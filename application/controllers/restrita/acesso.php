<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Acesso extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('restritaModel');

		if($this->session->userdata('logado') && $this->uri->segment(3) !== 'login') {
			redirect('restrita/home');
        }
	}

	public function index() {
		redirect('login');
	}

	public function login() {
		$this->restritaModel->login();
	}
}
