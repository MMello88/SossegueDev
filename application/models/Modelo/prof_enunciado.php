<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Prof_enunciado extends MY_Model {

    public $id_prof_enunciado;
    public $id_subcategoria;
    public $titulo;
    public $exemplo;
    public $observacao;
    public $ordem;
    public $informativo;

    public function  __construct() {
        parent::__construct($this);
    }
}
