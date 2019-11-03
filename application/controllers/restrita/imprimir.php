
<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Imprimir extends MY_Restrita {
	public function index() {
		$this->layout->view('restrita/imprimir', $this->data);
	}
}
