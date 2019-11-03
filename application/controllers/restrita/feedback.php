<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Feedback extends MY_Restrita {
    public function __construct() {
		parent::__construct();
    }

    public function cadastrar() {
        $this->data['titulo'] = 'Nova feedback';
        $this->layout->view('restrita/feedback/cadastrar', $this->data);
    }

    public function editar($id) {
        $clausulas = array(
            'row' => true,
            'condicoes' => array(
                array(
                    'campo' => 'id',
                    'valor' => $id
                )
            )
        );

        $this->data['feedback'] = $this->superModel->select('feedback', $clausulas);
        $this->data['titulo'] = 'Editar feedback';
        $this->layout->view('restrita/feedback/editar', $this->data);
    }

    public function alterar() {
        if($this->form_validation->run('restrita/feedback/inserir')) {
            $foto = $this->input->post('fotoAntiga');

            if(!$_FILES['feedback']['error']) {
                $url = $this->superModel->getUrlAmigavel($this->input->post('nome'), 'tbl_feedback', 'foto', 'id', $this->input->post('id'));

                unlink(DIR_FEEDBACK . $foto);

                $data = array(
                    'dir' => './uploads/feedback/',
                    'fileName' => $url,
                    'inputName' => 'feedback',
                );

                $foto = $this->realizaUpload($data, true);
            }

            $condicoes = array(
                array(
                    'campo' => 'id',
                    'valor' => $this->input->post('id'),
                )
            );

            $data = array(
                'nome' => $this->input->post('nome'),
                'texto' => $this->input->post('texto'),
                'foto' => $foto
            );

            $this->superModel->update('feedback', $data, $condicoes);
            $this->session->set_flashdata('msg', $this->lang->line('cadastro_atualizado'));
            redirect('restrita/feedback');
        }
    }

    public function inserir() {
        if($this->form_validation->run('restrita/feedback/inserir')) {
            $foto = '';

            if(!$_FILES['feedback']['error']) {
                $url = $this->superModel->getUrlAmigavel($this->input->post('nome'), 'tbl_feedback', 'foto');

                $data = array(
                    'dir' => './uploads/feedback/',
                    'fileName' => $url,
                    'inputName' => 'feedback'
                );

                $foto = $this->realizaUpload($data, true);
            }

            $this->superModel->insert('feedback', array(
                'nome' => $this->input->post('nome'),
                'texto' => $this->input->post('texto'),
                'foto' => $foto
            ));

            $this->session->set_flashdata('msg', $this->lang->line('cadastro_inserido'));
            redirect('restrita/feedback');
        }
    }

	public function index() {
        $clausulas = array(
            'condicoes' => array(
                array(
                    'campo' => 'status',
                    'sinal' => '<>',
                    'valor' => 'd'
                )
            )
        );

        $this->data['feedbacks'] = $this->superModel->select('feedback', $clausulas);
        $this->data['titulo'] = 'Feedback';

		$this->layout->view('restrita/feedback/listar', $this->data);
	}
}
