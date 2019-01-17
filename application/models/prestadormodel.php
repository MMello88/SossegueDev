<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class PrestadorModel extends CI_Model {

    public function  __construct() {
        parent::__construct();
    }


	public function addUsuario(){


	    $usuario = array(
			'id_tipo_usuario'     	=> 1,
			'nome'               	=> $this->input->post('nome'),
			'email'              	=> $this->input->post('email'),
			'telefone'            	=> $this->input->post('telefone'),
			'celular'			  	=> $this->input->post('celular'),
			'senha' 			  	=> Bcrypt::hash($this->input->post('senha')),
            'cpf' 					=> $this->input->post('cpf'),
            'nascimento' 			=> convertData($this->input->post('nascimento')),
            'telefone' 				=> $this->input->post('telefone'),
            'celular' 				=> $this->input->post('celular'),
            'id_cidade' 			=> $this->input->post('id_cidade'),
            'id_subcategoria' 		=> $this->input->post('id_subcategoria'),
            'url' 					=> $this->input->post('url'),
            'id_planos' 			=> $this->input->post('id_planos'),
            'status' 				=> 'a',
            'codigo_confirmacao' 	=> tokenGenerate(32),
            'confirmado_expiracao' 	=> date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . " + $diasConfirmacao days"))
	    );


	    $query = $this->db->insert('prestador', $usuario);

        return $query;
    }

    public function getUsuario($idUsuario){

		$usuario = $this->db
			            ->where('id_prestador', $idUsuario)
			            ->get('prestador')
			            ->row();

		return $usuario;
    }

}
