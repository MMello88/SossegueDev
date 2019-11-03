<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
require_once(APPPATH."models/Modelo/prof_enunciado.php");
 
class ListaProf_enunciado extends Control {

    public function  __construct() {
        parent::__construct($this);
    }

    public function get() {
    }

    public function getProf_enunciadoBySubcategoria($id_subcategoria) {
        $query = $this->_instance->db->order_by('ordem','ASC')->get_where('prof_enunciado', array('id_subcategoria' => $id_subcategoria));
        $result = $query->custom_result_object('prof_enunciado');
        return $result;
    }
}
