<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."models/Modelo/prof_subcateg.php");
 
class ListaProf_subcategs extends Control {

    public function  __construct() {
        parent::__construct($this);
    }

    public function get($id_prof_subcateg = '') {
        $query = $this->_instance->db->get_where('prof_subcateg', array('id_prof_subcateg' => $id_prof_subcateg));
        if (empty($query))
            $this->set_log_error_db();
        return $query->custom_row_object(0, 'prof_subcateg');
    }
 
    public function get_all(){
        $query = $this->_instance->db->get('prof_subcateg');
        if (empty($query))
            $this->set_log_error_db();
        return $query->custom_result_object('prof_subcateg');
    }

    public function getProf_subcategBySubcategoria($id_subcategoria = '') {
        $query = $this->_instance->db->get_where('prof_subcateg', array('id_subcategoria' => $id_subcategoria));
        return $query->custom_row_object(0, 'prof_subcateg');
    }

    public function getProf_subcategByProfissional($id_profissional = '') {
        $query = $this->_instance->db->get_where('prof_subcateg', array('id_profissional' => $id_profissional,'STATUS'=>'a'));
        return $query->custom_result_object('prof_subcateg');
    }
	
	public function getProfSubcatByProfAndSubcatg($id_profissional, $id_subcategoria = ''){
		if(empty($id_subcategoria)){
			$query = $this->_instance
				->db
				->order_by('id_subcategoria','ASC')
				->get_where('prof_subcateg', array('id_profissional' => $id_profissional,'STATUS' => 'a'));
			return $query->custom_result_object('prof_subcateg');
		} else {
			$query = $this->_instance
				->db
				->order_by('id_subcategoria','ASC')
				->get_where('prof_subcateg', 
								array('id_profissional' => $id_profissional,
									  'id_subcategoria' => $id_subcategoria,
									  'STATUS'=>'a'));
			$rows = $query->custom_result_object('prof_subcateg');
			return $rows[0];
		}
	}
}
