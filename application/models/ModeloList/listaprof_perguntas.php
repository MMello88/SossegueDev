<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
require_once(APPPATH."models/Modelo/prof_pergunta.php");
 
class ListaProf_perguntas extends Control {

    public function  __construct() {
        parent::__construct($this);
    }

    public function get() {
    }

    public function getProf_perguntaByProf_enunciado($id_prof_enunciado = '') {
        $query = $this->_instance->db->get_where('prof_pergunta', array('id_prof_enunciado' => $id_prof_enunciado));
        return $query->custom_result_object('prof_pergunta');
    }
}
