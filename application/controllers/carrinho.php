<?php
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );


//require_once(APPPATH."libraries/tcpdf/tcpdf.php");

class Carrinho extends MY_Front {
    
    private $EhPreenchimentoManual;

    private $id_orcamento;
    private $url;
    private $cidade_link;
    
    public function __construct() {
        parent::__construct();
        $this->output->enable_profiler(TRUE);
        $this->id_orcamento = $this->session->userdata('id_orcamento');
        $this->url = $this->session->userdata('url');
        $this->cidade_link = $this->session->userdata('cidade_link');
                
    }
    
    public function index() {
        $this->data ['id_orcamento'] = $this->id_orcamento;
        if (!empty($this->id_orcamento)){
            $this->data ['url'] = $this->url.'/'.$this->cidade_link;
            
            $this->data ['mixs'] = $this->getPedidos_bkp();
            
            $this->data['script_increment'] = 1;

            $html = $this->layout->view('carrinho',$this->data);

            //$this->GerarPDF($html);

        } else
            redirect('home');
    }
    
    public function Remover($idPedido){
        $data = array(
            'status' => 'e'
        );
        $condicoes = array(
            array(
                'campo' => 'id_pedido',
                'valor' => $idPedido
            )
        );

        $this->superModel->update('pedido', $data, $condicoes);
        
        redirect('Carrinho');
    }
    
    public function Alterar(){
        if($this->form_validation->run('pedidos/servico')) {
            $data = array(
                'qntd' => $this->input->post('qntd')
            );

            $condicoes = array(
                array (
                    'campo' => 'id_pedido',
                    'valor' => $this->input->post('id_pedido')
                )
            );

            $this->superModel->update('pedido', $data, $condicoes);
        }
    }    

    public function Finalizar(){
        //update no pedido passando orçamento e finalizando todos os pedidos

        if($_POST){
            
            $data = array(
                'status' => 'f'
            );

            $condicao = array(
                array(
                    'campo' => 'id_orcamento',
                    'valor' => $this->input->post('id_orcamento') //$idOrcamento
                ),
                array (
                    'sinal' => '<>',
                    'campo' => 'status',
                    'valor' => 'e'
                )
            );

            //$this->superModel->update('pedido', $data, $condicao);

            if(isset($_POST['resposta'])){
                foreach ($_POST['selected']['id_subcategoria'] as $id_subcategoria => $id_profissional) {
                    foreach ($_POST['resposta'][$id_subcategoria][$id_profissional] as $key => $value) {
                        $this->superModel->insert('prof_pergunta_resposta_pedido', (array) json_decode($value));
                    }
                }
                
            }

            //$this->enviaEmailPedido($this->data);
            
            $this->layout->view('confirmacao-pedido', $this->data);

            $this->session->unset_userdata('url');
            $this->session->unset_userdata('id_orcamento');
        }
    }
   
