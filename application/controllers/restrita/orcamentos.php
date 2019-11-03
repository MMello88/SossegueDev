
<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cadastro extends MY_Restrita {
	public function index() {
		$this->layout->view('restrita/orcamento', $this->data);
	}
}
