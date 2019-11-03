
<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orcamento extends MY_Restrita {
	public function index() {
		$this->layout->view('restrita/orcamentos', $this->data);
	}
}
