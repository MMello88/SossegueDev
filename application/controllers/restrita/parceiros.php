<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Parceiros extends MY_Restrita {
    public function __construct() {
		parent::__construct();
    }

    public function cadastrar() {
        $this->data['titulo'] = 'Nova parceiro';
        $this->layout->view('restrita/parceiros/cadastrar', $this->data);
    }

    public function editar($id) {
        $clausulas = array(
            'row' => true,
            'condicoes' => array(
                array(
                    'campo' => 'id_parceiro',
                    'valor' => $id
                )
            )
        );

        $this->data['parceiro'] = $this->superModel->select('parceiros', $clausulas);
        $this->data['titulo'] = 'Editar parceiro';
        $this->layout->view('restrita/parceiros/editar', $this->data);
    }

    public function alterar() {
        if($this->form_validation->run('restrita/parceiros/inserir')) {
            $foto = $this->input->post('fotoAntiga');

            if(!$_FILES['fotoparceiro']['error']) {
                $url = $this->superModel->getUrlAmigavel($this->input->post('nome'), 'tbl_parceiros', 'foto', 'id_parceiro', $this->input->post('id_parceiro'));

                unlink(DIR_PARCEIRO . $foto);

                $data = array(
                    'dir' => './uploads/parceiros/',
                    'fileName' => $url,
                    'inputName' => 'fotoparceiro',
                );

                $foto = $this->realizaUpload($data, true);
            }

            $condicoes = array(
                array(
                    'campo' => 'id_parceiro',
                    'valor' => $this->input->post('id_parceiro'),
                )
            );

            $data = array(
                'parceiro' => $this->input->post('parceiro'),
                'link' => $this->input->post('link'),
                'foto' => $foto
            );

            $this->superModel->update('parceiros', $data, $condicoes);
            $this->session->set_flashdata('msg', $this->lang->line('cadastro_atualizado'));
            redirect('restrita/parceiros');
        }
    }

    public function inserir() {
        if($this->form_validation->run('restrita/parceiros/inserir')) {
            $foto = '';

            if(!$_FILES['fotoparceiro']['error']) {
                $url = $this->superModel->getUrlAmigavel($this->input->post('parceiro'), 'tbl_parceiros', 'foto');

                $data = array(
                    'dir' => './uploads/parceiros/',
                    'fileName' => $url,
                    'inputName' => 'fotoparceiro'
                );

                $foto = $this->realizaUpload($data, true);
            }

            $this->superModel->insert('parceiros', array(
                'parceiro' => $this->input->post('parceiro'),
                'link' => $this->input->post('link'),
                'foto' => $foto
            ));

            $this->session->set_flashdata('msg', $this->lang->line('cadastro_inserido'));
            redirect('restrita/parceiros');
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

        $this->data['parceiros'] = $this->superModel->select('parceiros', $clausulas);
        $this->data['titulo'] = 'Parceiros';

		$this->layout->view('restrita/parceiros/listar', $this->data);
	}
}
