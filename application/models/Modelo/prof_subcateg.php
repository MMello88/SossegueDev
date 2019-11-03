<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Prof_subcateg extends MY_Model {

    public $id_prof_subcateg;
    public $id_subcategoria;
    public $id_profissional;
    public $status;

    public function  __construct() {
        parent::__construct($this);
    }

    public function insert() {
        $Ids = "";
        $this->session->set_userdata('id_profissional', $this->id_profissional);
        
        /*$this->session->set_userdata('OrdemEnunciado', '1');
        $this->session->set_userdata('subcategAndado', '');*/

        foreach ($this->id_subcategoria as $value) {
            list($id_prof_subcateg, $id_subcategoria, $count) = explode("/", $value, 3);
            if ($id_prof_subcateg == 0){
                $sql = "insert into tbl_prof_subcateg values (null, {$id_subcategoria}, {$this->id_profissional}, 'a')";
                $this->db->query($sql);
                $this->id_prof_subcateg = $this->db->insert_id();
                if (empty($this->id_prof_subcateg))
                  $this->set_log_error_db();
            } else {
                $Ids .= $id_prof_subcateg . ',';
            }
        }
        if ($Ids != "") {
            $Ids = substr($Ids, 0, -1);
            $this->update($Ids, $this->id_profissional);
        }
        
        $this->set_response_db('Incluido com sucesso');
    }

    private function update($Ids, $IDProf) {
        $sql = "update tbl_prof_subcateg set status = 'a' where id_prof_subcateg in ({$Ids}) and id_profissional = {$IDProf}";
        $this->db->query($sql);
        if ($this->db->_error_number() > 0)
          $this->set_log_error_db();
      
        $sql = "update tbl_prof_subcateg set status = 'd' where id_prof_subcateg not in ({$Ids}) and id_profissional = {$IDProf}";
        $this->db->query($sql);
        if ($this->db->_error_number() > 0)
          $this->set_log_error_db();
    }
}
