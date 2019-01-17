<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Faq extends MY_Restrita {
    public function __construct() {
		parent::__construct();
    }

    public function cadastrar() {
        $this->data['titulo'] = 'Novo faq';
        $this->layout->view('restrita/faq/cadastrar', $this->data);
    }

    public function editar($id) {
        $data = array(
            'row' => TRUE,
            'condicoes' => array(
                array(
                    'campo' => 'id',
                    'valor' => $id
                )
            )
        );

        $this->data['faq'] = $this->superModel->select('faq', $data);
        $this->data['titulo'] = 'Editar faq';
        $this->layout->view('restrita/faq/editar', $this->data);
    }

    public function alterar() {
        if($this->form_validation->run('restrita/faq/inserir')) {
            $condicoes = array(
                array(
                    'campo' => 'id',
                    'valor' => $this->input->post('id'),
                )
            );

            $data = array(
                'tipo' => $this->input->post('tipo'),
                'pergunta' => $this->input->post('pergunta'),
                'resposta' => $this->input->post('resposta')
            );

            $this->superModel->update('faq', $data, $condicoes);
            $this->session->set_flashdata('msg', $this->lang->line('cadastro_atualizado'));
            redirect('restrita/faq');
        }
    }

    public function inserir() {
        if($this->form_validation->run('restrita/faq/inserir')) {
            $this->superModel->insert('faq', $this->input->post());
            $this->session->set_flashdata('msg', $this->lang->line('cadastro_inserido'));
            redirect('restrita/faq');
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

        $this->data['faqs'] = $this->superModel->select('faq', $clausulas);
        $this->data['titulo'] = 'FAQ';

		$this->layout->view('restrita/faq/listar', $this->data);
	}
}
