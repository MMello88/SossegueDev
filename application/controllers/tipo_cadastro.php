<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tipo_Cadastro extends MY_Front {

	public function index(){
		$this->layout->view('tipo-cadastro', $this->data);
	}	
	
}