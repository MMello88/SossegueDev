<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Prof_pergunta extends MY_Model {

    public $id_prof_pergunta;
    public $id_prof_enunciado;
    public $pergunta;
    public $perg_ini;
    public $sn_checkbox;
    public $tem_vlr_primeiro;
    public $tem_vlr_adicional;
    public $tem_vlr_procent;
    public $tem_vlr_qntd;
    public $tem_faz_servico;
    public $ordem;
    public $sn_pula_proxima_pergunta;
    public $sn_pula_categoria;
    public $sn_vlr_visita;
    public $sn_preco_por_qntde;
    public $sn_qntd_por_add;
    public $sinal;
    public $sn_vlr_sinal;
    public $ds_servico;
    public $ds_servico_remetente;

    public function  __construct() {
        parent::__construct($this);
    }
}