<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subcategoria extends MY_Model {

    public $id_subcategoria;
    public $id_categoria;
    public $subcategoria;
    public $status;

    public function  __construct() {
        parent::__construct($this);
    }

    public function insert() {
        $this->id_subcategoria = null;
        if ($this->db->insert('subcategoria', $this))
            $this->id_subcategoria = $this->db->insert_id();
        if (empty($this->id_subcategoria))
          $this->set_log_error_db();
        $this->set_response_db('Incluido com sucesso');
    }

    public function update() {
        $this->db->update('subcategoria', $this, array('id_subcategoria' => $this->id_subcategoria));
        if ($this->db->error()['code'] > 0)
          $this->set_log_error_db();
        $this->set_response_db('Alteração concluída com sucesso');
    }

    public function delete() {
        $this->db->delete('subcategoria', $this, array('id_subcategoria' => $this->id_subcategoria));
        if ($this->db->error()['code'] > 0)
          $this->set_log_error_db();
        $this->set_response_db('Removido com sucesso');
    }

    protected function valida_form(){
        return true;//$this->form_validation->run('pedidos/realizar');
    }

    private function error(){
        $this->form_validation->error('field_name');
    }
}
