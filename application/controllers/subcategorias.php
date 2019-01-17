<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subcategorias extends MY_Front {

	public function index() {
		$this->layout->view('subcategorias', $this->data);
	}
}