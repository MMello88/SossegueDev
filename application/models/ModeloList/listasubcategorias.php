<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."models/Modelo/subcategoria.php");
 
class ListaSubcategorias extends Control {

    public function  __construct() {
        parent::__construct($this);
    }

    public function get($id_subcategoria = '') {
		if(empty($id_subcategoria)){
			$query = $this->_instance->db->get_where('subcategoria', array('status' => 'a'));
			return $query->custom_result_object('subcategoria');
		} else {
			$query = $this->_instance->db->get_where('subcategoria', array('id_subcategoria' => $id_subcategoria, 'status' => 'a'));
			$result = $query->custom_result_object('subcategoria');
			return $result[0];
		}
    }
 
    public function getSubcategoriaByCategoria($id_categoria = '') {
        $query = $this->_instance->db->get_where('subcategoria', array('id_categoria' => $id_categoria, 'status' => 'a'));
        return $query->custom_row_object('subcategoria');
    }
	
	public function getProfissionalByUsuario($id_usuario){
		$sql = "select p.id_profissional from tbl_profissional p where p.id_usuario = $id_usuario";
		$query = $this->_instance->db->query($sql);
		$prof = $query->row_object(0);
		return $prof;
		//echo $prof->id_profissional;
	}

    public function getSubCategoriaByUsuarioCheckBox($id_profissional) {
        $sql = "SELECT IFNULL(ps.id_prof_subcateg,0) id_prof_subcateg, " .
               "       IF(ps.status = 'a', 'TRUE', '') chacked, " .
               "       sc.id_subcategoria, " .
               "       sc.subcategoria " .
               "  FROM tbl_prof_subcateg ps " .
               " RIGHT JOIN (SELECT s.id_subcategoria, " .
               "                    s.subcategoria " .
               "         FROM tbl_subcategoria s " .
               "        WHERE s.id_categoria = 1 " .
               "          AND s.status = 'a') sc " .
               "       ON (sc.id_subcategoria = ps.id_subcategoria  " .
               "       AND ps.id_profissional = $id_profissional) " ;
			   //echo $sql;
        $query = $this->_instance->db->query($sql);
        return $query->result();
    }
}
