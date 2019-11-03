<?php
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );

class Manutencao extends MY_Front {
    
    private $id_manutencao = 1;
    private $CategoriaServico;
    private $Servicos;
    
    public function __construct() {
	   parent::__construct();
    }
    
    public function index($funcao = '',$cidade = '') {
		$this->data['cidade'] = $cidade;
        if($this->session->userdata('contato_cidade') == null){
            $this->session->set_userdata('contato_cidade', $cidade);
            $this->data['cidades'] = $this->getCidades($cidade);
        } else {
            if(!empty($cidade)){
                if($this->session->userdata('contato_cidade') <> $cidade){
                    $this->session->set_userdata('contato_cidade', $cidade);
                    $this->data['cidades'] = $this->getCidades($cidade);
                }
            }
        }
        
        //insertTag('js', 'jquery.maskedinput.min.js', $this->data);  
        //insertTag('js', 'cadastro.js', $this->data);
    //$this->session->unset_userdata('id_orcamento'); //usar para realizar teste
         $data = array(
            'select' => 'texto',
            'row' => TRUE,
            'condicoes' => array(
                array(
                    'campo' => 'url',
                    'valor' => 'termos-e-condicoes'
                ),
                array(
                    'campo' => 'status',
                    'valor' => 'a'
                )
            )
        );

        $this->data['termos'] = $this->superModel->select('pagina_estatica', $data);

        
        $this->session->set_userdata('inbound_lead', $funcao.'/'.$cidade);
        
        $this->session->set_userdata('url', $funcao);
        
        insertTag ( 'js', 'jquery.maskedinput.min.js', $this->data);
        insertTag ( 'js', 'custom.js', $this->data);
        
          
		
        $this->data['categoria'] = $this->Menus->getMenu($this->id_manutencao);

        $subcategorias = $this->Menus->getSubmenu($this->id_manutencao);

        $encontrou = false;
        foreach ($subcategorias as $subcategoria){
            if (removeAcentos($subcategoria->link) == removeAcentos($funcao)){
                $this->data['subcategoria'] = $subcategoria;
                
                $viewsubcategorias = $this->Menus->getViewSubmenu($subcategoria->id_subcategoria);

                $this->data['viewsubcategoria'] = $viewsubcategorias;
                $this->data['link_subcategoria'] = $funcao;
                
                $this->CategoriaServico = new CategoriaServico();
                $this->Servicos = new Servicos();
                
                $categoria_servicos = $this->CategoriaServico->getCategoriaServico($this->id_manutencao, $subcategoria->id_subcategoria);
                foreach ($categoria_servicos as $key => $item_cate_servico) {
                    $servicos = $this->Servicos->getServico($item_cate_servico->id_categoria_servico);
                    $categoria_servicos[$key]->Servicos = $servicos;
                }
                
                $this->data['categoria_servicos'] = $categoria_servicos;
                
                $clausulas = array(
                    'row' => true,
                    'condicoes' => array(
                        array(
                            'campo' => 'id_subcategoria',
                            'valor' => $subcategoria->id_subcategoria
                        )
                    )
                );
        
                $this->data['explicativo'] = $this->superModel->select('explicativos', $clausulas);
				
                if ($this->session->userdata('id_orcamento')){
                    
                    $this->data['id_orcamento'] = $this->session->userdata('id_orcamento');
                
                    $total_pedido = $this->superModel->query(
                        "SELECT COUNT(*) total_pedido "
                      . "  FROM tbl_pedido            "
                      . " WHERE status <> 'e'         "
                      . "   AND id_orcamento = " . $this->session->userdata('id_orcamento'));

                    $this->data['total_pedido'] = $total_pedido[0];
                    
                    $this->data['script_filtro'] = 1;
                    
                    $this->layout->view('continuacao-pedido', $this->data);
                } else
                    $this->layout->view ('manutencao', $this->data);

                $encontrou = true;
            }
        }

        if (!$encontrou){
            $this->layout->view ('manutencao', $this->data);
        }
    }
   


    public function Orcamento(){
        insertTag('js', 'jquery.maskedinput.min.js', $this->data);
        
        if($this->form_validation->run('pedidos/realizar')) {
          
            $orcamento = array(
                'nome'              => $this->input->post('nome'),
                'email'             => $this->input->post('email'),
                'celular'           => $this->input->post('celular'),
                'bairro'            => $this->input->post('bairro'),
                'id_cidade'         => $this->input->post('id_cidade'),
                'id_subcategoria'   => $this->input->post('subcategoria'),
                'descricao'         => $this->input->post('descricao'),
                'data_orcamento'    => date('Y-m-d H:i:s')
            );

            $idOrcamento = $this->superModel->insert('orcamento', $orcamento);
            $orcamento['id_orcamento'] = $idOrcamento;
 
            $this->session->set_userdata($orcamento);
            
            $this->enviaEmailPedido($orcamento);
            
            $this->inbound_mkting();
            $this->mailchimp();
			
            $cidade = $this->getCidadeById($this->input->post('id_cidade'));
            $this->session->set_userdata($cidade);
            $this->session->set_userdata('cidade_link', $cidade->link);
            redirect('Manutencao/'.$this->session->userdata('url').'/'. $cidade->link);
        } else {
            $clausulas = array(
                'order' => array(
                    array(
                        'campo' => 'ordem',
                        'valor' => 'ASC'
                    )
                )
            );

            $clausulas['order'] = array(
                array(
                    'campo' => 'categoria', 
                )
            );

            $this->data['listaCategorias'] = $this->superModel->select('categoria', $clausulas);

            $this->index($this->session->userdata('url'));
        }
    }

