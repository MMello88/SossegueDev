<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Link extends MY_Front {

    private $id_manutencao = 1;
    private $CategoriaServico;
    private $Servicos;

    public function cadastros($tipo, $confirmacao) {
        

        $sql = "SELECT * FROM tbl_usuario_precadastro up"
                . " WHERE up.codigo_confirmacao = '" . $confirmacao . "'"
                . " AND up.expira_em >= current_timestamp() "
                . " AND up.confirmado_expiracao is null "
                . " ";

        $codigoConfirmacao = $this->superModel->query($sql);

        if (count($codigoConfirmacao) == 0) {
            $this->lang->load('mensagens');
            $this->session->set_flashdata('msg', $this->lang->line('token_invalido_expirado'));
            redirect('');
        } else {
            

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





            $this->session->set_userdata('codigoConfirmacao', $confirmacao);

            $idEmpresa = $codigoConfirmacao[0]->id_usuario_empresa;

            $condicoes = array(
                'condicoes' => array(
                    array(
                        'campo' => 'id_usuario',
                        'valor' => $idEmpresa
                    ),
                )
            );

            insertTag('js', 'jquery.maskedinput.min.js', $this->data);

            $this->data['cidades'] = $this->getCidades($cidade);

            $this->data['categoria'] = $this->Menus->getMenu($this->id_manutencao);
            $subcategorias = $this->Menus->getSubmenu($this->id_manutencao);

            foreach ($subcategorias as $subcategoria) {
                if (removeAcentos($subcategoria->link) == removeAcentos($funcao)) {
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
                }
            }


            $usuario = $this->superModel->select('usuario', $condicoes);

            $usuarioAprov = $this->superModel->select('aprovador', $condicoes);

            if ($usuario[0]->id_tipo_usuario == '6') {

                $sql = "SELECT *"
                        . " FROM tbl_setor a "
                        . " LEFT JOIN tbl_setor_empresa b ON (b.id_setor = a.id_setor) "
                        . " WHERE b.id_empresa=" . $usuarioAprov[0]->id_usuario_empresa
                        . " AND a.status <> 'Removido' ";

                $setores = $this->superModel->query($sql);
                $this->data['setores'] = $setores;

                if ($tipo == 'requerente') {
                    $this->data['idEmpresa'] = $idEmpresa;
                    $this->layout->view('cadastroUsuariosRequerente', $this->data);
                }
                if ($tipo == 'aprovador') {

                    $this->data['idEmpresa'] = $idEmpresa;
                    $this->layout->view('cadastroUsuariosAprovador', $this->data);
                }
            } else {

                $sql = "SELECT *"
                        . " FROM tbl_setor a "
                        . " LEFT JOIN tbl_setor_empresa b ON (b.id_setor = a.id_setor) "
                        . " WHERE b.id_empresa=" . $idEmpresa
                        . " AND a.status <> 'Removido' ";

                $setores = $this->superModel->query($sql);
                $this->data['setores'] = $setores;


                if ($tipo == 'requerente') {
                    $this->data['idEmpresa'] = $idEmpresa;
                    $this->layout->view('cadastroUsuariosRequerente', $this->data);
                }

                if ($tipo == 'aprovador') {
                    $this->data['idEmpresa'] = $idEmpresa;
                    $this->layout->view('cadastroUsuariosAprovador', $this->data);
                }
            }
        }
    }

    public function salvar($tipo, $idEmpresa) {

        if ($this->form_validation->run('restrita/link/salvar')) {

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

            if (!$this->superModel->select('usuario', $clausulas)) {
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
                    'telefone' => $this->input->post('celular'),
                    'status' => 'a',
                    'ultimo_acesso' => date('Y-m-d H:i:s'),
                    'codigo_confirmacao' => tokenGenerate(32),
                    'confirmado_expiracao' => date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . " + $diasConfirmacao days"))
                );

                $idUsuario = $this->superModel->insert('usuario', $usuario);

                $clausulasEmpresa = array(
                    'condicoes' => array(
                        array(
                            'campo' => 'id_usuario',
                            'valor' => $idEmpresa
                        )
                    )
                );

                $u = $this->superModel->select('usuario', $clausulasEmpresa);

                if ($u[0]->id_tipo_usuario == 5) {
                    $empresa = $idEmpresa;
                } else {
                    $sql = "SELECT a.id_usuario_empresa"
                            . " FROM tbl_aprovador a"
                            . " WHERE a.id_usuario=" . $u[0]->id_usuario;

                    $query = $this->superModel->query($sql);
                    $empresa = $query[0]->id_usuario_empresa;
                }

                $user = array(
                    'id_usuario' => $idUsuario,
                    'id_usuario_empresa' => $empresa,
                    'id_usuario_permissao' => $idEmpresa,
                    'setor' => $this->input->post('setor'),
                );

                if ($tipo == 'requerente') {
                    $idRequerente = $this->superModel->insert('requerente', $user);
                }

                if ($tipo == 'aprovador') {
                    $idAprovador = $this->superModel->insert('aprovador', $user);
                }

                // $usuario['link'] = base_url('restrita/cadastro/confirmaCadastro?token=' . $usuario['codigo_confirmacao'] . '&email=' . $usuario['email']);
                // $this->enviaEmail($usuario);

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
                $this->session->set_flashdata('msg', $this->lang->line('cadastro_inserido'));

                $data = array(
                    'confirmado_expiracao' => date("Y-m-d H:i:s")
                );

                $condicoes = array(
                    array(
                        'campo' => 'codigo_confirmacao',
                        'valor' => $this->session->userdata('codigoConfirmacao')
                    )
                );

                $this->superModel->update('usuario_precadastro', $data, $condicoes);

                $this->session->unset_userdata('codigoConfirmacao');


                $usuario['link'] = base_url('link/confirmaCadastro?token=' . $usuario['codigo_confirmacao'] . '&email=' . $usuario['email']);
                $this->enviaEmailConfirmacao($usuario);

                $this->layout->view('confirmar-cadastro', $this->data);
                //redirect('restrita/home');
            } else {
                $this->lang->load('mensagens');
                $this->session->set_flashdata('msg', $this->lang->line('usuario_ja_cadastrado'));
                redirect('login');
            }
        } else {
            echo __LINE__;
            $this->session->set_flashdata('msg', validation_errors());
            redirect('link/cadastros/' . $tipo . '/' . $this->session->userdata('codigoConfirmacao'));
        }
    }
    public function expirado() {
        if($this->session->userdata('email') && $this->session->userdata('link')) {
            $this->layout->view('cadastro-expirado', $this->data);
        } else {
            redirect('');
        }
    }
    private function enviaEmailConfirmacao($data) {
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
   
public function confirmaCadastro() {
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

   public function reenviaLink() {
        if($this->session->userdata('email') && $this->session->userdata('link')) {
            $data['email'] = $this->session->userdata('email');
            $data['link'] = $this->session->userdata('link');
            $this->session->unset_userdata('email');
            $this->session->unset_userdata('link');

            $this->enviaEmailConfirmacao($data);
            $this->session->set_flashdata('msg', 'Link de confirmação reenviado para seu email!');
        }

        redirect('');
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
