<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends MY_Front {

	public function index() {
		$this->layout->view('login', $this->data);
	}

    public function confirmaSenha() {
        if(!$this->input->get('code') || !$this->input->get('token')) {
            redirect('login');
        }

        $data = array(
            'select' => 'id, email',
            'row' => TRUE,
            'condicoes' => array(
                array(
                    'campo' => 'confirmado',
                    'valor' => 'n'
                ),
                array(
                    'campo' => 'code',
                    'valor' => $this->input->get('code')
                )
            )
        );

        $consulta = $this->superModel->select('recupera_senha', $data);

        if($consulta) {
            if($this->input->get('token') === md5($consulta->email)) {
                $data = array(
                    'confirmado' => 's'
                );

                $condicao = array(
                    array(
                        'campo' => 'id',
                        'valor' => $consulta->id
                    )
                );

                $this->superModel->update('recupera_senha', $data, $condicao);

                $usuario = array(
                    'email' => $consulta->email,
                    'code' => $this->input->get('code')
                );

                $this->session->set_userdata($usuario);
                redirect('login/nova');
            } else {
                redirect('login');
            }
        } else {
            redirect('login');
        }
    }

    public function redefinirSenha() {
        if($this->form_validation->run('login/redefinirSenha') && $this->session->userdata('email') && $this->session->userdata('code')) {
            $data = array(
                'row' => true,
                'condicoes' => array(
                    array(
                        'campo' => 'email',
                        'valor' => $this->session->userdata('email')
                    ),
                    array(
                        'campo' => 'code',
                        'valor' => $this->session->userdata('code')
                    )
                )
            );

            $consulta = $this->superModel->select('recupera_senha', $data);

            if($consulta && $this->input->post('email') === $this->session->userdata('email')) {
                $this->session->unset_userdata('code');
                $this->session->unset_userdata('email');

                $data = array(
                    'senha' => Bcrypt::hash($this->input->post('senha'))
                );

                $condicao = array(
                    array(
                        'campo' => 'email',
                        'valor' => $this->input->post('email')
                    )
                );

                $this->superModel->update('usuario', $data, $condicao);

                $email = $this->input->post('email');

                $this->db->where('email', $email);
                $usuario = $this->db->get('usuario')->row();

                $this->superModel->salvaLog('Login no sistema', array('email' => $usuario->email));

                $this->db->where('id_usuario', $usuario->id_usuario);
                $this->db->update('usuario', array('ultimo_acesso' => date('Y-m-d H:i:s')));

                $this->db->where('id_tipo_usuario', $usuario->id_tipo_usuario);
                $tipo = $this->db->get('tipo_usuario')->row()->tipo_usuario;

                $data = array(
                    'logado' => TRUE,
                    'nome' => $usuario->nome,
                    'email' => $usuario->email,
                    'id' => $usuario->id_usuario,
                    'tipo' => $tipo
                );

                $this->db->select('cidade.nome AS cidade');
                $this->db->join('cidade', 'cidade.id = usuario_endereco.id_cidade');
                $this->db->where('id_usuario', $usuario->id_usuario);
                $endereco = $this->db->get('usuario_endereco')->row();

                if($endereco) {
                    $data['cidade'] = $endereco->cidade;
                }

                $this->session->set_userdata($data);

                $this->session->set_flashdata('msg', 'Senha atualizada com sucesso!');
                redirect('restrita/home');
            } else {
                $this->session->set_flashdata('msg', 'Email inválido!');
                redirect('login/nova');
            }
        } else {
            redirect('login');
        }
    }

    public function recuperarSenha() {
        if($this->form_validation->run('recuperarSenha')) {
            $this->lang->load('mensagens');
            $this->session->set_flashdata('msg', $this->lang->line('redefinicao_senha'));
            $data['email'] = $this->input->post('email');

            if(!$this->emailCadastrado($data['email'])) {
                redirect('login');
            }

            $token = md5($data['email']);
            $data['code'] = $this->getCode();
            $data['expiracao'] = date('Y-m-d H:i:s', strtotime(now() . " + 3 days"));

            $this->superModel->insert('recupera_senha', $data);

            $data['link'] = base_url('login/confirmaSenha?token=' . $token . '&code=' . $data['code']);
            
            //$this->enviaEmail($data);
        }

        redirect('login');
    }

    public function nova() {
        if($this->session->userdata('email') && $this->session->userdata('code')) {
            $this->layout->view('loginNova', $this->data);
        } else {
            redirect('login');
        }
    }

    private function enviaEmail($data) {
        $configEmail = $this->superModel->getRow('config_email');
        $this->load->library('email');

        $mensagem = '<!DOCTYPE html>
                <html lang="pt-br">
                <head>
                    <title>Sossegue - Recuperação de senha</title>
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
                    </table>
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
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
                                                                <td align="center" style="font-size: 25px; font-family: Helvetica, Arial, sans-serif; color: #333333; padding-top: 30px;" class="padding-copy">Você esqueceu sua senha?</td>
                                                            </tr>
                                                            <tr>
                                                                <td align="center" style="padding: 20px 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding-copy">Recebemos uma solicitação de recuperação de senha, clique no botão abaixo para registrar uma nova.</td>
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
                                                                            <td align="center"><a href="'.$data['link'].'" target="_blank" style="font-size: 16px; font-family: Helvetica, Arial, sans-serif; font-weight: normal; color: #ffffff; text-decoration: none; background-color: #5D9CEC; border-top: 15px solid #5D9CEC; border-bottom: 15px solid #5D9CEC; border-left: 25px solid #5D9CEC; border-right: 25px solid #5D9CEC; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; display: inline-block;" class="mobile-button">Recuperar senha &rarr;</a></td>
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
            ->subject("Sossegue - Redefinição de senha")
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

    private function emailCadastrado($email) {
        $code = tokenGenerate(32);

        $data = array(
            'row' => TRUE,
            'condicoes' => array(
                array(
                    'campo' => 'email',
                    'valor' => $email
                )
            )
        );

        return $this->superModel->select('usuario', $data);
    }

    private function getCode() {
        $code = tokenGenerate(32);

        $data = array(
            'row' => TRUE,
            'condicoes' => array(
                array(
                    'campo' => 'code',
                    'valor' => $code
                )
            )
        );

        $consulta = $this->superModel->select('recupera_senha', $data);

        if($consulta) {
            return getCode();
        } else {
            return $code;
        }
    }
}
