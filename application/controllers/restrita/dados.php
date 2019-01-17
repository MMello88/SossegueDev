<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dados extends MY_Restrita {
    public function anuncio() {
        if($this->form_validation->run('restrita/dados/anuncio')) {
            $idUsuario = $this->session->userdata('id');

            if(!$idUsuario) {
                redirect('login');
            }

            $url = $this->superModel->getUrlAmigavel($this->session->userdata('nome'), 'tbl_profissional', 'url', 'id_profissional', $this->input->post('id_profissional'));
            $foto = $this->input->post('fotoAntiga');

            if(isset($_FILES['foto']) && !$_FILES['foto']['error']) {
                if($foto && file_exists(DIR_PROFISSIONAL . $foto)) {
                    unlink(DIR_PROFISSIONAL . $foto);
                }

                $data = array(
                    'dir' => './uploads/profissionais/',
                    'fileName' => $url,
                    'inputName' => 'foto',
                );

                $foto = $this->realizaUpload($data, true);
            }

            $profissional = array(
                'descricao' => $this->input->post('descricao'),
                'foto' => $foto
            );

            if($this->input->post('subcategoria')) {
                $profissional['id_subcategoria'] = $this->input->post('subcategoria');
            }

            $condicoes = array(
                array(
                    'campo' => 'id_profissional',
                    'valor' => $this->input->post('id_profissional'),
                )
            );

            $this->superModel->update('profissional', $profissional, $condicoes);

            if($this->input->post('links')) {
                foreach($this->input->post('links') as $idLinks => $link) {
                    $data = array(
                        'row' => TRUE,
                        'select' => 'id_profissional_links',
                        'condicoes' => array(
                            array(
                                'campo' => 'id_links',
                                'valor' => $idLinks
                            ),
                            array(
                                'campo' => 'id_profissional',
                                'valor' => $this->input->post('id_profissional')
                            )
                        )
                    );

                    $consulta = $this->superModel->select('profissional_links', $data);
                    $idProfissionalLinks = $consulta ? $consulta->id_profissional_links : '';

                    if($link) {
                        if($idProfissionalLinks) {
                            $data = array(
                                'link' => $link
                            );

                            $condicoes = array(
                                array(
                                    'campo' => 'id_profissional_links',
                                    'valor' => $idProfissionalLinks
                                )
                            );

                            $this->superModel->update('profissional_links', $data, $condicoes);
                        } else {
                            $data = array(
                                'id_links' => $idLinks,
                                'id_profissional' => $this->input->post('id_profissional'),
                                'link' => $link
                            );

                            $this->superModel->insert('profissional_links', $data);
                        }
                    } else {
                        if($idProfissionalLinks) {
                            $condicoes = array(
                                    'campo' => 'id_profissional_links',
                                    'valor' => $idProfissionalLinks
                            );

                            $this->superModel->delete('profissional_links', $condicoes);
                        } else {
                            $data = array(
                                'id_links' => $idLinks,
                                'id_profissional' => $this->input->post('id_profissional'),
                                'link' => $link
                            );

                            $this->superModel->insert('profissional_links', $data);
                        }
                    }
                }
            }

            $this->session->set_flashdata('msg', $this->lang->line('cadastro_atualizado'));
            redirect('restrita/home');
        } else {
            $id = $this->session->userdata('id');

            if(!$id) {
                redirect('login');
            }

            insertTag('js', 'jquery.maskedinput.min.js', $this->data);
            insertTag('css', base_url('assets/plugins/dropzone/css/dropzone.css'), $this->data);
            insertTag('js', base_url('assets/plugins/dropzone/dropzone.js'), $this->data);
            insertTag('js', base_url('assets/plugins/jquery-ui.js'), $this->data);

            $clausulas = array(
                'row' => true,
                'select' => 'profissional_fatura.status AS status, usuario.nome, usuario.email, profissional.descricao, profissional.foto, profissional_fatura.id_profissional, subcategoria.id_subcategoria, subcategoria, total, preco, plano, categoria, DATE_FORMAT(inicio, "%d/%m/%Y %H:%i:%s") AS inicio, DATE_FORMAT(fim, "%d/%m/%Y %H:%i:%s") AS fim, situacao, NOW() < fim AS ativo, id_transacao AS transacao, link_pagamento',
                'condicoes' => array(
                    array(
                        'campo' => 'usuario.id_usuario',
                        'valor' => $id
                    )
                ),
                'join' => array(
                    array(
                        'tabela' => 'profissional',
                        'on' => 'profissional.id_usuario = usuario.id_usuario'
                    ),
                    array(
                        'tabela' => 'profissional_fatura',
                        'on' => 'profissional_fatura.id_profissional = profissional.id_profissional'
                    ),
                    array(
                        'tabela' => 'planos',
                        'on' => 'planos.id_plano = profissional_fatura.id_planos'
                    ),
                    array(
                        'tabela' => 'subcategoria',
                        'on' => 'profissional.id_subcategoria = subcategoria.id_subcategoria'
                    ),
                    array(
                        'tabela' => 'categoria',
                        'on' => 'subcategoria.id_categoria = categoria.id_categoria'
                    )
                ),
                'order' => array(
                    array(
                        'campo' => 'id_profissional_fatura',
                        'valor' => 'DESC'
                    )
                )
            );

            $this->data['usuario'] = $this->superModel->select('usuario', $clausulas);

            if(!$this->data['usuario']) {
                redirect('restrita/home');
            }

            $data = array(
                'row' => TRUE,
                'select' => 'plano',
                'condicoes' => array(
                    array(
                        'campo' => 'plano',
                        'valor' => $this->data['usuario']->plano
                    ),
                    array(
                        'campo' => ' (opcao LIKE "%link%" OR opcao LIKE "%site%")',
                        'valor' => NULL,
                        'sinal' => false,
                        'escape' => false
                    )
                ),
                'join' => array(
                    array(
                        'tabela' => 'planos_relacao',
                        'on' => 'planos_relacao.id_planos_opcoes = planos_opcoes.id_planos_opcoes'
                    ),
                    array(
                        'tabela' => 'planos',
                        'on' => 'planos.id_plano = tbl_planos_relacao.id_planos'
                    )
                )
            );

            $possuiLinks = $this->superModel->select('planos_opcoes', $data);

            if($possuiLinks) {
                $clausulas = array(
                    'condicoes' => array(
                        array(
                            'campo' => 'status',
                            'valor' => 'a'
                        )
                    )
                );

                $this->data['links'] = $this->superModel->select('links', $clausulas);
            } else {
                $this->data['links'] = array();
            }

            $data = array(
                'row' => TRUE,
                'select' => 'plano',
                'condicoes' => array(
                    array(
                        'campo' => 'plano',
                        'valor' => $this->data['usuario']->plano
                    ),
                    array(
                        'campo' => ' (opcao LIKE "%foto%" OR opcao LIKE "%galeria%")',
                        'valor' => NULL,
                        'sinal' => false,
                        'escape' => false
                    )
                ),
                'join' => array(
                    array(
                        'tabela' => 'planos_relacao',
                        'on' => 'planos_relacao.id_planos_opcoes = planos_opcoes.id_planos_opcoes'
                    ),
                    array(
                        'tabela' => 'planos',
                        'on' => 'planos.id_plano = tbl_planos_relacao.id_planos'
                    )
                )
            );

            $possuiFotos = $this->superModel->select('planos_opcoes', $data);

            if($possuiFotos) {
                $clausulas = array(
                    'condicoes' => array(
                        array(
                            'campo' => 'id_profissional',
                            'valor' => $this->data['usuario']->id_profissional
                        )
                    ),
                    'order' => array(
                        array(
                            'campo' => 'ordem'
                        )
                    )
                );

                $this->data['fotos'] = $this->superModel->select('fotos', $clausulas);
            } else {
                $this->data['fotos'] = array();
            }

            $data = array(
                'row' => 'true',
                'select' => 'valor as maxFotos',
                'condicoes' => array(
                    array(
                        'campo' => 'campo',
                        'valor' => 'max_fotos'
                    ),
                    array(
                        'campo' => 'complemento',
                        'valor' => $this->data['usuario']->plano
                    )
                )
            );

            $this->data['maxFotos'] = (int) $this->superModel->select('configuracoes', $data)->maxFotos - count($this->data['fotos']);

            foreach($this->data['links'] as $link) {
                $data = array(
                    'row' => true,
                    'select' => 'link',
                    'condicoes' => array(
                        array(
                            'campo' => 'id_links',
                            'valor' => $link->id_links
                        ),
                        array(
                            'campo' => 'id_profissional',
                            'valor' => $this->data['usuario']->id_profissional
                        )
                    )
                );

                $consulta = $this->superModel->select('profissional_links', $data);

                $this->data['usuario']->link[$link->nome] = $consulta ? $consulta->link : '';
            }

            $clausulas = array(
                'condicoes' => array(
                    array(
                        'campo' => 'status',
                        'valor' => 'a'
                    )
                ),
                'order' => array(
                    array(
                        'campo' => 'categoria'
                    )
                )
            );

            $this->data['listaCategorias'] = $this->superModel->select('categoria', $clausulas);

            $clausulas = array(
                'row' => true,
                'select' => 'id_categoria',
                'condicoes' => array(
                    array(
                        'campo' => 'id_subcategoria',
                        'valor' => $this->data['usuario']->id_subcategoria
                    )
                )
            );

            $this->data['usuario']->id_categoria = $this->superModel->select('subcategoria', $clausulas)->id_categoria;

            $clausulas = array(
                'select' => 'id_subcategoria, subcategoria',
                'condicoes' => array(
                    array(
                        'campo' => 'id_categoria',
                        'valor' => $this->data['usuario']->id_categoria
                    )
                )
            );

            $this->data['subcategorias'] = $this->superModel->select('subcategoria', $clausulas);

            $this->data['titulo'] = 'Meu anúncio';
            $this->layout->view('restrita/dados/anuncio', $this->data);
        }
    }

	public function usuario() {
        if($this->form_validation->run('restrita/dados/atualiza')) {
            $idUsuario = $this->session->userdata('id');

            if(!$idUsuario) {
                redirect('login');
            }

            $usuario = array(
                'nome' => $this->input->post('nome'),
                'email' => $this->input->post('email'),
                'nascimento' => convertData($this->input->post('nascimento')),
                'telefone' => $this->input->post('telefone'),
                'celular' => $this->input->post('celular')
            );

            if($this->input->post('senha')) {
                $usuario['senha'] = Bcrypt::hash($this->input->post('senha'));
            }

            $condicoes = array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $idUsuario,
                )
            );

            $this->superModel->update('usuario', $usuario, $condicoes);

            if(!$this->input->post('cidade')) {
                redirect('restrita/dados/usuario');
            }

            $data = array(
                'row' => TRUE,
                'select' => 'id_usuario_endereco',
                'condicoes' => array(
                    array(
                        'campo' => 'id_usuario',
                        'valor' => $idUsuario
                    )
                )
            );

            $consulta = $this->superModel->select('usuario_endereco', $data);

            $endereco = array(
                'id_cidade' => $this->input->post('cidade'),
                'bairro' => $this->input->post('bairro'),
                'endereco' => $this->input->post('endereco'),
                'numero' => $this->input->post('numero'),
                'complemento' => $this->input->post('complemento'),
            );

            if($consulta) {
                $idEndereco = $consulta->id_usuario_endereco;
                $condicoes = array(
                    array(
                        'campo' => 'id_usuario_endereco',
                        'valor' => $idEndereco
                    )
                );

                $this->superModel->update('usuario_endereco', $endereco, $condicoes);
            } else {
                $endereco['id_usuario'] = $idUsuario;
                $this->superModel->insert('usuario_endereco', $endereco);
            }

            $condicoes = array(
                'row' => TRUE,
                'select' => 'nome AS cidade',
                'condicoes' => array(
                    array(
                        'campo' => 'id',
                        'valor' => $endereco['id_cidade']
                    )
                )
            );

            $this->session->set_userdata('cidade', $this->superModel->select('cidade', $condicoes)->cidade);

            $this->session->set_flashdata('msg', $this->lang->line('cadastro_atualizado'));
            redirect('restrita/home');
        } else {
            $id = $this->session->userdata('id');

            if(!$id) {
                redirect('login');
            }

            insertTag('js', 'jquery.maskedinput.min.js', $this->data);

            $this->data['estados'] = $this->superModel->select('estado');

            $clausulas = array(
                'row' => true,
                'select' => 'usuario.id_usuario, nome, email, DATE_FORMAT(nascimento, "%d/%m/%Y") AS nascimento, telefone, celular, id_cidade, bairro, endereco, numero, complemento, id_usuario_endereco',
                'condicoes' => array(
                    array(
                        'campo' => 'usuario.id_usuario',
                        'valor' => $id
                    )
                ),
                'join' => array(
                    array(
                        'tabela' => 'usuario_endereco',
                        'on' => 'usuario_endereco.id_usuario = usuario.id_usuario',
                        'option' => 'left'
                    )
                ),
            );

            $this->data['usuario'] = $this->superModel->select('usuario', $clausulas);

            if(isset($this->data['usuario']->id_cidade)) {
                $clausulas = array(
                    'row' => true,
                    'select' => 'estado',
                    'condicoes' => array(
                        array(
                            'campo' => 'id',
                            'valor' => $this->data['usuario']->id_cidade
                        )
                    )
                );

                $this->data['usuario']->id_estado = $this->superModel->select('cidade', $clausulas)->estado;

                $clausulas = array(
                    'select' => 'id, nome',
                    'condicoes' => array(
                        array(
                            'campo' => 'estado',
                            'valor' => $this->data['usuario']->id_estado
                        )
                    )
                );

                $this->data['cidades'] = $this->superModel->select('cidade', $clausulas);
            }

            if(!isset($this->data['usuario']->id_cidade) || !isset($this->data['usuario']->bairro) || !isset($this->data['usuario']->endereco) || !isset($this->data['usuario']->numero) || !$this->data['usuario']->bairro || !$this->data['usuario']->endereco || !$this->data['usuario']->numero) {
                $this->data['cadastroIncompleto'] = true;
            }

            $this->data['titulo'] = 'Editar dados';
            $this->layout->view('restrita/dados/usuario', $this->data);
        }
	}

    public function renovarAnuncio() {
        if($this->form_validation->run('restrita/anunciar')) {
            $idUsuario = $this->session->userdata('id');

            if(!$idUsuario) {
                redirect('login');
            }

            $data = array(
                'row' => TRUE,
                'select' => 'id_tipo_usuario',
                'condicoes' => array(
                    array(
                        'campo' => 'tipo_usuario',
                        'valor' => 'Profissional'
                    )
                )
            );

            $idTipoUsuario = $this->superModel->select('tipo_usuario', $data)->id_tipo_usuario;

            $data = array(
                'id_tipo_usuario' => $idTipoUsuario
            );

            $condicoes = array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $idUsuario
                )
            );

            $this->superModel->update('usuario', $data, $condicoes);

            $data = array(
                'id_subcategoria' => $this->input->post('subcategoria')
            );

            $condicoes = array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $idUsuario
                )
            );

            $this->superModel->update('profissional', $data, $condicoes);

            $url = $this->superModel->getUrlAmigavel($this->session->userdata('nome'), 'tbl_profissional', 'url');

            $data = array(
                'row' => TRUE,
                'select' => 'id_profissional',
                'condicoes' => array(
                    array(
                        'campo' => 'id_usuario',
                        'valor' => $idUsuario
                    )
                )
            );

            $idProfissional = $this->superModel->select('profissional', $data)->id_profissional;
            list($idPlano, $periodo) = explode('_', $this->input->post('plano'));

            $data = array(
                'status' => 'i'
            );

            $condicoes = array(
                array(
                    'campo' => 'id_profissional',
                    'valor' => $idProfissional
                )
            );

            $this->superModel->update('profissional_fatura', $data, $condicoes);

            $clausulas = array(
                'row' => true,
                'condicoes' => array(
                    array(
                    'campo' => 'status',
                    'valor' => 'a'
                    ),
                    array(
                        'campo' => 'id_plano',
                        'valor' => $idPlano
                    )
                )
            );

            $plano = $this->superModel->select('planos', $clausulas);
            $now = date('Y-m-d H:i:s');

            switch($periodo) {
                case 'trimestral':
                    $preco = $plano->trimestral;
                    $months = ' + 3 months';
                    $total = $preco * 3;
                    break;
                case 'semestral':
                    $preco = $plano->semestral;
                    $months = ' + 6 months';
                    $total = $preco * 6;
                    break;
                case 'anual':
                    $preco = $plano->anual;
                    $months = ' + 12 months';
                    $total = $preco * 12;
                    break;
                default:
                    $preco = $plano->preco;
                    $months = ' + 1 month';
                    $total = $preco;
                    break;
            }

            $data = array(
                'select' => 'confirmado',
                'row' => true,
                'condicoes' => array(
                    array(
                        'campo' => 'id_usuario',
                        'valor' => $idUsuario
                    )
                )
            );

            $verificaUsuario = $this->superModel->select('usuario', $data);

            $fatura = array(
                'id_profissional' => $idProfissional,
                'id_planos' => $idPlano,
                'inicio' => $now,
                'fim' => date('Y-m-d H:i:s', strtotime($now . $months)),
                'total' => $total,
                'situacao' => $total > 0 ? 'p' : 'a',
                'status' => $verificaUsuario->confirmado === 's' ? 'a' : 'i'
            );

            $idFatura = $this->superModel->insert('profissional_fatura', $fatura);

            if($fatura['total'] > 0) {
                $data = array(
                    'row' => TRUE,
                    'select' => 'nome, email, celular, cpf',
                    'condicoes' => array(
                        array(
                            'campo' => 'id_usuario',
                            'valor' => $idUsuario
                        )
                    )
                );

                $usuario = $this->superModel->select('usuario', $data);

                $produto = array(
                    'comprador' => array(
                        'nome' => $usuario->nome,
                        'email' => $usuario->email,
                        'ddd' => substr(preg_replace('/\(|\)/', '', substr($usuario->celular, 0, 3)), 0, 2),
                        'telefone' => str_replace('-', '', substr($usuario->celular, 5, 10)),
                        'cpf' => $usuario->cpf
                    ),
                    'nome' => 'Plano ' . $plano->plano . ' - ' . ucwords($periodo),
                    'qtde' => 1,
                    'idFatura' => $idFatura,
                    'total' => $fatura['total'],
                );

                $this->realizaPagamento($produto);
            } else {
                $this->session->set_flashdata('msg', $this->lang->line('cadastro_inserido'));
                redirect('restrita/home');
            }
        } else {
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

            $clausulas = array(
                'condicoes' => array(
                    array(
                        'campo' => 'status',
                        'valor' => 'a'
                    )
                ),
                'order' => array(
                    array(
                        'campo' => 'ordem',
                        'valor' => 'ASC'
                    )
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

            $this->data['titulo'] = 'Anunciar';
            $this->layout->view('restrita/dados/renovarAnuncio', $this->data);
        }
	}

    public function relatorio() {
        $id = $this->session->userdata('id');

        if(!$id || $this->session->userdata('tipo') === 'Comum' || ($this->session->userdata('plano') !== 'Avançado')) {
            redirect('login');
        }

        $this->data['meses'] = array('Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
        $this->data['titulo'] = 'Relatório de acessos';
        $this->layout->view('restrita/relatorio', $this->data);
    }

    public function getRelatorio() {
        $id = $this->session->userdata('id');

        if(!$id || $this->session->userdata('tipo') === 'Comum') {
            redirect('login');
        }

        if($this->session->userdata('tipo') === 'Admin' && $this->input->get('profissional')) {
            $idProfissional = $this->input->get('profissional');
        } else {
            $idProfissional = $this->superModel->select('profissional', array(
                'select' => 'id_profissional',
                'row' => true,
                'condicoes' => array(
                    array(
                        'campo' => 'id_usuario',
                        'valor' => $id
                    )
                )
            ))->id_profissional;
        }


        $mes = $this->input->get('mes') ? $this->input->get('mes') : date('m');
        $ano = $this->input->get('ano') ? $this->input->get('ano') : date('Y');
        $dataMes = $ano . '-' . $mes . '-01';
        $mes .= '/' . $ano;

        $data = array(
            'row' => true,
            'select' => "DAY(LAST_DAY('$dataMes')) AS total"
        );

        $dias = $this->superModel->select('relatorio', $data);
        $relatorio = array();

        foreach(range(1, $dias->total) as $dia) {
            $dia = $dia < 10 ? '0' . $dia : $dia;

            $data = array(
                'row' => 'total',
                'select' => 'count(*) AS total',
                'condicoes' => array(
                    array(
                        'campo' => 'DATE_FORMAT(data, "%d/%m/%Y")',
                        'valor' => "'$dia/$mes'",
                        'escape' => true
                    ),
                    array(
                        'campo' => 'tipo',
                        'valor' => 'b'
                    ),
                    array(
                        'campo' => 'id_profissional',
                        'valor' => $idProfissional
                    )
                )
            );

            $visualizacoes = $this->superModel->select('relatorio', $data);

            $data['condicoes'][1] = array(
                'campo' => 'tipo',
                'valor' => 'd'
            );

            $detalhes = $this->superModel->select('relatorio', $data);

            $relatorio[] = array(
                (int)$dia,
                (int)$visualizacoes->total,
                (int)$detalhes->total,
            );
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($relatorio));
    }

    private function realizaPagamento($produto) {
        $this->load->library('PagSeguroLibrary/PagSeguroLibrary.php');

        $paymentRequest = new PagSeguroPaymentRequest();

        $paymentRequest->setReference($produto['idFatura']);
        $paymentRequest->addItem('1', $produto['nome'], $produto['qtde'], $produto['total']);

        $paymentRequest->setSender(
            $produto['comprador']['nome'],
            $produto['comprador']['email'],
            $produto['comprador']['ddd'],
            $produto['comprador']['telefone'],
            'CPF',
            $produto['comprador']['cpf']
        );

        $paymentRequest->setCurrency("BRL");

        $paymentRequest->setRedirectUrl("http://www.sossegue.com.br/restrita/dados/anuncio");
        $paymentRequest->addParameter('notificationURL', 'http://www.sossegue.com.br/api/transacoesPagseguro');

        try {
            $credentials = PagSeguroConfig::getAccountCredentials();
            $url = $paymentRequest->register($credentials);

            $condicoes = array(
                array(
                    'campo' => 'id_profissional_fatura',
                    'valor' => $produto['idFatura']
                )
            );

            $this->superModel->update('profissional_fatura', array('link_pagamento' => $url), $condicoes);

            redirect($url);
        } catch (PagSeguroServiceException $e) {
            die($e->getMessage());
        }
    }
}
