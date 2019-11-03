<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Erro_404 extends MY_Front {

	public function index(){
		$this->layout->view('404', $this->data);
	}	
	
}