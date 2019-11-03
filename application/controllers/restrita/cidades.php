<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cidades extends MY_Restrita {
    public function __construct() {
		parent::__construct();
    }

    public function cadastrar() {
        $clausulas = array();
        $this->data['estados'] = $this->superModel->select('estado', $clausulas);
        $this->data['titulo'] = 'Nova Cidade';
        $this->layout->view('restrita/cidades/cadastrar', $this->data);
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
        date_default_timezone_set("America/Sao_Paulo");

        $this->data['cidade'] = $this->superModel->select('cidade', $clausulas);
        $clausulas = array();
        $this->data['estados'] = $this->superModel->select('estado', $clausulas);
        $this->data['titulo'] = 'Editar cidade';
        $this->layout->view('restrita/cidades/editar', $this->data);
    }

    public function alterar() {
        if($this->form_validation->run('restrita/cidades/inserir')) {
            $condicoes = array(
                array(
                    'campo' => 'id',
                    'valor' => $this->input->post('id'),
                )
            );

            $data = array(
                'id' => $this->input->post('id'),
                'nome' => $this->input->post('nome'),
                'link' => $this->input->post('link'),
                'estado' => $this->input->post('estado'),
                'status' => $this->input->post('status')
            );

            $this->superModel->update('cidade', $data, $condicoes);

            $this->session->set_flashdata('msg', $this->lang->line('cadastro_atualizado'));
            redirect('restrita/cidades/listar');
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
            redirect('restrita/cidades/listar');
        }
    }

	public function listar() {
        
        $clausulas = array (
            'select' => 'cidade.id, cidade.nome, estado.nome as estado, cidade.link, cidade.status',
            'join' => array(
                array(
                    'tabela' => 'estado',
                    'on' => 'cidade.estado = estado.id'
                )
            )
        );

        $this->data['cidades'] = $this->superModel->select('cidade', $clausulas);
        $this->data['titulo'] = 'Cidades';

		$this->layout->view('restrita/cidades/listar', $this->data);
	}
}
