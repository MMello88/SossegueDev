<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profsubcateg extends MY_Restrita {

    public function __construct() {
        parent::__construct();
    }

    public function inserir() {
        $this->load->model('Modelo/prof_subcateg');
        $this->prof_subcateg->insert();
    }
}
?>