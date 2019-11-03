<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Busca_Profissional extends MY_Front {
    var $qtde = 30;

    public function profissionais() {

        $profissao = $this->input->get('profissao');
        $cidade = $this->input->get('cidade');


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

            $this->layout->view('busca_profissional', $this->data);

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

                  ////  if(!$consulta) {
                      //  $this->enviaEmail($email);
                    //}
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



}
