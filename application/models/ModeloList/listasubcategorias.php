<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
require_once(APPPATH."models/Modelo/subcategoria.php");
 
class ListaSubcategorias extends Control {

    public function  __construct() {
        parent::__construct($this);
    }

    public function get($id_subcategoria = '') {
        $query = $this->_instance->db->get_where('subcategoria', array('id_subcategoria' => $id_subcategoria, 'status' => 'a'));
        if (empty($query))
            $this->set_log_error_db();
        return $query->custom_result_object('subcategoria');
    }
 
    public function getSubcategoriaByCategoria($id_categoria = '') {
        $query = $this->_instance->db->get_where('subcategoria', array('id_categoria' => $id_categoria, 'status' => 'a'));
        return $query->custom_row_object('subcategoria');
    }

    public function getSubCategoriaByUsuarioCheckBox($id_usuario) {
        $sql = "SELECT IFNULL(ps.id_prof_subcateg,0) id_prof_subcateg, " .
               "       IF(ps.status = 'a','checked', '') status, " .
               "       sc.id_subcategoria, " .
               "       sc.subcategoria, " .
               "       sc.id_profissional " .
               "  FROM tbl_prof_subcateg ps " .
               " RIGHT JOIN (SELECT s.id_subcategoria, " .
               "                    s.subcategoria, " .
               "                    (SELECT p.id_profissional " .
               "                       FROM tbl_profissional p " .
               "                      WHERE p.id_usuario = 28) id_profissional " .
               "         FROM tbl_subcategoria s " .
               "        WHERE s.id_categoria = 1 " .
               "          AND s.status = 'a') sc " .
               "       ON (sc.id_subcategoria = ps.id_subcategoria  " .
               "       AND sc.id_profissional = ps.id_profissional) " ;
        $query = $this->_instance->db->query($sql, array($id_usuario, $id_usuario, $id_usuario));
        return $query->result();
    }
}
