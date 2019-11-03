<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Usuario extends MY_Restrita {

    private $id_manutencao = 1;
    private $CategoriaServico;
    private $Servicos;

    public function index() {

        $this->data['script_filtro'] = 1;
        $this->data['script_listar_os'] = 1;

        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata['id']
                ),
            )
        );

        $usuario = $this->superModel->select('usuario', $condicoes);

        if ($usuario[0]->id_tipo_usuario == '5') {

            $sql = "SELECT *"
                    . " FROM tbl_usuario a "
                    . " LEFT JOIN tbl_requerente b ON (b.id_usuario = a.id_usuario) "
                    . " WHERE b.id_usuario_empresa=" . $usuario[0]->id_usuario
                    . " AND a.status <> 'r' ";

            $req = $this->superModel->query($sql);
            $this->data['usuariosReq'] = $req;

            $sql = "SELECT *"
                    . " FROM tbl_usuario a "
                    . " LEFT JOIN tbl_aprovador b ON (b.id_usuario = a.id_usuario) "
                    . " WHERE b.id_usuario_empresa=" . $usuario[0]->id_usuario
                    . " AND a.status <> 'r' ";

            $aprov = $this->superModel->query($sql);
            $this->data['usuariosAprov'] = $aprov;

            $this->data['usuarioSessao'] = $usuario;
        } else {
            $sql = "SELECT *"
                    . " FROM tbl_usuario a "
                    . " LEFT JOIN tbl_requerente b ON (b.id_usuario = a.id_usuario) "
                    . " WHERE b.id_usuario_empresa=" . $usuario[0]->id_usuario
                    . " AND a.status <> 'r' ";

            $req = $this->superModel->query($sql);
            $this->data['usuariosReq'] = $req;

            $sql = "SELECT *"
                    . " FROM tbl_usuario a "
                    . " LEFT JOIN tbl_aprovador b ON (b.id_usuario = a.id_usuario) "
                    . " WHERE b.id_usuario_permissao=" . $usuario[0]->id_usuario
                    . " AND a.status <> 'r' ";

            $aprov = $this->superModel->query($sql);
            $this->data['usuariosAprov'] = $aprov;

            $this->data['usuarioSessao'] = $usuario;
        }

        $this->layout->view('restrita/cadastroUsuarios', $this->data);
    }

    public function modal() {

        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata['id']
                ),
            )
        );

        $usuario = $this->superModel->select('usuario', $condicoes);


        $sql = "SELECT *"
                . " FROM tbl_usuario a "
                . " LEFT JOIN tbl_requerente b ON (b.id_usuario = a.id_usuario) "
                . " WHERE b.id_usuario_permissao=" . $usuario[0]->id_usuario
                . " AND a.status <> 'r' ";

        $req = $this->superModel->query($sql);
        $this->data['usuariosReq'] = $req;

        $sql = "SELECT *"
                . " FROM tbl_usuario a "
                . " LEFT JOIN tbl_aprovador b ON (b.id_usuario = a.id_usuario) "
                . " WHERE b.id_usuario_permissao=" . $usuario[0]->id_usuario
                . " AND a.status <> 'r' ";

        $aprov = $this->superModel->query($sql);
        $this->data['usuariosAprov'] = $aprov;

        $this->data['usuarioSessao'] = $usuario;

        $this->layout->view('restrita/cadastroUsuariosModal', $this->data);
    }

    public function adicionar() {
        $this->data['script_filtro'] = 1;
        $this->data['script_listar_os'] = 1;

        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata['id']
                ),
            )
        );

        $usuario = $this->superModel->select('usuario', $condicoes);

        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata['id']
                ),
            )
        );

        $usuario = $this->superModel->select('usuario', $condicoes);

        $sql = "SELECT *"
                . " FROM tbl_usuario a "
                . " LEFT JOIN tbl_requerente b ON (b.id_usuario = a.id_usuario) "
                . " WHERE b.id_usuario_permissao=" . $usuario[0]->id_usuario
                . " AND a.status <> 'r' ";

        $req = $this->superModel->query($sql);
        $this->data['usuariosReq'] = $req;

        $sql = "SELECT *"
                . " FROM tbl_usuario a "
                . " LEFT JOIN tbl_aprovador b ON (b.id_usuario = a.id_usuario) "
                . " WHERE b.id_usuario_permissao=" . $usuario[0]->id_usuario
                . " AND a.status <> 'r' ";

        $aprov = $this->superModel->query($sql);
        $this->data['usuariosAprov'] = $aprov;

        $this->data['usuarioSessao'] = $usuario;

        $this->layout->view('restrita/cadastroUsuariosModal2', $this->data);
    }

    public function remover() {

        $this->data['script_filtro'] = 1;
        $this->data['script_listar_os'] = 1;

        $data = array(
            'status' => 'r'
        );

        $condicoes = array(
            array(
                'campo' => 'id_usuario',
                'valor' => $this->input->post('id_usuario')
            )
        );

        $this->superModel->update('usuario', $data, $condicoes);

        //$this->superModel->query("DELETE FROM tbl_requerente WHERE id_usuario = ".$this->input->post("id_usuario"));
        // $this->superModel->query("DELETE FROM tbl_aprovador WHERE id_usuario = ".$this->input->post("id_usuario"));

        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata['id']
                ),
            )
        );

        $usuario = $this->superModel->select('usuario', $condicoes);

        $sql = "SELECT *"
                . " FROM tbl_usuario a "
                . " LEFT JOIN tbl_requerente b ON (b.id_usuario = a.id_usuario) "
                . " WHERE b.id_usuario_permissao=" . $usuario[0]->id_usuario
                . " AND a.status <> 'r' ";

        $req = $this->superModel->query($sql);
        $this->data['usuariosReq'] = $req;

        $sql = "SELECT *"
                . " FROM tbl_usuario a "
                . " LEFT JOIN tbl_aprovador b ON (b.id_usuario = a.id_usuario) "
                . " WHERE b.id_usuario_permissao=" . $usuario[0]->id_usuario
                . " AND a.status <> 'r' ";

        $aprov = $this->superModel->query($sql);
        $this->data['usuariosAprov'] = $aprov;

        $condicoes2 = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->input->post('id_usuario')
                ),
            )
        );

        $usuarioExcluido = $this->superModel->select('usuario', $condicoes2);

        $emailUsuarioExcluido = $usuarioExcluido[0]->email;

        $this->enviaEmailExclusao($usuario[0], $emailUsuarioExcluido);

        $this->data['usuarioSessao'] = $usuario;
        $this->layout->view('restrita/cadastroUsuarios', $this->data);
    }

    public function mensagem() {


        $this->data['script_filtro'] = 1;
        $this->data['script_listar_os'] = 1;

        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata['id']
                ),
            )
        );

        $usuarioAprov = $this->superModel->select('usuario', $condicoes);

        $emailUsuarioReq = $this->input->post('email');

        if ($this->input->post('tipo') == 4) {
            $tipo = 'requerente';
        }
        if ($this->input->post('tipo') == 6) {
            $tipo = 'aprovador';
        }

        $data = array(
            'row' => true,
            'select' => 'valor',
            'condicoes' => array(
                array(
                    'campo' => 'campo',
                    'valor' => 'dias_confirmacao_cadastro'
                )
            )
        );

        $diasConfirmacao = (int) $this->superModel->select('configuracoes', $data)->valor;

        $codigo_confirmacao = tokenGenerate(32);

        $usuario_precadastro = array(
            'email' => $emailUsuarioReq,
            'id_tipo_usuario' => $this->input->post('tipo'),
            'codigo_confirmacao' => $codigo_confirmacao,
            'id_usuario_empresa' => $this->session->userdata['id'],
            'expira_em' => date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . " + $diasConfirmacao days"))
        );

        $this->superModel->insert('usuario_precadastro', $usuario_precadastro);

        $link = base_url('link/cadastros/' . $tipo . '/' . $codigo_confirmacao);

        $bol = $this->enviaEmail($usuarioAprov[0], $emailUsuarioReq, $link);

        if ($bol) {
            $this->session->set_userdata('mensagem', 'O Link foi Enviado para o Email!');
        } else {
            $this->session->set_userdata('mensagem', 'O Link não Pode ser Enviado para o Email!');
        }
        
        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata['id']
                ),
            )
        );

        $usuario = $this->superModel->select('usuario', $condicoes);

        $sql = "SELECT * "
                . " FROM tbl_usuario a "
                . " LEFT JOIN tbl_requerente b ON (b.id_usuario = a.id_usuario) "
                . " WHERE b.id_usuario_permissao=" . $usuario[0]->id_usuario
                . " AND a.status <> 'r' ";

        $req = $this->superModel->query($sql);
        $this->data['usuariosReq'] = $req;

        $sql = "SELECT * "
                . " FROM tbl_usuario a "
                . " LEFT JOIN tbl_aprovador b ON (b.id_usuario = a.id_usuario) "
                . " WHERE b.id_usuario_permissao=" . $usuario[0]->id_usuario
                . " AND a.status <> 'r' ";

        $aprov = $this->superModel->query($sql);
        $this->data['usuariosAprov'] = $aprov;

        $this->session->unset_userdata('excluindo');
        $this->data['usuarioSessao'] = $usuario;
        $this->layout->view('restrita/cadastroUsuarios', $this->data);
    }

    public function enviaEmailExclusao($usuario, $emailUsuarioExcluido) {

        $configEmail = $this->superModel->getRow('config_email');
        $this->load->library('email');

        $mensagem = '<!DOCTYPE html>
                <html lang="pt-br">
                <head>
                    <title>Sossegue - Aviso de Exclusão</title>
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
                                            <td bgcolor="#ffffff" width="100" align="left"><a href="' . base_url() . '" target="_blank">
                                                <img alt="Logo Sossegue" src="' . base_url("assets/img/logo_mod-a.png") . '" width="130"
                                                    style="display: block;" border="0"></a>
                                            </td>
                                            <td bgcolor="#ffffff" width="400" align="right" class="mobile-hide">
                                                <table border="0" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td align="right" style="padding: 0 0 5px 0; font-size: 14px; font-family: Arial,
                                                            sans-serif; color: #666666; text-decoration: none;"><span style="color: #666666;
                                                            text-decoration: none;"><a href="' . base_url() . '" target="_blank">SOSSEGUE</a>
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
                                                    <td align="center" style="padding: 20px 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding-copy"></td>
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
                                                                <td align="center">O Usuário "' . $usuario->nome . '"excluiu a sua conta.</td>
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
                ->to($emailUsuarioExcluido)
                ->subject("Sossegue - Aviso de Exclusão de Conta")
                ->message($mensagem)
                ->send();
    }

    /* public function salvar($tipo,$idEmpresa) {

      if(TRUE){ //$this->form_validation->run('cadastro/cadastrar')

      $clausulas = array(
      'select' => 'id_usuario',
      'row' => true,
      'condicoes' => array(
      array(
      'campo' => 'cpf',
      'valor' => $this->input->post('cpf')
      ),
      array(
      'campo' => 'email',
      'valor' => $this->input->post('email'),
      'or' => TRUE
      )
      )
      );

      if(!$this->superModel->select('usuario', $clausulas)){


      $clausulas = array(
      'select' => 'id_tipo_usuario',
      'row' => true,
      'condicoes' => array(
      array(
      'campo' => 'status',
      'valor' => 'a'
      ),
      array(
      'campo' => 'tipo_usuario',
      'valor' => $tipo
      )
      )
      );
      $data = array(
      'row' => true,
      'select' => 'valor',
      'condicoes' => array(
      array(
      'campo' => 'campo',
      'valor' => 'dias_confirmacao_cadastro'
      )
      )
      );

      $diasConfirmacao = (int) $this->superModel->select('configuracoes', $data)->valor;

      $usuario = array(
      'id_tipo_usuario' => $this->superModel->select('tipo_usuario', $clausulas)->id_tipo_usuario,
      'nome' => $this->input->post('nome'),
      'email' => $this->input->post('email'),
      'senha' => Bcrypt::hash($this->input->post('senha')),
      'cpf' => $this->input->post('cpf'),
      'telefone' => $this->input->post('telefone'),
      'status' => 'a',
      'codigo_confirmacao' => tokenGenerate(32),
      'confirmado_expiracao' => date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . " + $diasConfirmacao days"))
      );

      $idUsuario = $this->superModel->insert('usuario', $usuario);

      $user = array(

      'id_usuario' => $idUsuario,
      'id_usuario_empresa' => $idEmpresa,
      'setor' => $this->input->post('setor')

      );
      if ($tipo =='requerente'){

      $idRequerente = $this->superModel->insert('requerente', $user);

      }

      else if($tipo == 'aprovador') {

      $idAprovador = $this->superModel->insert('aprovador', $user);
      }



      $cidade = array(
      'id_usuario'=> $idUsuario,
      'id_cidade' => $this->input->post('id_cidade')
      );

      $idUsuarioEndereco = $this->superModel->insert('usuario_endereco', $cidade);

      $this->superModel->salvaLog('Login no sistema', array('email' => $usuario['email']));
      $this->db->where('id_usuario', $idUsuario);
      $this->db->update('usuario', array('ultimo_acesso' => date('Y-m-d H:i:s')));

      $data = array(
      'logado' => TRUE,
      'nome' => $usuario['nome'],
      'email' => $usuario['email'],
      'id' => $idUsuario,
      'tipo' => $usuario['id_tipo_usuario']
      );

      $this->session->set_userdata($data);
      $this->layout->view('', $this->data);


      } else {
      $this->lang->load('mensagens');
      $this->session->set_flashdata('msg', $this->lang->line('usuario_ja_cadastrado'));
      redirect('restrita/cadastro/link/'.$tipo);//errado
      }

      } else {
      redirect('restrita/cadastro/link/'.$tipo);// errado
      }




      }
     */

    public function confirmaCadastro() {
        if ($this->input->get('token') && $this->input->get('email')) {
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

            if ($usuario) {
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

                $this->layout->view('restrita/confirmacao-cadastro', $this->data);
            } else {
                redirect('');
            }
        } else {
            redirect('');
        }
    }

    public function expirado() {
        if ($this->session->userdata('email') && $this->session->userdata('link')) {
            $this->layout->view('cadastro-expirado', $this->data);
        } else {
            redirect('');
        }
    }

    public function reenviaLink() { //ajeitar

        if ($this->session->userdata('email') && $this->session->userdata('link')) {
            $data['email'] = $this->session->userdata('email');
            $data['link'] = $this->session->userdata('link');
            $this->session->unset_userdata('email');
            $this->session->unset_userdata('link');

            $bol = $this->enviaEmail($data);

            if ($bol) {
                $this->session->set_flashdata('msg', 'Link de cadastro enviado!');
            } else {
                $this->session->set_flashdata('msg', 'Link de cadastro nao pode ser enviado!');
            }
        }

        redirect('');
    }

    public function enviaEmail($usuarioAprov, $emailUsuarioReq, $link) {

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
                                            <td bgcolor="#ffffff" width="100" align="left"><a href="' . base_url() . '" target="_blank">
                                                <img alt="Logo Sossegue" src="' . base_url("assets/img/logo_mod-a.png") . '" width="130"
                                                    style="display: block;" border="0"></a>
                                            </td>
                                            <td bgcolor="#ffffff" width="400" align="right" class="mobile-hide">
                                                <table border="0" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td align="right" style="padding: 0 0 5px 0; font-size: 14px; font-family: Arial,
                                                            sans-serif; color: #666666; text-decoration: none;"><span style="color: #666666;
                                                            text-decoration: none;"><a href="' . base_url() . '" target="_blank">SOSSEGUE</a>
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
                                                    <td align="center" style="padding: 20px 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding-copy">'.$usuarioAprov->nome.' aguarda seu cadastro para que você possa fazer pedidos de manuntenção pelo sistema da Sossegue.</td>
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
                                                                <td align="center"><a href="' . $link . '" target="_blank" style="font-size: 16px; font-family: Helvetica, Arial, sans-serif; font-weight: normal; color: #ffffff; text-decoration: none; background-color: #5D9CEC; border-top: 15px solid #5D9CEC; border-bottom: 15px solid #5D9CEC; border-left: 25px solid #5D9CEC; border-right: 25px solid #5D9CEC; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; display: inline-block;" class="mobile-button">Cadastre-se&rarr;</a></td>
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
                ->from($usuarioAprov->email, $usuarioAprov->nome)
                ->to($emailUsuarioReq)
                ->subject("Sossegue - Link de Cadastro")
                ->message($mensagem)
                ->send();

        if (!$emailEnviado) {
            /* $this->superModel->insert('log', array(
              'texto' => $this->email->print_debugger(),
              'pagina' => $this->uri->uri_string(),
              'post' => $this->input->post() ? json_encode($this->input->post()) : '',
              'data' => now(),
              'ip' => $this->input->ip_address()
              )); */
            return FALSE;
        } else {

            return TRUE;
        }
    }

    public function getCidades($cidade) {

        if (empty($cidade)) {
            return $this->getAllCidades();
        } else {
            $data = array(
                'select' => '*',
                'condicoes' => array(
                    array(
                        'campo' => 'status',
                        'valor' => 'a'
                    ),
                    array(
                        'campo' => 'link',
                        'valor' => $cidade
                    )
                )
            );
            $cidades = $this->superModel->select('cidade', $data);
            if (!empty($cidades)) {
                $this->data['cidade'] = $cidade;
                return $cidades;
            } else
                return $this->getAllCidades();
        }
    }

    public function getAllCidades() {
        $data = array(
            'select' => '*',
            'condicoes' => array(
                array(
                    'campo' => 'status',
                    'valor' => 'a'
                )
            )
        );

        return $this->superModel->select('cidade', $data);
    }

    public function getCidadeById($id) {
        $data = array(
            'select' => '*',
            'condicoes' => array(
                array(
                    'campo' => 'status',
                    'valor' => 'a'
                ),
                array(
                    'campo' => 'id',
                    'valor' => $id
                )
            )
        );

        $cidades = $this->superModel->select('cidade', $data);
        if (!empty($cidades))
            return $cidades[0];
        return null;
    }

}
