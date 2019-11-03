<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agenda extends MY_Restrita {
	public function index() {
		$this->layout->view('restrita/agendaVisitas', $this->data);
	}
}
