<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PlanosModel extends CI_Model {

    public function  __construct() {
        parent::__construct();
    }

	public function getPlanos($idPlano){

		$plano = $this->db
			->select('id_plano, plano, preco, dias, status, trimestal, semestral, anual')
			->where('id_plano', $id_plano)
			->where('status', 'a')
			->get('planos')
			->result();

		return $plano;

	}
}