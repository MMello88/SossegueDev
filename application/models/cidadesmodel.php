<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CidadesModel extends CI_Model {

    public function  __construct() {
        parent::__construct();
    }

    public function getEstado(){

        $estado = $this->db->select('estado.nome AS estado')
                           ->get('estado')
                           ->result();
        return $estado;

    }

	public function getCidades(){
		
            $cidade = $this->db->select('cidade.nome AS cidade')
                         ->join('cidade', 'cidade.id = usuario_endereco.id_cidade')
                         ->where('id_usuario', $idUsuario)
                         ->get('usuario_endereco')
                         ->row();

			return $cidade;
	}

}