<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Restrita {
	public function index() {
		$this->layout->view('restrita/home', $this->data);
	}
}
