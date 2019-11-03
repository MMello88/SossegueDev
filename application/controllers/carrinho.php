<?php
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
class Carrinho extends MY_Front {
    
    private $id_orcamento;
    private $url;
    
    public function __construct() {
	parent::__construct();		//$this->output->enable_profiler(TRUE);		
        $this->id_orcamento = $this->session->userdata('id_orcamento');
        $this->url = $this->session->userdata('url');
                
    }
    
    public function index() {
        //$this->teste_ab();
        $this->data ['id_orcamento'] = $this->id_orcamento;
        if (!empty($this->id_orcamento)){
            $this->data ['url'] = $this->url;
            
            $this->data ['pedidos'] = $this->getPedidos();
            
            $this->data['script_increment'] = 1;
            
            $this->layout->view('carrinho',$this->data);
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
    
    public function Finalizar($idOrcamento){
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

        $this->enviaEmailPedido($this->orcamento);
        
        $this->layout->view('confirmacao-pedido', $this->data);

        $this->session->unset_userdata('url');
        $this->session->unset_userdata('id_orcamento');
        
        /*$this->session->unset_userdata('teste_ab_alterado');
        $this->session->unset_userdata('id_teste_ab'); 
        $this->session->unset_userdata('btn_carrinho');*/
    }

    private function getPedidos(){
        //$mix = array();
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

        //$mix[0] = $pedidos;

        return $pedidos;

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
    
    private function teste_ab(){
        if ($this->session->userdata('teste_ab_alterado') == ""){
            
            $data = array(
                'tipo_a'     => $this->session->userdata('btn_carrinho') == "Branco" ? 1 : 0,
                'tipo_b'     => $this->session->userdata('btn_carrinho') == "Verde" ? 1 : 0,
                'data_click' => date('Y-m-d H:i:s')
            );

            $condicao = array(
                array(
                    'campo' => 'id_teste_ab',
                    'valor' => $this->session->userdata('id_teste_ab')
                )
            );

            $this->superModel->update('teste_ab', $data, $condicao);
            
            $this->session->set_userdata('teste_ab_alterado','True');
        }
    }
    
}
