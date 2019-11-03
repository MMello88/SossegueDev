<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Avaliacoes extends MY_Restrita {
	public function index() {
        $data = array(
            'select' => 'id_nota, id_usuario, status, id_profissional, nota, DATE_FORMAT(data, "%d/%m/%Y") as data',
            'condicoes' => array(
                array(
                    'campo' => 'status',
                    'valor' => 'd',
                    'sinal' => '<>'
                )
            )
        );

        $this->data['titulo'] = 'Avaliações';
        $this->data['avaliacoes'] = $this->superModel->select('nota', $data);

        foreach($this->data['avaliacoes'] as $key => $avaliacao) {
            $data = array(
                'row' => TRUE,
                'select' => 'nome',
                'condicoes' => array(
                    array(
                        'campo' => 'id_usuario',
                        'valor' => $avaliacao->id_usuario
                    )
                )
            );

            $this->data['avaliacoes'][$key]->usuario = $this->superModel->select('usuario', $data)->nome;

            $data = array(
                'row' => TRUE,
                'select' => 'nome',
                'condicoes' => array(
                    array(
                        'campo' => 'profissional.id_profissional',
                        'valor' => $avaliacao->id_profissional
                    )
                ),
                'join' => array(
                    array(
                        'tabela' => 'usuario',
                        'on' => 'usuario.id_usuario = profissional.id_usuario'
                    )
                )
            );

            $this->data['avaliacoes'][$key]->profissional = $this->superModel->select('profissional', $data)->nome;
        }

		$this->layout->view('restrita/avaliacoes/listar', $this->data);
	}

    public function editar($id) {
        $data = array(
            'row' => TRUE,
            'condicoes' => array(
                array(
                    'campo' => 'id_nota',
                    'valor' => $id
                )
            )
        );

        $this->data['titulo'] = 'Editar avaliação';
        $this->data['avaliacao'] = $this->superModel->select('nota', $data);

        $data = array(
            'row' => TRUE,
            'select' => 'nome',
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->data['avaliacao']->id_usuario
                )
            )
        );

        $this->data['avaliacao']->usuario = $this->superModel->select('usuario', $data)->nome;

        $data = array(
            'row' => TRUE,
            'select' => 'nome',
            'condicoes' => array(
                array(
                    'campo' => 'profissional.id_profissional',
                    'valor' => $this->data['avaliacao']->id_profissional
                )
            ),
            'join' => array(
                array(
                    'tabela' => 'usuario',
                    'on' => 'usuario.id_usuario = profissional.id_usuario'
                )
            )
        );

        $this->data['avaliacao']->profissional = $this->superModel->select('profissional', $data)->nome;
        $this->layout->view('restrita/avaliacoes/editar', $this->data);
    }

    public function alterar() {
        if($this->form_validation->run('restrita/avaliacao/alterar')) {
            $condicoes = array(
                array(
                    'campo' => 'id_nota',
                    'valor' => $this->input->post('id_nota')
                )
            );

            $data = array(
                'data' => convertData($this->input->post('data')),
                'comentario' => $this->input->post('comentario'),
                'resposta' => $this->input->post('resposta')
            );

            $this->superModel->update('nota', $data, $condicoes);

            $this->session->set_flashdata('msg', $this->lang->line('cadastro_atualizado'));
            redirect('restrita/avaliacoes');
        }
    }
}
