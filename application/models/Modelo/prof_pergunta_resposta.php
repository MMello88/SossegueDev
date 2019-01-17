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

    public function insert()
    {
        $sql = "INSERT INTO tbl_prof_pergunta_resposta(id_prof_pergunta_resposta, id_prof_subcateg, id_prof_pergunta,
            id_prof_enunciado, vlr_primeiro, vlr_adicional, vlr_porcent, qntd, checkbox, faz_servico, respondido, 
            sn_pula_proxima_pergunta, sn_pula_categoria, sn_vlr_visita, sn_preco_por_qntde, sn_qntd_por_add, sinal,
            sn_vlr_sinal, sn_vlr_sinal_todos_categ, ds_servico, ds_servico_remetente, sn_vlr_sinal_off, valor_sinal_off, tipo) VALUES (
            {$this->id_prof_pergunta_resposta}, {$this->id_prof_subcateg}, {$this->id_prof_pergunta}, {$this->id_prof_enunciado}, 
            '{$this->vlr_primeiro}', '{$this->vlr_adicional}', '{$this->vlr_porcent}', '{$this->qntd}', '{$this->checkbox}', 
            '{$this->faz_servico}', '{$this->respondido}', '{$this->sn_pula_proxima_pergunta}', '{$this->sn_pula_categoria}', 
            '{$this->sn_vlr_visita}', '{$this->sn_preco_por_qntde}', '{$this->sn_qntd_por_add}', '{$this->sinal}', '{$this->sn_vlr_sinal}', 
            '{$this->sn_vlr_sinal_todos_categ}', '{$this->ds_servico}', '{$this->ds_servico_remetente}', '{$this->sn_vlr_sinal_off}',
            '{$this->valor_sinal_off}', '$this->tipo')";
        $this->db->query($sql);
        if ($this->db->_error_number() > 0)
          $this->set_log_error_db();

    }

    public function update()
    {
        $sql = "UPDATE tbl_prof_pergunta_resposta SET vlr_primeiro = '{$this->vlr_primeiro}', vlr_adicional = '{$this->vlr_adicional}', vlr_porcent = '{$this->vlr_porcent}', qntd = '{$this->qntd}', checkbox = '{$this->checkbox}', faz_servico = '{$this->faz_servico}', 
            sn_preco_por_qntde = '{$this->sn_pula_proxima_pergunta}', sn_pula_categoria = '{$this->sn_pula_categoria}', 
            sn_vlr_visita = '{$this->sn_vlr_visita}', sn_preco_por_qntde = '{$this->sn_preco_por_qntde}', 
            sn_qntd_por_add = '{$this->sn_qntd_por_add}', sinal = '{$this->sinal}', sn_vlr_sinal = '{$this->sn_vlr_sinal}', 
            ds_servico = '{$this->ds_servico}', ds_servico_remetente = '{$this->ds_servico_remetente}', 
            valor_sinal_off = '{$this->valor_sinal_off}', sn_vlr_sinal_off = '{$this->sn_vlr_sinal_off}', 
            sn_vlr_sinal_todos_categ = '{$this->sn_vlr_sinal_todos_categ}', tipo = '$this->tipo'
            WHERE id_prof_pergunta_resposta = {$this->id_prof_pergunta_resposta}";
        $this->db->query($sql);
        if ($this->db->_error_number() > 0)
          $this->set_log_error_db();
    }

}