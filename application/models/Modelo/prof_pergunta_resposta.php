<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Prof_pergunta_resposta extends MY_Model {

    public $id_prof_pergunta_resposta;
    public $id_prof_subcateg;
    public $id_prof_pergunta;
    public $id_prof_enunciado;
    public $vlr_primeiro;
    public $vlr_adicional;
    public $vlr_porcent;
    public $qntd;
    public $checkbox;
    public $faz_servico;
    public $respondido;
    public $sn_pula_proxima_pergunta;
    public $sn_pula_categoria;
    public $sn_vlr_visita;
    public $sn_preco_por_qntde;
    public $sn_qntd_por_add;
    public $sinal;
    public $sn_vlr_sinal;
    public $sn_vlr_sinal_todos_categ;
    public $ds_servico;
    public $ds_servico_remetente;
    public $sn_vlr_sinal_off;
    public $valor_sinal_off;
    public $tipo;

    public function  __construct() {
        parent::__construct();
    }

    public function insert($data)
    {
        $this->db->insert('prof_pergunta_resposta', $data);

    }

    public function update($data, $where)
    {
        $this->db->update('prof_pergunta_resposta', $data, $where);
    }

}