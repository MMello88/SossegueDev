<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Prestador extends MY_Front {



	public function __construct()
	{
		parent::__construct();


		// Carrego os models
		$this->load->model('prestadormodel');
		$this->load->model('superModel');
		$this->load->model('restritamodel');
	}



	public function index()
	{
		$data = array();

		$clausulas = array(
            'order' => array(
                array(
                	'campo' => 'ordem',
                    'valor' => 'ASC'
                )
            )
        );

        $this->data['estados'] = $this->superModel->select('estado');

        $clausulas = array(
            'condicoes' => array(
                array(
                    'campo' => 'status',
                    'valor' => 'a'
                )
            )
        );

        $this->data['planos'] = $this->superModel->select('planos', $clausulas);

        $clausulas['order'] = array(
            array(
                'campo' => 'categoria'
            )
        );

        $this->data['listaCategorias'] = $this->superModel->select('categoria', $clausulas);

        $clausulas['order'] = array(
            array(
                'campo' => 'ordem',
                'valor' => 'ASC'
            )
        );

        $this->data['planosOpcoes'] = $this->superModel->select('planos_opcoes', $clausulas);

        foreach($this->data['planos'] as $key => $plano) {
            foreach($this->data['planosOpcoes'] as $opcao) {
                $clausulas = array(
                    'row' => true,
                    'condicoes' => array(
                        array(
                            'campo' => 'id_planos',
                            'valor' => $plano->id_plano,
                        ),
                        array(
                            'campo' => 'id_planos_opcoes',
                            'valor' => $opcao->id_planos_opcoes,
                        )
                    )
                );

                $relacao = $this->superModel->select('planos_relacao', $clausulas);

                $this->data['planos'][$key]->opcoes[$opcao->id_planos_opcoes] = $relacao ? ($relacao->info ? $relacao->info : true) : false;
            }
        }

		if ( $this->input->post() ):

			// Valido o formulario
        	$this->_validarUsuario() == TRUE;



				// Gravo os dados do usuario
				$this->prestadormodel->addUsuario();
				$idUsuario = $this->db->insert_id();

				$usuario = $this->prestadormodel->getUsuario($idUsuario);

				$usuario['link'] = base_url('cadastro/confirmaCadastroProfissional?token=' . $usuario['codigo_confirmacao'] . '&email=' . $usuario['email']);
                $this->enviaEmail($usuario);
				
				echo '<pre>';
				print_r($usuario);
				echo '</pre>';
				$this->conclusao();


		endif;				
		
		insertTag('js', 'jquery.maskedinput.min.js', $this->data);
        $this->layout->view('cadastro_prestador', $this->data);

	}

    public function _validarUsuario()
    {

        // Carrego a classe de validacao
        $this->load->library('form_validation');

        // Defino as regras
        $this->form_validation->set_rules('nome',                'nome',               		  'trim|required');
        $this->form_validation->set_rules('email',               'e-mail',              	  'trim|valid_email|required');
        $this->form_validation->set_rules('telefone',     		 'telefone',           		  'trim|required');
        $this->form_validation->set_rules('celular',     		 'celular',           		  'trim|required');
        $this->form_validation->set_rules('senha',     		     'senha',           		  'trim|required');
        $this->form_validation->set_rules('cpf',     	     	 'cpf',             		  'trim|required');
        $this->form_validation->set_rules('nascimento',          'nascimento',          	  'trim');
        $this->form_validation->set_rules('id_categoria',        'categoria', 	              'trim|required');
        $this->form_validation->set_rules('id_subcategoria',     'subcategoria',              'trim|required');
        $this->form_validation->set_rules('id_estado',           'estado',                    'trim|required');
        $this->form_validation->set_rules('id_cidade',           'cidade',                    'trim|required');
        $this->form_validation->set_rules('id_planos',           'plano',                     'trim|required');
        return $this->form_validation->run();

    }

    public function conclusao() {

        $this->layout->view('confirmar-cadastro', $this->data);

    }

	public function enviaEmail($data) {
        $configEmail = $this->superModel->getRow('config_email');
        $this->load->library('email');

        $mensagem = '<!DOCTYPE html>
                <html lang="pt-br">
                <head>
                    <title>Sossegue - Confirmação de cadastro</title>
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
                                            <td bgcolor="#ffffff" width="100" align="left"><a href="'.base_url().'" target="_blank">
                                                <img alt="Logo Sossegue" src="'.base_url("assets/img/logo_mod-a.png").'" width="130" 
                                                    style="display: block;" border="0"></a>
                                            </td>
                                            <td bgcolor="#ffffff" width="400" align="right" class="mobile-hide">
                                                <table border="0" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td align="right" style="padding: 0 0 5px 0; font-size: 14px; font-family: Arial, 
                                                            sans-serif; color: #666666; text-decoration: none;"><span style="color: #666666; 
                                                            text-decoration: none;"><a href="'.base_url().'" target="_blank">SOSSEGUE</a>
                                                            <br>Sua vida mais tranquila</span>
                                                        </td>
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
                                                    <td align="center" style="padding: 20px 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding-copy">Clique no botão abaixo para confirmar e completar seu cadastro.</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="mobile-button-container">
                                                <tr>
                                                    <td align="center" style="padding: 25px 0 0 0;" class="padding-copy">
                                                        <table border="0" cellspacing="0" cellpadding="0" class="responsive-table">
                                                            <tr>
                                                                <td align="center"><a href="'.$data['link'].'" target="_blank" style="font-size: 16px; font-family: Helvetica, Arial, sans-serif; font-weight: normal; color: #ffffff; text-decoration: none; background-color: #5D9CEC; border-top: 15px solid #5D9CEC; border-bottom: 15px solid #5D9CEC; border-left: 25px solid #5D9CEC; border-right: 25px solid #5D9CEC; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; display: inline-block;" class="mobile-button">Confirmar cadastro &rarr;</a></td>
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
                </td>
            </tr>
        </table>
    </body>
    </html>';

        $emailEnviado = $this->email
            ->from($configEmail->username, 'Sossegue')
            ->to($data['email'])
            ->subject("Sossegue - Confirmação de cadastro")
            ->message($mensagem)
            ->send();

        if(!$emailEnviado) {
           $this->superModel->insert('log', array(
                'texto' => $this->email->print_debugger(),
                'pagina' => $this->uri->uri_string(),
                'post' => $this->input->post() ? json_encode($this->input->post()) : '',
                'data' => now(),
                'ip' => $this->input->ip_address()
            ));
        }
    }

    public function confirmaCadastroProfissional() {
        if($this->input->get('token') && $this->input->get('email')) {
            $data = array(
                'select' => 'id_usuario',
                'row' => true,
                'condicoes' => array(
                    array(
                        'campo' => 'confirmado',
                        'valor' => 'n'
                    ),
                    array(
                        'campo' => 'email',
                        'valor' => $this->input->get('email')
                    ),
                    array(
                        'campo' => 'codigo_confirmacao',
                        'valor' => $this->input->get('token')
                    )
                )
            );

            $usuario = $this->superModel->select('usuario', $data);

            if($usuario) {
                $data = array(
                    'confirmado' => 's'
                );

                $condicoes = array(
                    array(
                        'campo' => 'id_usuario',
                        'valor' => $usuario->id_usuario
                    )
                );

                $this->superModel->update('usuario', $data, $condicoes);

                $data = array(
                    'select' => 'id_profissional',
                    'row' => true,
                    'condicoes' => array(
                        array(
                            'campo' => 'id_usuario',
                            'valor' => $usuario->id_usuario
                        )
                    )
                );

                $profissional = $this->superModel->select('profissional', $data);

                if($profissional) {
                    $data = array(
                        'select' => 'id_profissional_fatura',
                        'row' => true,
                        'condicoes' => array(
                            array(
                                'campo' => 'id_profissional',
                                'valor' => $profissional->id_profissional
                            )
                        ),
                        'order' => array(
                            array(
                                'campo' => 'id_profissional_fatura',
                                'valor' => 'DESC'
                            )
                        )
                    );

                    $fatura = $this->superModel->select('profissional_fatura', $data);

                    if($fatura) {
                        $data = array(
                            'status' => 'a'
                        );

                        $condicoes = array(
                            array(
                                'campo' => 'id_profissional_fatura',
                                'valor' => $fatura->id_profissional_fatura
                            )
                        );

                        $this->superModel->update('profissional_fatura', $data, $condicoes);
                    }
                }

                $this->layout->view('confirmacao-cadastro', $this->data);
            } else {
                redirect('');
            }
        } else {
            redirect('');
        }
    }

    public function expirado() {
        if($this->session->userdata('email') && $this->session->userdata('link')) {
            $this->layout->view('cadastro-expirado', $this->data);
        } else {
            redirect('');
        }
    }

    public function reenviaLink() {
        if($this->session->userdata('email') && $this->session->userdata('link')) {
            $data['email'] = $this->session->userdata('email');
            $data['link'] = $this->session->userdata('link');
            $this->session->unset_userdata('email');
            $this->session->unset_userdata('link');

            $this->enviaEmail($data);
            $this->session->set_flashdata('msg', 'Link de confirmação reenviado para seu email!');
        }

        redirect('');
    }


}

/* End of file Cliente.php */
/* Location: ./application/controllers/chat/Cliente.php */