    public function actionPedido(){

        if($this->input->post('submit_type') == "finalizarPedido") {
            //update no pedido passando orçamento e finalizando todos os pedidos
            $data = array(
                'status' => 'f'
            );

            $condicao = array(
                array(
                    'campo' => 'id_orcamento',
                    'valor' => $this->session->userdata('id_orcamento')
                ),
                array (
                    'sinal' => '<>',
                    'campo' => 'status',
                    'valor' => 'e'
                )
            );

            $this->superModel->update('pedido', $data, $condicao);
            
            $this->layout->view('confirmacao-pedido', $this->data);
            
            $this->session->unset_userdata('url');
            $this->session->unset_userdata('id_orcamento');
            
        } else {
            if($this->form_validation->run('pedidos/servico')) {
                $pedido = array(
                    'id_orcamento' => $this->session->userdata('id_orcamento'),
                    'id_servico'   => $this->input->post('id_servico'),
                    'qntd'         => $this->input->post('qntd'),
                    'status'       => 'a'
                );

                $idPedido = $this->superModel->insert('pedido', $pedido);
                $pedido['id_pedido'] = $idPedido;
                
                
                $idFiltros = $this->input->post('id_filtros');

                if (!empty($idFiltros)){
                    foreach ($idFiltros as $key => $idFiltro) {
                        if (isset($idFiltro['id_filtro'])){
                            $pedido_filtro = array(
                                'id_pedido' => $idPedido,
                                'id_filtro' => $idFiltro['id_filtro'],
                                'valor' => '',
                            );
                        } else if (isset($idFiltro['value'])){
                            $pedido_filtro = array(
                                'id_pedido' => $idPedido,
                                'id_filtro' => $key,
                                'valor' => $idFiltro['value'],
                            );
                        }
                        $this->superModel->insert('pedido_filtro', $pedido_filtro);
                    }
                }
                $cidade = $this->getCidadeById($this->session->userdata('id_cidade'));
                
                $this->mostrarPopup();
                
                redirect('Manutencao/'.$this->session->userdata('url').'/'. $cidade->link);
            } else {
                $this->index($this->session->userdata('url'));
            }
        }
    }  
    
    public function getFiltrosOrcamento($idServico){
        $query = "SELECT a.id_pergunta, b.descricao AS pergunta, b.input_type "
               . " FROM tbl_filtro a "
               . " LEFT JOIN tbl_pergunta b ON (b.id_pergunta = a.id_pergunta) "
               . " WHERE a.id_servico = $idServico "
               . " AND a.status = 'a' "
               . " AND b.status = 'a' "
               . " GROUP BY 1, 2";

        $perguntas = $this->superModel->query($query);
        
        foreach($perguntas as $chave => $pergunta){
            $query = "SELECT a.id_filtro, a.descricao AS filtro "
                   . " FROM tbl_filtro a "
                   . " WHERE a.id_servico = $idServico "
                   . " AND a.id_pergunta = $pergunta->id_pergunta "
                   . " AND a.status = 'a'";
            $filtros = $this->superModel->query($query);
            
            $perguntas[$chave]->Filtros = $filtros;
        }
        $myJSON = json_encode($perguntas);
        echo $myJSON;
    }

//parte nova inserida fim

    private function enviaEmailPedido($data) {
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
                                                                    Contato: '.$this->session->userdata('nome').', '.$this->session->userdata('celular').', '.$this->session->userdata('email').', '.$this->session->userdata('bairro').'</td>
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
    }

    private function mailchimp(){
        session_start();
        $fname = $this->input->post('nome');
        $lname = '';
        $email = $this->input->post('email');
        if(!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL) === false){
            // MailChimp API credentials
            /*$apiKey = 'InserirMailChimpAPIKey';
            $listID = 'InserirMailChimpListID';*/
            $apiKey = 'a7862396fe2beb456157bdfbefe3e428-us16';
            $listID = 'ba702b4005';
            
            // MailChimp API URL
            $memberID = md5(strtolower($email));
            $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
            $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listID . '/members/' . $memberID;
            
            // informação do inscrito
            $json = json_encode(array(
                'email_address' => $email,
                'status'        => 'subscribed',
                'merge_fields'  => array(
                    'FNAME'     => $fname,
                    'LNAME'     => $lname
                )
            ));
            
            // enviar um HTTP POST request with curl
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            // mensagem de status baseado na resposta do código
            if ($httpCode == 200) {
                $_SESSION['msg'] = '<p style="color: #34A853">Você está inscrito no Sossegue.</p>';
            } else {
                switch ($httpCode) {
                    case 214:
                        $msg = 'Você já está inscrito.';
                        break;
                    default:
                        $msg = 'Ocorreu um problema. Tente novamente. ' . $httpCode;
                        break;
                }
                $_SESSION['msg'] = '<p style="color: #EA4335">'.$msg.'</p>';
            }
        }else{
            $_SESSION['msg'] = '<p style="color: #EA4335">Por favor, insira um e-mail válido.</p>';
        }
    }
    
    private function inbound_mkting(){
        $inbound_lead = "";
        $email = $this->input->post('email');
        $nome  = $this->input->post('nome');
        if ($this->session->userdata('inbound_lead') !== null)
            $inbound_lead = $this->session->userdata('inbound_lead');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://api.bulldesk.com.br/conversion");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "token=4176a42efb4d01013c9e74ad070e7885&identifier={$inbound_lead}&email={$email}&name={$nome}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec ($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close ($ch);
        $this->session->unset_userdata('inbound_lead');
    }

    private function mostrarPopup() {

        $this->session->set_flashdata('suss', "Seu Servico foi inserido com sucesso no Carrinho.");

    }

}