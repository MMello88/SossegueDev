<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Planos extends MY_Restrita {
    public function __construct() {
		parent::__construct();
    }

    public function cadastrar() {
        $clausulas = array(
            'condicoes' => array(
                array(
                    'campo' => 'status',
                    'sinal' => '<>',
                    'valor' => 'd'
                )
            )
        );

        $this->data['opcoes'] = $this->superModel->select('planos_opcoes', $clausulas);
        $this->data['titulo'] = 'Novo plano';
        $this->layout->view('restrita/planos/cadastrar', $this->data);
    }

    public function editar($id) {
        $clausulas = array(
            'row' => true,
            'condicoes' => array(
                array(
                    'campo' => 'id_plano',
                    'valor' => $id
                )
            )
        );
        date_default_timezone_set("America/Sao_Paulo");


        $this->data['plano'] = $this->superModel->select('planos', $clausulas);

        $clausulas = array(
            'condicoes' => array(
                array(
                    'campo' => 'status',
                    'sinal' => '<>',
                    'valor' => 'd'
                )
            )
        );

        $this->data['opcoes'] = $this->superModel->select('planos_opcoes', $clausulas);

        $clausulas = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_planos',
                    'valor' => $id
                )
            )
        );

        $relacaoOpcoes = $this->superModel->select('planos_relacao', $clausulas);
        $this->data['relacaoOpcoes'] = array();

        foreach($relacaoOpcoes as $key => $opcao) {
            $this->data['relacaoOpcoes'][$opcao->id_planos_opcoes] = $opcao;
        }

        $this->data['titulo'] = 'Editar plano';
        $this->layout->view('restrita/planos/editar', $this->data);
    }

    public function alterar() {
        if($this->form_validation->run('restrita/planos/inserir')) {
            $condicoes = array(
                array(
                    'campo' => 'id_plano',
                    'valor' => $this->input->post('id'),
                )
            );

            $nomeAntigo = $this->superModel->select('planos', array('row' => TRUE, 'condicoes' => $condicoes, 'select' => 'plano'))->plano;

            $data = array(
                'complemento' => $this->input->post('plano')
            );

            $this->superModel->update('configuracoes', $data, array(
                array(
                    'campo' => 'complemento',
                    'valor' => $nomeAntigo
                )
            ));

            $data = array(
                'plano' => $this->input->post('plano'),
                'preco' => $this->input->post('preco'),
                'trimestral' => $this->input->post('trimestral'),
                'semestral' => $this->input->post('semestral'),
                'anual' => $this->input->post('anual'),
                'dias' => $this->input->post('dias')
            );

            $this->superModel->update('planos', $data, $condicoes);

            $informacoes = $this->input->post('informacoes');

            foreach($this->input->post('opcoes') as $id_planos_opcoes => $opcao) {
                $clausulas = array(
                    'row' => true,
                    'condicoes' => array(
                        array(
                            'campo' => 'id_planos',
                            'valor' => $this->input->post('id')
                        ),
                        array(
                            'campo' => 'id_planos_opcoes',
                            'valor' => $id_planos_opcoes
                        )
                    )
                );

                $consulta = $this->superModel->select('planos_relacao', $clausulas);

                if($opcao) {
                    if($consulta) {
                        $condicoes = array(
                            array(
                                'campo' => 'id_planos_relacao',
                                'valor' => $consulta->id_planos_relacao
                            )
                        );

                        $data = array(
                            'info' => $informacoes[$id_planos_opcoes]
                        );

                        $this->superModel->update('planos_relacao', $data, $condicoes);
                    } else {
                        $data = array(
                            'id_planos' => $this->input->post('id'),
                            'id_planos_opcoes' => $id_planos_opcoes,
                            'info' => $informacoes[$id_planos_opcoes]
                        );

                        $this->superModel->insert('planos_relacao', $data);
                    }
                } else {
                    if($consulta) {
                        $data = array(
                            'campo' => 'id_planos_relacao',
                            'valor' => $consulta->id_planos_relacao
                        );

                        $this->superModel->delete('planos_relacao', $data);
                    }
                }
            }

            $this->session->set_flashdata('msg', $this->lang->line('cadastro_atualizado'));
            redirect('restrita/planos/listar');
        }
    }

    public function inserir() {
        if($this->form_validation->run('restrita/planos/inserir')) {
            $post = $this->input->post();
            $opcoes = $post['opcoes'];
            $informacoes = $post['informacoes'];

            unset($post['opcoes']);
            unset($post['informacoes']);

            $idPlano = $this->superModel->insert('planos', $post);

            foreach($opcoes as $id_planos_opcoes => $opcao) {
                if($opcao) {
                    $data = array(
                        'id_planos' => $idPlano,
                        'id_planos_opcoes' => $id_planos_opcoes,
                        'info' => $informacoes[$id_planos_opcoes]
                    );

                    $this->superModel->insert('planos_relacao', $data);
                }
            }

            $this->session->set_flashdata('msg', $this->lang->line('cadastro_inserido'));
            redirect('restrita/planos/listar');
        }
    }

	public function listar() {
        $clausulas = array(
            'condicoes' => array(
                array(
                    'campo' => 'status',
                    'sinal' => '<>',
                    'valor' => 'd'
                )
            )
        );

        $this->data['planos'] = $this->superModel->select('planos', $clausulas);
        $this->data['titulo'] = 'Planos';

		$this->layout->view('restrita/planos/listar', $this->data);
	}

    public function opcoes() {
        $clausulas = array(
            'condicoes' => array(
                array(
                    'campo' => 'status',
                    'sinal' => '<>',
                    'valor' => 'd'
                )
            )
        );

        $this->data['opcoes'] = $this->superModel->select('planos_opcoes', $clausulas);
        $this->data['titulo'] = 'Opções do plano';

		$this->layout->view('restrita/planos/opcoes', $this->data);
	}

    public function cadastrarOpcao() {
        $this->data['titulo'] = 'Nova opção do plano';
        $this->layout->view('restrita/planos/cadastrarOpcao', $this->data);
    }

    public function inserirOpcao() {
        if($this->form_validation->run('restrita/planos/inserirOpcao')) {
            $this->superModel->insert('planos_opcoes', $this->input->post());
            $this->session->set_flashdata('msg', $this->lang->line('cadastro_inserido'));
            redirect('restrita/planos/opcoes');
        }
    }

    public function editarOpcao($id) {
        $clausulas = array(
            'row' => true,
            'condicoes' => array(
                array(
                    'campo' => 'id_planos_opcoes',
                    'valor' => $id
                )
            )
        );

        $this->data['opcao'] = $this->superModel->select('planos_opcoes', $clausulas);
        $this->data['titulo'] = 'Editar opção do plano';
        $this->layout->view('restrita/planos/editarOpcao', $this->data);
    }

    public function alterarOpcao() {
        if($this->form_validation->run('restrita/planos/inserirOpcao')) {
            $condicoes = array(
                array(
                    'campo' => 'id_planos_opcoes',
                    'valor' => $this->input->post('id'),
                )
            );

            $data = array(
                'opcao' => $this->input->post('opcao'),
                'ordem' => $this->input->post('ordem')
            );

            $this->superModel->update('planos_opcoes', $data, $condicoes);
            $this->session->set_flashdata('msg', $this->lang->line('cadastro_atualizado'));
            redirect('restrita/planos/opcoes');
        }
    }
}
