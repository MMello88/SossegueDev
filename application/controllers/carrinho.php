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
    
    /*old function*/
    /*public function Finalizar($idOrcamento){
        //update no pedido passando orçamento e finalizando todos os pedidos
        $data = array(
            'status' => 'f'
        );

        $condicao = array(
            array(
                'campo' => 'id_orcamento',
                'valor' => $idOrcamento
            ),
            array (
                'sinal' => '<>',
                'campo' => 'status',
                'valor' => 'e'
            )
        );

        $this->superModel->update('pedido', $data, $condicao);

        //$this->enviaEmailPedido($this->data);
        
        $this->layout->view('confirmacao-pedido', $this->data);

        $this->session->unset_userdata('url');
        $this->session->unset_userdata('id_orcamento');
    }*/

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

        $subcategorias = $this->superModel->query(" SELECT sb.id_subcategoria, sb.id_categoria, sb.subcategoria, sb.titulo, sb.status
                                                      FROM tbl_pedido p 
                                                      LEFT JOIN tbl_servico s ON (s.id_servico = p.id_servico)
                                                      LEFT JOIN tbl_categoria_servico c ON (c.id_categoria_servico = s.id_categoria_servico)
                                                      LEFT JOIN tbl_subcategoria sb ON (sb.id_subcategoria = c.id_subcategoria)
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
                        if (array_search($prof->id_profissional, $arrayProf) === FALSE){
                            array_push($arrayProf, $prof->id_profissional);
                            $sql_resposta_inicial = "SELECT pr.*, p.tipo tipo
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
						
                        $in_filtro = "";
                        foreach($valuePedido->filtros as $keyFiltro => $filtro){
                          if($keyFiltro == 0)
                            $in_filtro .= "'$filtro->id_filtro'";
                          else
                            $in_filtro .= ",'$filtro->id_filtro'";
                        }

                        $sql_resposta_profs = "  SELECT pr.*, '' tipo
                                                  FROM tbl_prof_pergunta_resposta pr 
                                                  LEFT JOIN tbl_prof_pergunta_filtro pf ON (pr.id_prof_pergunta = pf.id_prof_pergunta)
                                                 WHERE pr.id_prof_subcateg = $valueProfsubCateg->id_prof_subcateg
                                                   AND pf.id_categoria_servico = $valuePedido->id_categoria_servico
                                                   AND pf.id_servico = $valuePedido->id_servico";
                        if (!empty($in_filtro))
                          $sql_resposta_profs .= " AND pf.id_filtro in ($in_filtro) ";
                        $sql_resposta_profs .= "   AND (pf.tipo is null or pf.tipo = 'o')
                                                   ORDER BY pr.vlr_primeiro DESC";
                        $respostas = $this->superModel->query( $sql_resposta_profs );
                        foreach ($respostas as $valueResposta) {
                            if (!isset($mixs[$keyMix]->profs[$keyProf]->prof_subcategs[$keyProfSubCateg]->respostas)){
                                $mixs[$keyMix]->profs[$keyProf]->prof_subcategs[$keyProfSubCateg]->respostas = array();
                            }
                            $valueResposta->Pedido = $valuePedido;
                            array_push($mixs[$keyMix]->profs[$keyProf]->prof_subcategs[$keyProfSubCateg]->respostas, $valueResposta);
                        }
                        
                        $sql_config_valres = " SELECT pr.* 
                                                 FROM tbl_prof_pergunta_resposta pr
                                                 LEFT JOIN tbl_prof_pergunta_filtro pf ON (pr.id_prof_pergunta = pf.id_prof_pergunta)
                                                WHERE pr.id_prof_subcateg = $valueProfsubCateg->id_prof_subcateg
                                                  AND pr.faz_servico <> 'S'
                                                  AND pf.id_categoria_servico = $valuePedido->id_categoria_servico
                                                  AND pf.id_servico = $valuePedido->id_servico ";
												if (!empty($in_filtro))
                          $sql_config_valres .= " AND pf.id_filtro in ($in_filtro) ";
                          $sql_config_valres .= " AND pf.tipo IN ('c','v')
                                                  AND (pr.vlr_porcent <> 0 OR pr.checkbox = 'S')";
                        $valores_config = $this->superModel->query( $sql_config_valres );
                        foreach ($valores_config as $valueValor_Config) {
                            if (!isset($mixs[$keyMix]->profs[$keyProf]->prof_subcategs[$keyProfSubCateg]->valores_config)){
                                $mixs[$keyMix]->profs[$keyProf]->prof_subcategs[$keyProfSubCateg]->valores_config = array();
                            }
                            array_push($mixs[$keyMix]->profs[$keyProf]->prof_subcategs[$keyProfSubCateg]->valores_config, $valueValor_Config);
                        }
                    }
                }
            }
            
        }
        //depois do mix formado, tenho q colocar os valores por orçamento
        /*foreach ($mixs as $mix) 
        {
            foreach ($mix->profs as $keyProf => $prof) 
            {
                //echo $keyProf;
                $mix->profs[$keyProf]->valor_visita = '';
                $mix->profs[$keyProf]->valor_instalacao = '';
                $mix->profs[$keyProf]->valor_primeiro = '';
                $mix->profs[$keyProf]->valor_add = '';
                $this->EhPreenchimentoManual = '';
                foreach ($prof->prof_subcategs as $keyProfSubcateg => $prof_subcateg) 
                {
                    $mix->profs[$keyProf]->prof_subcategs[$keyProfSubcateg]->valor_primeiro = '';
                    $mix->profs[$keyProf]->prof_subcategs[$keyProfSubcateg]->valor_add = '';
                    $mix->profs[$keyProf]->prof_subcategs[$keyProfSubcateg]->pedidos = array();
                    $data = array (
                        'select' => '*',
                        'condicoes' => array (
                            array (
                                'campo' => 'id_prof_subcateg',
                                'valor' => $prof_subcateg->id_prof_subcateg
                            ) 
                        )
                    );

                    $pergunta_respostas = $this->superModel->select ( 'prof_pergunta_resposta', $data );

                    // for somente para buscar os valores iniciais
                    foreach ($pergunta_respostas as $pergunta_resposta) 
                    {
                        $data = array (
                            'select' => '*',
                            'condicoes' => array (
                                array (
                                    'campo' => 'id_prof_pergunta',
                                    'valor' => $pergunta_resposta->id_prof_pergunta
                                ) 
                            )
                        );

                        $prof_pergunta = $this->superModel->select ( 'prof_pergunta', $data )[0];

                        if ($prof_pergunta->perg_ini == "S")
                        {
                            if ($this->EhPreenchimentoManual === '')
                            {
                                //busca por valor preenchido pelo prestador 
                                if ($prof_pergunta->perg_ini == "S" and 
                                    $prof_pergunta->sn_checkbox == "S" and 
                                    $prof_pergunta->tem_vlr_primeiro == "N" and
                                    $prof_pergunta->tem_vlr_adicional == "N" and
                                    $prof_pergunta->tem_vlr_procent == "N" and
                                    $prof_pergunta->tem_vlr_qntd == "N" and
                                    $prof_pergunta->tem_faz_servico == "N" and
                                    $prof_pergunta->sinal == "" and
                                    $pergunta_resposta->checkbox == "S"){
                                        $this->EhPreenchimentoManual = True;
                                        //echo $prof->id_profissional;
                                        //print_r($prof_pergunta->pergunta);
                                        //echo "<br/>";
                                } else if ($prof_pergunta->perg_ini == "S" and 
                                    $prof_pergunta->sn_checkbox == "S" and 
                                    $prof_pergunta->tem_vlr_primeiro == "N" and
                                    $prof_pergunta->tem_vlr_adicional == "N" and
                                    $prof_pergunta->tem_vlr_procent == "N" and
                                    $prof_pergunta->tem_vlr_qntd == "N" and
                                    $prof_pergunta->tem_faz_servico == "N" and
                                    $prof_pergunta->sinal == "" and

                                    $pergunta_resposta->checkbox == "S"){
                                        $this->EhPreenchimentoManual = False;
                                        //echo $prof->id_profissional;
                                        //print_r($prof_pergunta->pergunta);
                                        //echo "<br/>";
                                }
                            }

                            if ($this->EhPreenchimentoManual === True)
                            {
                                if ($prof_pergunta->perg_ini == "S" and 
                                    $prof_pergunta->sn_checkbox == "N" and 
                                    $prof_pergunta->tem_vlr_primeiro == "S" and
                                    $prof_pergunta->tem_vlr_adicional == "N" and
                                    $prof_pergunta->tem_vlr_procent == "N" and
                                    $prof_pergunta->tem_vlr_qntd == "N" and
                                    $prof_pergunta->tem_faz_servico == "N" and
                                    $prof_pergunta->sinal == "" and
                                    $pergunta_resposta->checkbox == ""){
                                        //echo $prof->nome;
                                        $mix->profs[$keyProf]->valor_visita = $pergunta_resposta->vlr_primeiro;
                                        //echo "VALOR DA VISITA:" . $pergunta_resposta->vlr_primeiro;
                                        //echo "<br/>";
                                }

                                if ($prof_pergunta->perg_ini == "S" and 
                                    $prof_pergunta->sn_checkbox == "S" and 
                                    $prof_pergunta->tem_vlr_primeiro == "N" and
                                    $prof_pergunta->tem_vlr_adicional == "N" and
                                    $prof_pergunta->tem_vlr_procent == "N" and
                                    $prof_pergunta->tem_vlr_qntd == "N" and
                                    $prof_pergunta->tem_faz_servico == "N" and
                                    $prof_pergunta->sinal == "" and
                                    $pergunta_resposta->checkbox == ""){
                                       $mix->profs[$keyProf]->valor_instalacao = '';
                                        //$this->TemValorVisita = True;
                                        //echo "<br/>";
                                        //echo $prof_subcateg->id_profissional;
                                        //echo "estima presencial";
                                        //print_r($prof_pergunta->id_prof_pergunta);
                                        //print_r($prof_pergunta->pergunta);
                                        //echo "<br/>";
                                } else if ($prof_pergunta->perg_ini == "S" and 
                                            $prof_pergunta->sn_checkbox == "S" and 
                                            $prof_pergunta->tem_vlr_primeiro == "S" and
                                            $prof_pergunta->tem_vlr_adicional == "N" and
                                            $prof_pergunta->tem_vlr_procent == "N" and
                                            $prof_pergunta->tem_vlr_qntd == "N" and
                                            $prof_pergunta->tem_faz_servico == "N" and
                                            $prof_pergunta->sinal == "" and

                                            $pergunta_resposta->checkbox == ""){
                                               $mix->profs[$keyProf]->valor_instalacao = $pergunta_resposta->vlr_primeiro;
                                               //$this->TemValorVisita = True;
                                               // echo "<br/>";
                                               // echo $prof_subcateg->id_profissional;
                                               // echo "VALOR DA instalação:" . $pergunta_resposta->vlr_primeiro;
                                               // print_r($prof_pergunta->id_prof_pergunta);
                                               // print_r($prof_pergunta->pergunta);
                                               // echo "<br/>";
                                }
                            }
                        }
                    }  
                }  
            }
        }*/

            
        foreach ($mixs as $mix) { 
            foreach ($mix->profs as $keyProf => $prof) {
                //print_r($mix->profs);
            }
        }

        return $mixs;
    }

    private function getPedidosPorServico($id_orcamento, $id_subcategoria){
        $pedidos = $this->superModel->query(
            "select pd.id_pedido, pd.id_servico, sv.id_categoria_servico, pd.id_orcamento, pd.qntd, pd.status, cs.id_subcategoria
               from tbl_pedido pd 
               left join tbl_servico sv on (pd.id_servico = sv.id_servico)
               left join tbl_categoria_servico cs on (cs.id_categoria_servico = sv.id_categoria_servico)
              where pd.id_orcamento = {$id_orcamento}
                and cs.id_subcategoria = {$id_subcategoria}
                and pd.status <> 'e'
              group by pd.id_pedido, pd.id_servico, pd.id_orcamento, pd.qntd, pd.status, cs.id_subcategoria");

        foreach ($pedidos as $key => $pedido){
            $sql = "SELECT c.descricao AS pergunta, c.id_pergunta, b.descricao AS filtro, a.valor, a.id_filtro "
                 . " FROM tbl_pedido_filtro a "
                 . " LEFT JOIN tbl_filtro b ON (b.id_filtro = a.id_filtro) "
                 . " LEFT JOIN tbl_pergunta c ON (c.id_pergunta = b.id_pergunta) "
                 . " WHERE a.id_pedido = $pedido->id_pedido";
            $filtro = $this->superModel->query( $sql );

            $pedidos[$key]->filtros = $filtro;

            $data = array (
                'select' => 'id_categoria_servico, descricao',
                'condicoes' => array (
                    array (
                        'campo' => 'id_servico',
                        'valor' => $pedido->id_servico
                    )
                )
            );

            $servico = $this->superModel->select( 'servico', $data );
            $servico = $servico[0];

            $pedidos[$key]->Servico = $servico;

            $data = array (
                'select' => 'id_categoria_servico, id_subcategoria, descricao, imagem',
                'condicoes' => array (
                    array (
                        'campo' => 'id_categoria_servico',
                        'valor' => $servico->id_categoria_servico
                    ) 
                )
            );

            $categoria_servico = $this->superModel->select ( 'categoria_servico', $data );
            $categoria_servico = $categoria_servico[0];

            $pedidos[$key]->Servico->categoria = $categoria_servico;

            $data = array (
                'select' => 'subcategoria as descricao',
                'condicoes' => array (
                    array (
                        'campo' => 'id_subcategoria',
                        'valor' => $categoria_servico->id_subcategoria
                    ) 
                )
            );

            $subcategoria = $this->superModel->select ( 'subcategoria', $data );
            $subcategoria = $subcategoria[0];

            $pedidos[$key]->Servico->categoria->subcategoria = $subcategoria;
        }

        return $pedidos;
    }

/*
    private function getPedidos_bkp_bkp(){
        $mix = array();

        $data = array (
            'select' => 'id_pedido, id_orcamento, id_servico, qntd',
            'condicoes' => array (
                array (
                    'campo' => 'id_orcamento',
                    'valor' => $this->id_orcamento
                ),
                array (
                    'sinal' => '<>',
                    'campo' => 'status',
                    'valor' => 'e'
                )
            ) 
        );

        $pedidos = $this->superModel->select ( 'pedido', $data );

        $total_pedido = 0;
        foreach ($pedidos as $key => $pedido){   
            $total_pedido++;

            $sql = "SELECT c.descricao AS pergunta, b.descricao AS filtro, a.valor "
                 . " FROM tbl_pedido_filtro a "
                 . " LEFT JOIN tbl_filtro b ON (b.id_filtro = a.id_filtro) "
                 . " LEFT JOIN tbl_pergunta c ON (c.id_pergunta = b.id_pergunta) "
                 . " WHERE a.id_pedido = $pedido->id_pedido";
            $filtro = $this->superModel->query( $sql );

            $pedidos[$key]->filtros = $filtro;

            $data = array (
                'select' => 'id_categoria_servico, descricao',
                'condicoes' => array (
                    array (
                        'campo' => 'id_servico',
                        'valor' => $pedido->id_servico
                    )
                )
            );

            $servico = $this->superModel->select( 'servico', $data );
            $servico = $servico[0];

            $pedidos[$key]->Servico = $servico;

            $data = array (
                'select' => 'id_categoria_servico, id_subcategoria, descricao, imagem',
                'condicoes' => array (
                    array (
                        'campo' => 'id_categoria_servico',
                        'valor' => $servico->id_categoria_servico
                    ) 
                )
            );

            $categoria_servico = $this->superModel->select ( 'categoria_servico', $data );
            $categoria_servico = $categoria_servico[0];

            $pedidos[$key]->Servico->categoria = $categoria_servico;

            $data = array (
                'select' => 'subcategoria as descricao',
                'condicoes' => array (
                    array (
                        'campo' => 'id_subcategoria',
                        'valor' => $categoria_servico->id_subcategoria
                    ) 
                )
            );

            $subcategoria = $this->superModel->select ( 'subcategoria', $data );
            $subcategoria = $subcategoria[0];

            $pedidos[$key]->Servico->categoria->subcategoria = $subcategoria;
        }

        $sql = "SELECT s.id_profissional, s.id_prof_subcateg, pd.id_orcamento, u.nome
                  FROM tbl_prof_subcateg s
                  LEFT JOIN tbl_profissional p ON (p.id_profissional = s.id_profissional)
                  LEFT JOIN tbl_usuario u ON (u.id_usuario = p.id_usuario)
                  LEFT JOIN tbl_categoria_servico cs ON (cs.id_subcategoria = s.id_subcategoria)
                  LEFT JOIN tbl_servico sv ON (sv.id_categoria_servico = cs.id_categoria_servico)
                  LEFT JOIN tbl_pedido pd ON (pd.id_servico = sv.id_servico)
                 WHERE s.status = 'a'
                   AND u.status = 'a'
                   AND pd.id_orcamento = {$this->id_orcamento} 
                GROUP BY s.id_profissional, s.id_prof_subcateg, pd.id_orcamento, u.nome " ;


        
        $profs = $this->superModel->query( $sql );

        $mixs[0] = new stdClass;
        $mixs[0]->pedidos = $pedidos;
        $mixs[0]->profs = $profs;

        $mixs[1] = new stdClass;
        $mixs[1]->pedidos = $pedidos;
        $mixs[1]->profs = $profs;

        $mixs[2] = new stdClass;
        $mixs[2]->pedidos = $pedidos;
        $mixs[2]->profs = $profs;

        
        foreach ($mixs as $mix) { 
            foreach ($mix->profs as $key => $prof_subcateg) {
                $mix->profs[$key]->valor_visita = '';
                $mix->profs[$key]->valor_instalacao = '';
                $this->EhPreenchimentoManual = '';
                //tbl_prof_pergunta_resposta
                $data = array (
                    'select' => '*',
                    'condicoes' => array (
                        array (
                            'campo' => 'id_prof_subcateg',
                            'valor' => $prof_subcateg->id_prof_subcateg
                        ) 
                    )
                );

                $pergunta_respostas = $this->superModel->select ( 'prof_pergunta_resposta', $data );

                //die();
                // for somente para buscar os valores iniciais
                foreach ($pergunta_respostas as $pergunta_resposta) {
                    $data = array (
                        'select' => '*',
                        'condicoes' => array (
                            array (
                                'campo' => 'id_prof_pergunta',
                                'valor' => $pergunta_resposta->id_prof_pergunta
                            ) 
                        )
                    );

                    $prof_pergunta = $this->superModel->select ( 'prof_pergunta', $data )[0];

                    if ($this->EhPreenchimentoManual === '')
                    {
                        
                        if ($prof_pergunta->perg_ini == "S" and 
                            $prof_pergunta->sn_checkbox == "S" and 
                            $prof_pergunta->tem_vlr_primeiro == "N" and
                            $prof_pergunta->tem_vlr_adicional == "N" and
                            $prof_pergunta->tem_vlr_procent == "N" and
                            $prof_pergunta->tem_vlr_qntd == "N" and
                            $prof_pergunta->tem_faz_servico == "N" and
                            $prof_pergunta->sinal == "" and
                            $pergunta_resposta->checkbox == "S"){
                                $this->EhPreenchimentoManual = True;
                                echo $prof_subcateg->id_profissional;
                                print_r($prof_pergunta->pergunta);
                                echo "<br/>";
                        } else if ($prof_pergunta->perg_ini == "S" and 
                            $prof_pergunta->sn_checkbox == "S" and 
                            $prof_pergunta->tem_vlr_primeiro == "N" and
                            $prof_pergunta->tem_vlr_adicional == "N" and
                            $prof_pergunta->tem_vlr_procent == "N" and
                            $prof_pergunta->tem_vlr_qntd == "N" and
                            $prof_pergunta->tem_faz_servico == "N" and
                            $prof_pergunta->sinal == "" and
                            $pergunta_resposta->checkbox == "S"){
                                $this->EhPreenchimentoManual = False;
                                echo $prof_subcateg->id_profissional;
                                print_r($prof_pergunta->pergunta);
                                echo "<br/>";
                        }
                    }


                    if ($this->EhPreenchimentoManual === True)
                    {
                        
                        if ($prof_pergunta->perg_ini == "S" and 
                            $prof_pergunta->sn_checkbox == "N" and 
                            $prof_pergunta->tem_vlr_primeiro == "S" and
                            $prof_pergunta->tem_vlr_adicional == "N" and
                            $prof_pergunta->tem_vlr_procent == "N" and
                            $prof_pergunta->tem_vlr_qntd == "N" and
                            $prof_pergunta->tem_faz_servico == "N" and
                            $prof_pergunta->sinal == "" and
                            $pergunta_resposta->checkbox == ""){
                               $mix->profs[$key]->valor_visita = $pergunta_resposta->vlr_primeiro;
                                echo "VALOR DA VISITA:" . $pergunta_resposta->vlr_primeiro;
                                echo "<br/>";
                        }

                        if ($prof_pergunta->perg_ini == "S" and 
                            $prof_pergunta->sn_checkbox == "S" and 
                            $prof_pergunta->tem_vlr_primeiro == "N" and
                            $prof_pergunta->tem_vlr_adicional == "N" and
                            $prof_pergunta->tem_vlr_procent == "N" and
                            $prof_pergunta->tem_vlr_qntd == "N" and
                            $prof_pergunta->tem_faz_servico == "N" and
                            $prof_pergunta->sinal == "" and
                            $pergunta_resposta->checkbox == ""){
                               $mix->profs[$key]->valor_instalacao = '';
                                //$this->TemValorVisita = True;
                                echo "<br/>";
                                echo $prof_subcateg->id_profissional;
                                echo "estima presencial";
                                print_r($prof_pergunta->id_prof_pergunta);
                                print_r($prof_pergunta->pergunta);
                                echo "<br/>";
                        } else if ($prof_pergunta->perg_ini == "S" and 
                                    $prof_pergunta->sn_checkbox == "S" and 
                                    $prof_pergunta->tem_vlr_primeiro == "S" and
                                    $prof_pergunta->tem_vlr_adicional == "N" and
                                    $prof_pergunta->tem_vlr_procent == "N" and
                                    $prof_pergunta->tem_vlr_qntd == "N" and
                                    $prof_pergunta->tem_faz_servico == "N" and
                                    $prof_pergunta->sinal == "" and
                                    $pergunta_resposta->checkbox == ""){
                                       $mix->profs[$key]->valor_instalacao = $pergunta_resposta->vlr_primeiro;
                                        //$this->TemValorVisita = True;
                                        echo "<br/>";
                                        echo $prof_subcateg->id_profissional;
                                        echo "VALOR DA instalação:" . $pergunta_resposta->vlr_primeiro;
                                        print_r($prof_pergunta->id_prof_pergunta);
                                        print_r($prof_pergunta->pergunta);
                                        echo "<br/>";
                        }

                    }
                }    
            }
        }

        return $mixs;
    }

    private function getPedidos(){
        $mix = array();
        $data = array (
            'select' => 'id_subcategoria, subcategoria',
            'condicoes' => array (
                array (
                    'campo' => 'status',
                    'valor' => 'a'
                )
            ) 
        );

        $tbl_subcategoria = $this->superModel->select ( 'subcategoria', $data );
        //echo count($tbl_subcategoria);
        foreach ($tbl_subcategoria as $key_item => $item_subcategoria)
        {

            $data = array (
                'select' => 'pedido.id_pedido, pedido.id_orcamento, pedido.id_servico, pedido.qntd',
                'join' => array(
                    array(
                        'tabela' => 'servico',
                        'on' => 'servico.id_servico = pedido.id_servico'
                    ),
                    array(
                        'tabela' => 'categoria_servico',
                        'on' => 'categoria_servico.id_categoria_servico = servico.id_categoria_servico'
                    )
                ),
                'condicoes' => array (
                    array (
                        'campo' => 'pedido.id_orcamento',
                        'valor' => $this->id_orcamento
                    ),
                    array (
                        'campo' => 'categoria_servico.id_subcategoria',
                        'valor' => $item_subcategoria->id_subcategoria
                    ),
                    array (
                        'sinal' => '<>',
                        'campo' => 'pedido.status',
                        'valor' => 'e'
                    )
                ) 
            );

            $pedidos = $this->superModel->select ( 'pedido', $data );
            
            foreach ($pedidos as $key => $pedido){
                $sql = "SELECT c.descricao AS pergunta, b.descricao AS filtro, a.valor "
                     . " FROM tbl_pedido_filtro a "
                     . " LEFT JOIN tbl_filtro b ON (b.id_filtro = a.id_filtro) "
                     . " LEFT JOIN tbl_pergunta c ON (c.id_pergunta = b.id_pergunta) "
                     . " WHERE a.id_pedido = $pedido->id_pedido";
                $filtro = $this->superModel->query( $sql );

                $pedidos[$key]->filtros = $filtro;

                $data = array (
                    'select' => 'id_categoria_servico, descricao',
                    'condicoes' => array (
                        array (
                            'campo' => 'id_servico',
                            'valor' => $pedido->id_servico
                        )
                    )
                );

                $servico = $this->superModel->select( 'servico', $data );
                $servico = $servico[0];

                $pedidos[$key]->Servico = $servico;

                $data = array (
                    'select' => 'id_categoria_servico, id_subcategoria, descricao, imagem',
                    'condicoes' => array (
                        array (
                            'campo' => 'id_categoria_servico',
                            'valor' => $servico->id_categoria_servico
                        ) 
                    )
                );

                $categoria_servico = $this->superModel->select ( 'categoria_servico', $data );
                $categoria_servico = $categoria_servico[0];

                $pedidos[$key]->Servico->categoria = $categoria_servico;




                $data = array (
                    'select' => 'profissional.id_profissional, usuario.nome',
                    'join' => array(
                        array(
                            'tabela' => 'profissional',
                            'on' => 'profissional.id_profissional = prof_subcateg.id_profissional'
                        ),
                        array(
                            'tabela' => 'usuario',
                            'on' => 'usuario.id_usuario = profissional.id_usuario'
                        )
                    ),
                    'condicoes' => array (
                        array (
                            'campo' => 'prof_subcateg.id_subcategoria',
                            'valor' => $item_subcategoria->id_subcategoria,
                        ),
                        array (
                            'campo' => 'prof_subcateg.status',
                            'valor' => 'a'
                        ),
                        array (
                            'campo' => 'usuario.status',
                            'valor' => 'a'
                        )
                    ),
                    'limit' => array(
                        'page' => '0',
                        'qtde' => '3'
                    )
                );

                $prof_subcateg = $this->superModel->select ('prof_subcateg', $data );
                $prof_subcateg = $prof_subcateg;

                $pedidos[$key]->Servico->categoria->profissionais = $prof_subcateg;



                $data = array (
                    'select' => 'subcategoria as descricao',
                    'condicoes' => array (
                        array (
                            'campo' => 'id_subcategoria',
                            'valor' => $categoria_servico->id_subcategoria
                        ) 
                    )
                );

                
                $subcategoria = $subcategoria[0];

                $pedidos[$key]->Servico->categoria->subcategoria = $subcategoria;



                $mix[$key_item] = $pedidos;
            }
        }


        return $mix;
    }

    private function getProfPergunta($id_prof_pergunta){
        $data = array (
            'select' => '*',
            'condicoes' => array (
                array (
                    'campo' => 'id_prof_pergunta',
                    'valor' => $id_prof_pergunta
                )
            )
        );

        return $this->superModel->select ('prof_pergunta', $data);
    }

    private function getProfPerguntaFiltros($id_categoria_servico, $id_servico){
        $data = array (
            'select' => 'id_prof_pergunta_filtro, id_prof_pergunta, ds_filtro, id_filtro, id_categoria_servico, id_servico',
            'condicoes' => array (
                array (
                    'campo' => 'id_categoria_servico',
                    'valor' => $id_categoria_servico
                ),
                array (
                    'campo' => 'id_servico',
                    'valor' => $id_servico
                )
            )
        );

        return $this->superModel->select ('prof_pergunta_filtro', $data);
    }

    private function getProfPerguntaResposta($id_prof_subcateg, $id_prof_pergunta){
        $data = array (
            'select' => '*',
            'condicoes' => array (
                array (
                    'campo' => 'id_prof_subcateg',
                    'valor' => $id_prof_subcateg
                ),
                array (
                    'campo' => 'id_prof_pergunta',
                    'valor' => $id_prof_pergunta
                )
            )
        );

        return $this->superModel->select ('prof_pergunta_resposta', $data);
    }

    private function getProfSubcateg($id_profissional){
        $data = array (
            'select' => 'id_prof_subcateg, id_subcategoria, id_profissional, status',
            'condicoes' => array (
                array (
                    'campo' => 'id_profissional',
                    'valor' => $id_profissional
                ),
                array (
                    'campo' => 'status',
                    'valor' => 'a'
                )
            ) 
        );

        return $this->superModel->select ('prof_subcateg', $data);
    }
*/

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
}