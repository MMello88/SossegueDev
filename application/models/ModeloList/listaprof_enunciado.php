<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."models/Modelo/prof_enunciado.php");
 
class ListaProf_enunciado extends Control {

    public function  __construct() {
        parent::__construct($this);
    }

    public function get($id_prof_enunciado = '') {
		if (empty($id_prof_enunciado)){
			$query = $this->_instance
				->db
				->order_by('ordem','ASC')
				->get_where('prof_enunciado');
			$result = $query->custom_result_object('prof_enunciado');
			return $result;
		} else {
			$query = $this->_instance
				->db
				->order_by('ordem','ASC')
				->get_where('prof_enunciado', array('id_prof_enunciado' => $id_prof_enunciado));
			$result = $query->custom_result_object('prof_enunciado');
			return $result[0];
		}
        
    }

    public function getProf_enunciadoBySubcategoria($id_subcategoria, $ordem = '') {
		if (empty($ordem)){
			$query = $this->_instance
				->db
				->order_by('ordem','ASC')
				->get_where('prof_enunciado', array('id_subcategoria' => $id_subcategoria));
			$result = $query->custom_result_object('prof_enunciado');
			return $result;
		} else {
			$query = $this->_instance
				->db
				->order_by('ordem','ASC')
				->get_where('prof_enunciado', array('id_subcategoria' => $id_subcategoria, 'ordem' => $ordem));
			$result = $query->result();
			return $result[0];
		}
    }
}
