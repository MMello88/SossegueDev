<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Busca extends MY_Front {
    var $qtde = 30;

    public function profissionais() {

        $profissao = $this->input->get('profissao');
        $cidade = $this->input->get('cidade');

        if(!$cidade && $this->session->userdata('cidade')) {
            redirect(current_full_url() . '&cidade=' . $this->session->userdata('cidade'));
        }

        if($profissao || $cidade) {
            $this->data['gratuitos'] = array();
            $this->data['pagos'] = array();

            $clausulas = array(
                'select' => 'url, usuario.nome, email, telefone, celular, plano, preco, cidade.nome AS cidade, subcategoria.subcategoria AS profissao, descricao, foto, profissional.id_profissional, profissional.id_subcategoria, usuario.id_usuario AS idUsuario',
                'order' => array(
                    array(
                        'campo' => 'preco',
                        'valor' => 'desc'
                    ),
                    array(
                        'campo' => 'avg(nota)',
                        'valor' => 'desc'
                    ),
                    array(
                        'campo' => 'nome',
                        'valor' => 'random'
                    )
                ),
                'group' => array(
                    'usuario.id_usuario'
                ),
                'join' => array(
                    array(
                        'tabela' => 'usuario',
                        'on' => 'profissional.id_usuario = usuario.id_usuario'
                    ),
                    array(
                        'tabela' => 'usuario_endereco',
                        'on' => 'usuario_endereco.id_usuario = usuario.id_usuario'
                    ),
                    array(
                        'tabela' => 'cidade',
                        'on' => 'usuario_endereco.id_cidade = cidade.id'
                    ),
                    array(
                        'tabela' => 'subcategoria',
                        'on' => 'profissional.id_subcategoria = subcategoria.id_subcategoria'
                    ),
                    array(
                        'tabela' => 'profissional_fatura',
                        'on' => 'profissional_fatura.id_profissional = profissional.id_profissional'
                    ),
                    array(
                        'tabela' => 'nota',
                        'on' => 'profissional.id_profissional = nota.id_profissional',
                        'option' => 'left'
                    ),
                    array(
                        'tabela' => 'planos',
                        'on' => 'planos.id_plano = profissional_fatura.id_planos'
                    )
                ),
                'condicoes' => array(
                    array(
                        'campo' => 'profissional.status',
                        'valor' => 'a'
                    ),
                    array(
                        'campo' => 'profissional_fatura.situacao',
                        'valor' => 'a'
                    ),
                    array(
                        'campo' => 'profissional_fatura.status',
                        'valor' => 'a'
                    ),
                    array(
                        'campo' => 'NOW() BETWEEN inicio AND fim',
                        'valor' => NULL,
                        'sinal' => false
                    )
                ),
                'limit' => array(
                    'page' => $this->input->get('pagina'),
                    'qtde' => $this->qtde
                )
            );

            if($profissao) {
                $clausulas['condicoes'][] = array(
                    'campo' => 'subcategoria',
                    'like' => 'after',
                    'valor' => $profissao
                );
            }

            if($cidade) {
                $clausulas['condicoes'][] = array(
                    'campo' => 'cidade.nome',
                    'like' => 'after',
                    'valor' => $cidade
                );
            }

            $profissionais = $this->superModel->select('profissional', $clausulas);

            if(!$profissionais || !$cidade) {
                $clausulas = array(
                    'condicoes' => array(
                        array(
                            'campo' => 'id IN (SELECT id_cidade FROM tbl_usuario_endereco JOIN tbl_usuario ON  tbl_usuario.id_usuario = tbl_usuario_endereco.id_usuario WHERE tbl_usuario.status = "a")',
                            'sinal' => '',
                            'valor' => NULL
                        )
                    )
                );

                $this->data['listaCidades'] = $this->superModel->select('cidade', $clausulas);
            }

            foreach($profissionais as $key => $profissional) {
                $data = array(
                    'select' => 'id',
                    'row' => true,
                    'condicoes' => array(
                        array(
                            'campo' => 'id_usuario',
                            'valor' => $this->session->userdata('id')
                        ),
                        array(
                            'campo' => 'id_profissional',
                            'valor' => $profissional->id_profissional
                        ),
                        array(
                            'campo' => 'tipo',
                            'valor' => 'b'
                        ),
                        array(
                            'campo' => 'DATE_FORMAT(data, "%d/%m/%Y")',
                            'valor' => "'" . date('d/m/Y') . "'",
                            'escape' => true
                        )
                    )
                );

                $clausulas = array(
                    'row' => true,
                    'select' => 'round(floor(avg(nota) * 2) / 2, 1) AS nota',
                    'condicoes' => array(
                        array(
                            'campo' => 'nota.status',
                            'valor' => 'a'
                        ),
                        array(
                            'campo' => 'id_profissional',
                            'valor' => $profissional->id_profissional
                        )
                    )
                );

                $profissionais[$key]->nota = $this->superModel->select('nota', $clausulas)->nota;

                if($profissionais[$key]->nota) {
                    $condicoes = array(
                        'condicoes' => array(
                            array(
                                'campo' => 'status',
                                'valor' => 'a'
                            ),
                            array(
                                'campo' => 'id_profissional',
                                'valor' => $profissionais[$key]->id_profissional
                            )
                        )
                    );

                    $profissionais[$key]->totalAvaliacoes = $this->superModel->count('nota', $condicoes);
                }

                if($profissional->plano == 'Grátis') {
                    $this->data['gratuitos'][] = $profissional;
                } else {
                    $this->data['pagos'][] = $profissional;
                }
            }

            if($cidade) {
                $this->data['cidade'] = $cidade;
            }

            if($profissao) {
                $this->data['profissao'] = $profissao;
            }

            if(!$profissao) {
                $this->data['listaProfissoes'] = $this->superModel->select('subcategoria', array(
                    'condicoes' => array(
                        array(
                            'campo' => 'categoria.status',
                            'valor' => 'a'
                        ),
                        array(
                            'campo' => 'subcategoria.status',
                            'valor' => 'a'
                        )
                    ),
                    'join' => array(
                        array(
                            'tabela' => 'categoria',
                            'on' => 'categoria.id_categoria = subcategoria.id_categoria'
                        )
                    )
                ));
            }

            $data = array(
                'condicoes' => array(
                    array(
                        'campo' => 'profissional_fatura.status',
                        'valor' => 'a'
                    ),
                    array(
                        'campo' => 'situacao',
                        'valor' => 'a'
                    )
                ),
                'join' => array(
                    array(
                        'tabela' => 'profissional',
                        'on' => 'profissional_fatura.id_profissional = profissional.id_profissional'
                    )
                )
            );

            if($profissionais) {
                $data['condicoes'][2] = array(
                    'campo' => 'profissional.id_subcategoria',
                    'valor' => $profissionais[0]->id_subcategoria
                );
            }

            $total = $this->superModel->count('profissional_fatura', $data);

            $config['base_url'] = base_url($this->uri->uri_string() . '?profissao=' . $this->input->get('profissao') . '&cidade=' . $this->input->get('cidade'));
            $config['query_string_segment'] = 'pagina';
            $config['full_tag_open'] = '<ul class="pagination"><li></li>';
            $config['full_tag_close'] = '<li></li></ul>';
            $config['prev_tag_open'] = '<li>';
            $config['prev_link'] = '«';
            $config['prev_tag_close'] = '</li>';
            $config['next_tag_open'] = '<li>';
            $config['next_link'] = '»';
            $config['next_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="active"><a href="#">';
            $config['cur_tag_close'] = '</a></li>';
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';
            $config['first_link'] = FALSE;
            $config['last_link'] = FALSE;
            $config['page_query_string'] = TRUE;
            $config['total_rows'] = $total;
            $config['per_page'] = $this->qtde;

            $this->pagination->initialize($config);

            $this->layout->view('busca', $this->data);

            $emails = array();
            $telefone = '';

            $data = array(
                'select' => 'nome, email, telefone, celular',
                'row' => true,
                'condicoes' => array(
                    array(
                        'campo' => 'id_usuario',
                        'valor' => $this->session->userdata('id')
                    )
                )
            );

            if($this->session->userdata('tipo') === 'Comum') {
                $usuario = $this->superModel->select('usuario', $data);

                if($usuario->telefone && $usuario->celular) {
                    $telefone = $usuario->telefone . ' / ' . $usuario->celular;
                } else if($usuario->telefone || $usuario->celular) {
                    $telefone = $usuario->telefone ? $usuario->telefone : $usuario->celular;
                }

                foreach($this->data['pagos'] as $key => $profissional) {
                    if($profissional->idUsuario !== $this->session->userdata('id')) {
                        if($key < 5) {
                            $emails[] = array(
                                'emailPara' => $profissional->email,
                                'profissional' => $profissional->nome,
                                'usuario' => $usuario->nome,
                                'emailUsuario' => $usuario->email,
                                'telefoneUsuario' => $telefone
                            );
                        } else {
                            break;
                        }
                    }
                }

                if(count($emails) < 5) {
                    $break = 5 - count($emails);
                    foreach($this->data['gratuitos'] as $key => $profissional) {
                        if($profissional->idUsuario !== $this->session->userdata('id')) {
                            if($key < $break) {
                                $emails[] = array(
                                    'emailPara' => $profissional->email,
                                    'profissional' => $profissional->nome,
                                    'usuario' => $usuario->nome,
                                    'emailUsuario' => $usuario->email,
                                    'telefoneUsuario' => $telefone
                                );
                            } else {
                                break;
                            }
                        }
                    }
                }

                foreach($emails as $email) {
                    $data = array(
                        'row' => true,
                        'select' => 'id',
                        'condicoes' => array(
                            array(
                                'campo' => 'email',
                                'valor' => $email['emailPara']
                            ),
                            array(
                                'campo' => 'DATE_FORMAT(data, "%d/%m/%Y")',
                                'valor' => "'" . date('d/m/Y') . "'",
                                'escape' => true
                            )
                        )
                    );

                    $consulta = $this->superModel->select('email_busca', $data);

                    if(!$consulta) {
                        $this->enviaEmail($email);
                    }
                }
            }

        } else {
            redirect('');
        }
    }

    public function getSubcategorias() {
        if(!$this->input->is_ajax_request() || !$this->input->get('categoria')) {
            redirect('');
        }

        $clausulas = array(
            'select' => 'id_subcategoria AS id, subcategoria AS nome',
            'condicoes' => array(
                array(
                    'campo' => 'status',
                    'valor' => 'a'
                ),
                array(
                    'campo' => 'id_categoria',
                    'valor' => $this->input->get('categoria')
                )
            ),
            'order' => array(
                array(
                    'campo' => 'nome'
                )
            )
        );

        $subcategorias = $this->superModel->select('subcategoria', $clausulas);
        print json_encode($subcategorias);
    }

    public function getProfissoes() {
        if(!$this->input->is_ajax_request()) {
            redirect('');
        }

        $profissoes = $this->superModel->select('subcategoria', array(
            'condicoes' => array(
                array(
                    'campo' => 'categoria.status',
                    'valor' => 'a'
                ),
                array(
                    'campo' => 'subcategoria.status',
                    'valor' => 'a'
                )
            ),
            'join' => array(
                array(
                    'tabela' => 'categoria',
                    'on' => 'categoria.id_categoria = subcategoria.id_categoria'
                )
            )
        ));

        $resultado = array();

        foreach($profissoes as $profissao) {
            $resultado[] = $profissao->subcategoria;
        }

        print json_encode($resultado);
    }

    public function getCidades() {
        if(!$this->input->is_ajax_request()) {
            redirect('');
        }

        if($this->input->get('estado')) {
            $clausulas = array(
                'condicoes' => array(
                    array(
                        'campo' => 'estado',
                        'valor' => $this->input->get('estado')
                    )
                )
            );
        } else {
            $clausulas = array(
                'condicoes' => array(
                    array(
                        'campo' => 'id IN (SELECT id_cidade FROM tbl_usuario_endereco JOIN tbl_usuario ON  tbl_usuario.id_usuario = tbl_usuario_endereco.id_usuario WHERE tbl_usuario.status = "a")',
                        'sinal' => '',
                        'valor' => NULL
                    )
                )
            );
        }


        $cidades = $this->superModel->select('cidade', $clausulas);

        $resultado = array();

        if(!$this->input->get('estado')) {
            foreach($cidades as $cidade) {
                $resultado[] = $cidade->nome;
            }
        } else {
            $resultado = $cidades;
        }

        print json_encode($resultado);
    }

        public function gravaOrcamento(){

        $orcamento = array(
                    'nome'              => $this->usuario->nome,
                    'email'             => $this->usuario->email,
                    'telefone'          => $this->usuario->telefone,
                    'celular'           => $this->usuario->celular,
                    'id_cidade'         => $this->cidades->id_cidade,
                    'id_subcategoria'   => $this->subcategorias->id_subcategoria,
                    'data_orcamento'    => date('Y-m-d H:i:s')
                );


        $this->superModel->insert('orcamento', $orcamento);

        }

     
    private function enviaEmail($data) {
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
                                                                <td align="center" style="padding: 20px 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding-copy">Olá ' . $data['profissional'] . '! <strong>' . $data['usuario'] . '</strong> está buscando pelo seu serviço. Entre em contato:</td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="mobile-button-container">
                                                            <tr>
                                                                <td align="center" style="padding: 20px 0 0 0;font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding-copy">
                                                                    <table border="0" cellspacing="0" cellpadding="0" class="responsive-table">
                                                                        <tr>
                                                                            <strong>Email</strong>: ' . $data['emailUsuario'] . '
                                                                        </tr>
                                                                        <tr>
                                                                            <strong>Telefone</strong>: '. $data['telefoneUsuario'] . '
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
            ->to($data['emailPara'])
            ->subject("Sossegue - Solicitação de serviço")
            ->message($mensagem)
            ->send();

    }
}
