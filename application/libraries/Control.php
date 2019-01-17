<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

abstract class Control {

	protected $_instance;
	private $_class;
	private $_message;
	private $_code;

	public function __construct($class_heranca) {
		$this->_instance =& get_instance();
		$this->_class = $class_heranca;
	}

	public function set_log_error_db() {
		$this->_message = $this->_instance->db->_error_message();
		$this->_code    = $this->_instance->db->_error_number();
		$error_log = array(
			'id_error_log_db' => null,
            'error'           => $this->getMessageError(),
            'query'           => $this->_instance->db->queries[0],
            'class'           => get_class($this->_class),
            'dt_erro'         => date('Y-m-d H:i:s')
	       );
		$this->_instance->db->insert('error_log_db',$error_log);
		$this->show_respose_error();
	}

	private function getMessageError(){
		return $this->_code . ' - ' . $this->_message;
	}

	private function show_respose_error(){
		show_error('Em breve o serviço estará disponível novamente.', 500, 'Houve um erro em nosso servidor.');
		//enviar e-mail sobre o erro;
		exit(1);
	}

    abstract protected function get();

}