    private function getPedidos_bkp(){
      echo $this->id_orcamento;
        $tem_pedido_sem_profissinal = '';
        $mixs = array();

        $profissionals = $this->superModel->query(
            "SELECT a.id_profissional, a.nome, (a.total_vezes - a.vezes) status_ordem, $this->id_orcamento id_orcamento
               FROM (
                SELECT 
                  s.id_profissional, u.nome, COUNT(*) vezes, 
                  (SELECT COUNT(*) FROM ( SELECT p.id_subcategoria FROM tbl_pedido p WHERE p.id_orcamento = $this->id_orcamento  AND p.status = 'a' GROUP BY p.id_subcategoria) a) total_vezes
                FROM
                  tbl_prof_subcateg s 
                LEFT JOIN tbl_profissional p ON (p.id_profissional = s.id_profissional)
                LEFT JOIN tbl_usuario u ON (u.id_usuario = p.id_usuario)
                WHERE s.id_subcategoria IN (SELECT pd.id_subcategoria FROM tbl_pedido pd WHERE pd.id_orcamento = $this->id_orcamento GROUP BY pd.id_subcategoria) 
                  AND s.status = 'a'
                GROUP BY s.id_profissional, u.nome ) a
                ORDER BY 3");

        $status_ordem = -1;
        $key = -1;
        foreach ($profissionals as $profissional) {
            
            if ($profissional->status_ordem <> $status_ordem){
                $key += 1;

                $sql = "SELECT * FROM tbl_prof_subcateg s WHERE s.STATUS = 'a' AND s.id_profissional = $profissional->id_profissional";
                $prof_subcategs = $this->superModel->query( $sql );
                $arrSubcategs = array();
                foreach ($prof_subcategs as $key => $value) {
                    $arrSubcategs[] = $value->id_subcategoria;
                }

                $status_ordem = $profissional->status_ordem;
                $pedidos = $this->getPedidosPorServico($this->id_orcamento, $arrSubcategs);
                $mix = new stdClass;
                $mix->pedidos = $pedidos;
                $mix->profs = array();
                $profissional->prof_subcategs = $prof_subcategs;
                array_push($mix->profs, $profissional);
                $mixs[$key] = $mix;
            } else if ($profissional->status_ordem == $status_ordem) {
                $sql = "SELECT * FROM tbl_prof_subcateg s WHERE s.STATUS = 'a' AND s.id_profissional = $profissional->id_profissional";
                $prof_subcategs = $this->superModel->query( $sql );
                $profissional->prof_subcategs = $prof_subcategs;
                array_push($mixs[$key]->profs, $profissional);
            }
            
        }

        

        foreach ($mixs as $keyMix => $mix) 
        {
            foreach ($mix->pedidos as $keyPedido => $valuePedido) 
            {   
                foreach ($mix->profs as $keyProf => $prof) 
                {
                    foreach ($prof->prof_subcategs as $keyProfSubCateg => $valueProfsubCateg) 
                    {                       
                        $in_filtro = "";
                        foreach($valuePedido->filtros as $keyFiltro => $filtro){
                          if($keyFiltro == 0)
                            $in_filtro .= "'$filtro->id_filtro'";
                          else
                            $in_filtro .= ",'$filtro->id_filtro'";
                        }

                        $sql_resposta_profs = "  SELECT pr.*, p.tipo, e.id_prof_enunciado
                                                  FROM tbl_prof_pergunta_resposta pr 
                                                  LEFT JOIN tbl_prof_pergunta_filtro pf ON (pr.id_prof_pergunta = pf.id_prof_pergunta)
                                                 INNER JOIN tbl_prof_pergunta p ON (pr.id_prof_pergunta = p.id_prof_pergunta)
                                                 INNER JOIN tbl_prof_enunciado e ON (e.id_prof_enunciado = p.id_prof_enunciado)
                                                 WHERE pr.id_prof_subcateg = $valueProfsubCateg->id_prof_subcateg
                                                   AND pf.id_categoria_servico = $valuePedido->id_categoria_servico
                                                   AND pf.id_servico = $valuePedido->id_servico
                                                   AND e.id_subcategoria = $valueProfsubCateg->id_subcategoria";
                        if (!empty($in_filtro))
                          $sql_resposta_profs .= " AND pf.id_filtro in ($in_filtro) 
                                                   AND (pf.tipo is null or pf.tipo = 'o') ";
                          $sql_resposta_profs .= " ORDER BY pr.vlr_primeiro DESC";


                        $respostas = $this->superModel->query( $sql_resposta_profs );
                        foreach ($respostas as $valueResposta) {
                            if (!isset($mixs[$keyMix]->profs[$keyProf]->prof_subcategs[$keyProfSubCateg]->respostas)){
                                $mixs[$keyMix]->profs[$keyProf]->prof_subcategs[$keyProfSubCateg]->respostas = array();
                            }
                            $valueResposta->Pedido = $valuePedido;
                            array_push($mixs[$keyMix]->profs[$keyProf]->prof_subcategs[$keyProfSubCateg]->respostas, $valueResposta);
                        }
                        
                    }
                }
            }
            
        }
       
            
        foreach ($mixs as $mix) { 
            foreach ($mix->profs as $keyProf => $prof) {
                //print_r($mix->profs);
            }
        }

        return $mixs;
    }

    private function getPedidos(){
      echo $this->id_orcamento;
        $tem_pedido_sem_profissinal = '';
        $mixs = array();

        $subcategorias = $this->superModel->query(" SELECT sb.id_subcategoria, sb.id_categoria, sb.subcategoria, sb.titulo, sb.status
                                                      FROM tbl_pedido p 
                                                      LEFT JOIN tbl_subcategoria sb ON (sb.id_subcategoria = p.id_subcategoria)
                                                     WHERE p.id_orcamento = {$this->id_orcamento}
                                                       AND sb.status = 'a'
                                                       GROUP BY sb.id_subcategoria, sb.id_categoria, sb.subcategoria, sb.titulo, sb.status");

        foreach ($subcategorias as $subcategoria) {
            $sql = "select * from ( 
                        SELECT s.id_profissional, s.id_prof_subcateg, s.id_subcategoria, pd.id_orcamento, u.nome, sc.subcategoria
                          FROM tbl_prof_subcateg s
                          LEFT JOIN tbl_profissional p ON (p.id_profissional = s.id_profissional)
                          LEFT JOIN tbl_usuario u ON (u.id_usuario = p.id_usuario)
                          LEFT JOIN tbl_categoria_servico cs ON (cs.id_subcategoria = s.id_subcategoria)
                          LEFT JOIN tbl_subcategoria sc ON (sc.id_categoria = cs.id_categoria AND sc.id_subcategoria = cs.id_subcategoria)
                          LEFT JOIN tbl_servico sv ON (sv.id_categoria_servico = cs.id_categoria_servico)
                          LEFT JOIN tbl_pedido pd ON (pd.id_servico = sv.id_servico)
                         WHERE s.status = 'a'
                           AND u.status = 'a'
                           AND pd.id_orcamento = {$this->id_orcamento}
                           and s.id_subcategoria = {$subcategoria->id_subcategoria}
                        GROUP BY s.id_profissional, s.id_prof_subcateg, s.id_subcategoria, pd.id_orcamento, u.nome, sc.subcategoria) a
                    order by a.subcategoria, a.nome";
            $prof_subcategs = $this->superModel->query( $sql );

            if (!empty($prof_subcategs)){
                $total_prof_subcategs = count($prof_subcategs);

                $pedidos = $this->getPedidosPorServico($this->id_orcamento, $subcategoria->id_subcategoria);

               
                if (!isset($mixs[$total_prof_subcategs])){
                    $mixs[$total_prof_subcategs] = new stdClass;
                    $mixs[$total_prof_subcategs]->pedidos = $pedidos;
                    $mixs[$total_prof_subcategs]->profs = array();
                    foreach ($prof_subcategs as $prof_subcateg) {
                        $objProf = new stdClass;
                        $objProf->id_profissional = $prof_subcateg->id_profissional;
                        $objProf->nome = $prof_subcateg->nome;
                        $objProf->id_orcamento = $prof_subcateg->id_orcamento;
                        $objProf->prof_subcategs = array();

                        $objProfSubcateg = new stdClass;
                        $objProfSubcateg->id_prof_subcateg = $prof_subcateg->id_prof_subcateg;
                        $objProfSubcateg->id_subcategoria = $prof_subcateg->id_subcategoria;

                        array_push($objProf->prof_subcategs, $objProfSubcateg);
                        array_push($mixs[$total_prof_subcategs]->profs, $objProf);
                    }
                } else {
                    foreach ($pedidos as $value) 
                    {
                        array_push($mixs[$total_prof_subcategs]->pedidos, $value);
                    }
                    
                    foreach ($prof_subcategs as $prof_subcateg)
                    {
                        $achouProf = False;
                        foreach ($mixs[$total_prof_subcategs]->profs as $key => $prof) 
                        {
                            if ($mixs[$total_prof_subcategs]->profs[$key]->id_profissional == $prof_subcateg->id_profissional){
                                $achouProf = True;
                                break;
                            }
                        }

                        if ($achouProf === False){
                            $objProf = new stdClass;
                            $objProf->id_profissional = $prof_subcateg->id_profissional;
                            $objProf->nome = $prof_subcateg->nome;
                            $objProf->id_orcamento = $prof_subcateg->id_orcamento;
                            $objProf->prof_subcategs = array();

                            $objProfSubcateg = new stdClass;
                            $objProfSubcateg->id_prof_subcateg = $prof_subcateg->id_prof_subcateg;
                            $objProfSubcateg->id_subcategoria = $prof_subcateg->id_subcategoria;

                            array_push($objProf->prof_subcategs, $objProfSubcateg);
                            array_push($mixs[$total_prof_subcategs]->profs, $objProf);
                        } else {
                            $objProfSubcateg = new stdClass;
                            $objProfSubcateg->id_prof_subcateg = $prof_subcateg->id_prof_subcateg;
                            $objProfSubcateg->id_subcategoria = $prof_subcateg->id_subcategoria;
                            array_push($mixs[$total_prof_subcategs]->profs[$key]->prof_subcategs, $objProfSubcateg);
                        }
                    }   
                }
            } else {
                $pedidos = $this->getPedidosPorServico($this->id_orcamento, $subcategoria->id_subcategoria);
                if (!empty($pedidos)){
                    //este pedido não tem profissional para realizar o serviço
                }
            }
        }

        foreach ($mixs as $keyMix => $mix) 
        {
            $arrayProf = array('');
            foreach ($mix->pedidos as $keyPedido => $valuePedido) 
            {   
                foreach ($mix->profs as $keyProf => $prof) 
                {
                    foreach ($prof->prof_subcategs as $keyProfSubCateg => $valueProfsubCateg) 
                    {
                        if (array_search($prof->id_profissional, $arrayProf) === FALSE)
                            array_push($arrayProf, $prof->id_profissional);                        
						
                        $in_filtro = "";
                        foreach($valuePedido->filtros as $keyFiltro => $filtro){
                          if($keyFiltro == 0)
                            $in_filtro .= "'$filtro->id_filtro'";
                          else
                            $in_filtro .= ",'$filtro->id_filtro'";
                        }

                        $sql_resposta_profs = "  SELECT pr.*, p.tipo, e.id_prof_enunciado
                                                  FROM tbl_prof_pergunta_resposta pr 
                                                  LEFT JOIN tbl_prof_pergunta_filtro pf ON (pr.id_prof_pergunta = pf.id_prof_pergunta)
                                                 INNER JOIN tbl_prof_pergunta p ON (pr.id_prof_pergunta = p.id_prof_pergunta)
                                                 INNER JOIN tbl_prof_enunciado e ON (e.id_prof_enunciado = p.id_prof_enunciado)
                                                 WHERE pr.id_prof_subcateg = $valueProfsubCateg->id_prof_subcateg
                                                   AND pf.id_categoria_servico = $valuePedido->id_categoria_servico
                                                   AND pf.id_servico = $valuePedido->id_servico
                                                   AND e.id_subcategoria = $valueProfsubCateg->id_subcategoria";
                        if (!empty($in_filtro))
                          $sql_resposta_profs .= " AND pf.id_filtro in ($in_filtro) 
                                                   AND (pf.tipo is null or pf.tipo = 'o') ";
                          $sql_resposta_profs .= " ORDER BY pr.vlr_primeiro DESC";
                        $respostas = $this->superModel->query( $sql_resposta_profs );
                        foreach ($respostas as $valueResposta) {
                            if (!isset($mixs[$keyMix]->profs[$keyProf]->prof_subcategs[$keyProfSubCateg]->respostas)){
                                $mixs[$keyMix]->profs[$keyProf]->prof_subcategs[$keyProfSubCateg]->respostas = array();
                            }
                            $valueResposta->Pedido = $valuePedido;
                            array_push($mixs[$keyMix]->profs[$keyProf]->prof_subcategs[$keyProfSubCateg]->respostas, $valueResposta);
                        }
                        
                    }
                }
            }
            
        }
       
            
        foreach ($mixs as $mix) { 
            foreach ($mix->profs as $keyProf => $prof) {
                //print_r($mix->profs);
            }
        }

        return $mixs;
    }

    private function getPedidosPorServico($id_orcamento, $id_subcategoria){
        if (is_array($id_subcategoria)){
            $subs = "";
            foreach ($id_subcategoria as $key => $value) {
                if (count($id_subcategoria)-1 == $key)
                    $subs .= "$value";
                else
                    $subs .= "$value,";
            }
        } else if (is_string($id_subcategoria)) $subs = $id_subcategoria;

        $pedidos = $this->superModel->query(
            "SELECT 
              p.id_pedido,
              p.id_servico,
              v.descricao servico,
              v.id_categoria_servico,
              c.descricao,
              c.imagem,
              p.id_orcamento,
              p.qntd,
              p.status,
              c.id_subcategoria,
              s.subcategoria 
            FROM tbl_pedido p 
            LEFT JOIN tbl_subcategoria s ON (s.id_subcategoria = p.id_subcategoria)
            LEFT JOIN tbl_categoria_servico c ON (c.id_categoria_servico = p.id_categoria_servico AND c.id_subcategoria = p.id_subcategoria)
            LEFT JOIN tbl_servico v ON (v.id_servico = p.id_servico AND v.id_categoria_servico = p.id_categoria_servico)
            WHERE p.id_orcamento = $id_orcamento 
              AND p.status <> 'e' 
              AND p.id_subcategoria IN ($subs) ");

        foreach ($pedidos as $key => $pedido){
            $sql = "SELECT c.descricao AS pergunta, c.id_pergunta, b.descricao AS filtro, a.valor, a.id_filtro "
                 . " FROM tbl_pedido_filtro a "
                 . " LEFT JOIN tbl_filtro b ON (b.id_filtro = a.id_filtro) "
                 . " LEFT JOIN tbl_pergunta c ON (c.id_pergunta = b.id_pergunta) "
                 . " WHERE a.id_pedido = $pedido->id_pedido";
            $filtro = $this->superModel->query( $sql );

            $pedidos[$key]->filtros = $filtro;
        }

        return $pedidos;
    }


    private function MontaValor($id_profissional){
        $profSubcategs = $this->getProfSubcateg($id_profissional);
        foreach ($profSubcategs as $profSubcateg) {
            $ProfPerguntas = $this->getProfPergunta($profSubcateg->id_subcategoria);
            //foreach ($ProfPerguntas as $ProfPergunta) {
                
            //}
        }

    }
    
    private function enviaEmailPedido($data) {
        $pedidos = $this->getPedidos();

        foreach($pedidos as $pedido) {
            $mensagemPedido .= '<ul class="list-unstyled">';
            $mensagemPedido .= '<li><strong>'.$pedido->Servico->categoria->descricao.'</strong></li>
                            <li>Categoria '.$pedido->Servico->categoria->subcategoria->descricao.'</li>
                            <li>Servico de '.$pedido->Servico->descricao.'</li>
                           <li>Quantidade: '.$pedido->qntd.'</li>';
            foreach($pedido->filtros as $filtro) {
                $mensagemPedido .= '<li>'.$filtro->pergunta. ' : '. 
                        empty($filtro->filtro) ? $filtro->valor : $filtro->filtro.'</li>';
            }
            $mensagemPedido .= '</ul>';
        }

        $configEmail = $this->superModel->getRow('config_email');
        $this->load->library('email');

        $mensagem = '<!DOCTYPE html>
                <html lang="pt-br">
                <head>
                    <title>Sossegue - Solicitação de serviço</title>
                    <meta charset="utf-8">
                    <meta name="viewport" content="width=device-width">
                    <style type="text/css">
                        /* CLIENT-SPECIFIC STYLES */
                        #outlook a{padding:0;} /* Force Outlook to provide a "view in browser" message */
                        .ReadMsgBody{width:100%;} .ExternalClass{width:100%;} /* Force Hotmail to display emails at full width */
                        .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} /* Force Hotmail to display normal line spacing */
                        body, table, td, a{-webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;} /* Prevent WebKit and Windows mobile changing default text sizes */
                        table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;} /* Remove spacing between tables in Outlook 2007 and up */
                        img{-ms-interpolation-mode:bicubic;} /* Allow smoother rendering of resized image in Internet Explorer */
                        /* RESET STYLES */
                        body{margin:0; padding:0;}
                        img{border:0; height:auto; line-height:100%; outline:none; text-decoration:none;}
                        table{border-collapse:collapse !important;}
                        body{height:100% !important; margin:0; padding:0; width:100% !important;}
                        /* iOS BLUE LINKS */
                        .appleBody a {color:#68440a; text-decoration: none;}
                        .appleFooter a {color:#999999; text-decoration: none;}
                        /* MOBILE STYLES */
                        @media screen and (max-width: 525px) {
                            /* ALLOWS FOR FLUID TABLES */
                            table[class="wrapper"]{
                              width:100% !important;
                            }
                            /* ADJUSTS LAYOUT OF LOGO IMAGE */
                            td[class="logo"]{
                              text-align: left;
                              padding: 20px 0 20px 0 !important;
                            }
                            td[class="logo"] img{
                              margin:0 auto!important;
                            }
                            /* USE THESE CLASSES TO HIDE CONTENT ON MOBILE */
                            td[class="mobile-hide"]{
                              display:none;}
                            img[class="mobile-hide"]{
                              display: none !important;
                            }
                            img[class="img-max"]{
                              max-width: 100% !important;
                              height:auto !important;
                            }
                            /* FULL-WIDTH TABLES */
                            table[class="responsive-table"]{
                              width:100%!important;
                            }
                            /* UTILITY CLASSES FOR ADJUSTING PADDING ON MOBILE */
                            td[class="padding"]{
                              padding: 10px 5% 15px 5% !important;
                            }
                            td[class="padding-copy"]{
                              padding: 10px 5% 10px 5% !important;
                              text-align: center;
                            }
                            td[class="padding-meta"]{
                              padding: 30px 5% 0px 5% !important;
                              text-align: center;
                            }
                            td[class="no-pad"]{
                              padding: 0 0 20px 0 !important;
                            }
                            td[class="no-padding"]{
                              padding: 0 !important;
                            }
                            td[class="section-padding"]{
                              padding: 50px 15px 50px 15px !important;
                            }
                            td[class="section-padding-bottom-image"]{
                              padding: 50px 15px 0 15px !important;
                            }
                            /* ADJUST BUTTONS ON MOBILE */
                            td[class="mobile-wrapper"]{
                                padding: 10px 5% 15px 5% !important;
                            }
                            table[class="mobile-button-container"]{
                                margin:0 auto;
                                width:100% !important;
                            }
                            a[class="mobile-button"]{
                                width:80% !important;
                                padding: 15px !important;
                                border: 0 !important;
                                font-size: 16px !important;
                            }
                        }
                    </style>
                </head>
                <body style="margin: 0; padding: 0;">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td bgcolor="#ffffff">
                                <div align="center" style="padding: 0px 15px 0px 15px;">
                                    <table border="0" cellpadding="0" cellspacing="0" width="500" class="wrapper">
                                        <tr>
                                            <td style="padding: 20px 0px 0px 0px;" class="logo">
                                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td bgcolor="#ffffff" width="100" align="left"><a href="'.base_url().'" target="_blank"><img alt="Logo Sossegue" src="'.base_url("assets/img/logo_mod-a.png").'" width="130" style="display: block;" border="0"></a></td>
                                                        <td bgcolor="#ffffff" width="400" align="right" class="mobile-hide">
                                                            <table border="0" cellpadding="0" cellspacing="0">
                                                                <tr>
                                                                    <td align="right" style="padding: 0 0 5px 0; font-size: 14px; font-family: Arial, sans-serif; color: #666666; text-decoration: none;"><span style="color: #666666; text-decoration: none;"><a href="'.base_url().'" target="_blank">SOSSEGUE</a><br>Sua vida mais tranquila</span></td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#ffffff" align="center" style="padding: 30px 15px 70px 15px;" class="section-padding">
                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="responsive-table">
                                    <tr>
                                        <td>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td>
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                            <tr>
                                                                <td align="center" style="padding: 20px 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding-copy">Olá, o cliente '.$this->session->userdata('nome').' está a procura de profissionais. <br /> 
                                                                    Descrição: '.$this->session->userdata('descricao').'<br />
                                                                    Contato: '.$this->session->userdata('nome').', '.$this->session->userdata('celular').', '.$this->session->userdata('email').', '.$this->session->userdata('bairro').'<br />
                                                                    Pedido: <br/>'.$mensagemPedido.'
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="mobile-button-container">
                                                            <tr>
                                                                <td align="center" style="padding: 20px 0 0 0;font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding-copy">

                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </body>
                </html>';

        $emailEnviado = $this->email
            ->from($configEmail->username, 'Sossegue')
            ->to('contato@sossegue.com.br, contato.sossegue@gmail.com')
            ->subject("Sossegue - Solicitação de orçamento")
            ->message($mensagem)
            ->send();
        
        $this->email
            ->from($configEmail->username, 'Sossegue')
            ->to('matheus.gnu@gmail.com')
            ->subject("Sossegue - Solicitação de orçamento")
            ->message('Existe um novo orçamento finalizado <br/>'
                    . 'Dados do contato: '.
                    $this->session->userdata('nome').', '.
                    $this->session->userdata('celular').', '.
                    $this->session->userdata('email'))
            ->send();
    }    

    /*private function GerarPDF($html){
        $this->load->library('Pdf');

        
        ob_clean();

        /*$pdf = new pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetTitle('My Title');
        $pdf->SetHeaderMargin(30);
        $pdf->SetTopMargin(20);
        $pdf->setFooterMargin(20);
        $pdf->SetAutoPageBreak(true);
        $pdf->SetAuthor('Sossegue');
        $pdf->SetDisplayMode('real', 'default');/

        $pdf->AddPage();

        $pdf->Write(5, $html);
        $pdf->Output('My-File-Name.pdf', 'I');

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Nicola Asuni');
        $pdf->SetTitle('TCPDF Example 006');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

        //$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set font
        $pdf->SetFont('dejavusans', '', 10);

        // add a page
        $pdf->AddPage();

        // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');
        //$pdf->Write(5, $html);

        //Close and output PDF document
        $pdf->Output('example_006.pdf', 'I');
    }*/



    /*


                            $sql_resposta_inicial = "SELECT pr.*, p.tipo, e.id_prof_enunciado
                                                      FROM tbl_prof_pergunta_resposta pr 
                                                      LEFT JOIN tbl_prof_pergunta p ON (pr.id_prof_pergunta = p.id_prof_pergunta)
                                                      LEFT JOIN tbl_prof_enunciado e ON (e.id_prof_enunciado = p.id_prof_enunciado)
                                                     WHERE pr.id_prof_subcateg = $valueProfsubCateg->id_prof_subcateg
                                                       AND p.tipo IN ('valor_minimo_visita')
                                                       AND e.id_subcategoria = $valueProfsubCateg->id_subcategoria ";
                            $valores_inicials = $this->superModel->query( $sql_resposta_inicial );
                            foreach ($valores_inicials as $valueInicial) {
                                if (!isset($mixs[$keyMix]->profs[$keyProf]->prof_subcategs[$keyProfSubCateg]->valores_iniciais)){
                                    $mixs[$keyMix]->profs[$keyProf]->prof_subcategs[$keyProfSubCateg]->valores_iniciais = array();
                                }
                                array_push($mixs[$keyMix]->profs[$keyProf]->prof_subcategs[$keyProfSubCateg]->valores_iniciais, $valueInicial);
                            }
                        }


$sql_config_valres = " SELECT pr.* 
                                                 FROM tbl_prof_pergunta_resposta pr
                                                 LEFT JOIN tbl_prof_pergunta_filtro pf ON (pr.id_prof_pergunta = pf.id_prof_pergunta)
                                                WHERE pr.id_prof_subcateg = $valueProfsubCateg->id_prof_subcateg
                                                  --AND pr.faz_servico <> 'S'
                                                  AND pf.id_categoria_servico = $valuePedido->id_categoria_servico
                                                  AND pf.id_servico = $valuePedido->id_servico ";
                                                if (!empty($in_filtro))
                          $sql_config_valres .= " AND pf.id_filtro in ($in_filtro) ";
                          $sql_config_valres .= " AND pf.tipo IN ('c','v')
                                                  AND (pr.vlr_porcent <> 0 --OR pr.checkbox = 'S')";
                        $valores_config = $this->superModel->query( $sql_config_valres );
                        foreach ($valores_config as $valueValor_Config) {
                            if (!isset($mixs[$keyMix]->profs[$keyProf]->prof_subcategs[$keyProfSubCateg]->valores_config)){
                                $mixs[$keyMix]->profs[$keyProf]->prof_subcategs[$keyProfSubCateg]->valores_config = array();
                            }
                            array_push($mixs[$keyMix]->profs[$keyProf]->prof_subcategs[$keyProfSubCateg]->valores_config, $valueValor_Config);
                        }

    */
}