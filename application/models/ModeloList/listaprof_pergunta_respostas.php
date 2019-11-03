<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
require_once(APPPATH."models/Modelo/prof_pergunta_resposta.php");
 
class ListaProf_pergunta_respostas extends Control {

    public function  __construct() {
        parent::__construct($this);
    }

    public function get() {
    }

    public function getProf_pergunta_respostasByProf_enunciado($id_prof_subcateg, $id_prof_enunciado) {
        //echo $id_prof_subcateg.','.$id_prof_enunciado;
        $query = $this->_instance->db->get_where('prof_pergunta_resposta', array('id_prof_subcateg' => $id_prof_subcateg, 'id_prof_enunciado' => $id_prof_enunciado));
        $row = $query->custom_result_object('prof_pergunta_resposta');
        //print_r($row);
        return $row;
    }

    public function BuscaPorProf_pergunta_respostas($ds_servico, $id_prof_subcateg, $id_prof_pergunta, $id_prof_enunciado) {
        $query = $this->_instance->db->get_where('prof_pergunta_resposta', 
        	array('ds_servico' => $ds_servico, 
        		  'id_prof_subcateg'  => $id_prof_subcateg,
        		  'id_prof_pergunta'  => $id_prof_pergunta,
        		  'id_prof_enunciado' => $id_prof_enunciado));
        //print_r($query);
        return $query->custom_result_object('prof_pergunta_resposta');
    }    

    public function ListaValoresRespondido($Prof_pergunta_resposta, $id_subcategoria) {
        $_porcent = ($Prof_pergunta_resposta->vlr_porcent/100);
        $sql = "SELECT r.sinal"
             . " FROM tbl_prof_enunciado e"
             . " LEFT JOIN tbl_prof_pergunta p ON (e.id_prof_enunciado = p.id_prof_enunciado)"
             . " LEFT JOIN tbl_prof_pergunta_resposta r ON (r.id_prof_pergunta = p.id_prof_pergunta AND r.id_prof_enunciado = e.id_prof_enunciado)"
             . " WHERE e.id_subcategoria = {$id_subcategoria}"
             . " AND p.ds_servico_remetente = '{$Prof_pergunta_resposta->ds_servico_remetente}'"
             . " AND r.checkbox = 'S'"
             . " AND r.ds_servico = p.ds_servico"
             . " AND r.sinal IN ('=','<','> ')"
             . " AND r.id_prof_subcateg = {$Prof_pergunta_resposta->id_prof_subcateg}";
        $query = $this->_instance->db->query($sql);
        $sinal = $query->result()[0]->sinal;

        if ($Prof_pergunta_resposta->sn_vlr_sinal_todos_categ  == 'S'){
            $sql = "SELECT A.*, B.descricao AS ds_servico_remetente FROM (SELECT r.id_prof_pergunta_resposta, "
                 . "      r.id_prof_subcateg, "
                 . "      r.id_prof_pergunta, "
                 . "      r.id_prof_enunciado, "
                 . "      CASE "
                 . "          WHEN '{$sinal}' = '>' THEN r.vlr_primeiro + (r.vlr_primeiro * 0.10)  "
                 . "          WHEN '{$sinal}' = '<' THEN r.vlr_primeiro - (r.vlr_primeiro * 0.10)  "
                 . "          ELSE r.vlr_primeiro "
                 . "      END vlr_primeiro, "
                 . "      r.vlr_adicional, "
                 . "      r.vlr_porcent, "
                 . "      r.qntd, "
                 . "      r.checkbox, "
                 . "      r.faz_servico, "
                 . "      r.respondido, "
                 . "      r.sn_pula_proxima_pergunta, "
                 . "      r.sn_pula_categoria, "
                 . "      r.sn_vlr_visita, "
                 . "      r.sn_preco_por_qntde, "
                 . "      r.sn_qntd_por_add, "
                 . "      r.sinal, "
                 . "      r.sn_vlr_sinal, "
                 . "      '{$Prof_pergunta_resposta->ds_servico_remetente}' ds_servico "
                 . " FROM tbl_prof_enunciado e "
                 . " LEFT JOIN tbl_prof_pergunta p ON (e.id_prof_enunciado = p.id_prof_enunciado) "
                 . " LEFT JOIN tbl_prof_pergunta_resposta r ON (r.id_prof_pergunta = p.id_prof_pergunta AND r.id_prof_enunciado =  e.id_prof_enunciado) "
                 . " WHERE e.id_subcategoria = {$id_subcategoria} "
                 . "  AND p.ds_servico = '{$Prof_pergunta_resposta->ds_servico}' "
                 . "  AND r.sinal NOT IN ('=','<','>') "
                 . "  AND r.sn_vlr_sinal NOT IN ('S','N') "
                 . "  AND r.ds_servico = p.ds_servico "
                 . "  AND r.id_prof_subcateg = {$Prof_pergunta_resposta->id_prof_subcateg}) A, " 
                 . "  (SELECT  s.descricao "
                 . "     FROM tbl_categoria_servico c "
                 . "     LEFT JOIN tbl_servico s ON (s.id_categoria_servico = c.id_categoria_servico) "
                 . "    WHERE c.id_subcategoria = {$id_subcategoria} "
                 . "      AND c.id_categoria = 1  "
                 . "      GROUP BY s.descricao) B ";
            $this->_instance->db->query($sql);
            $query = $this->_instance->db->query($sql);
        } else {
            $sql = "SELECT r.id_prof_pergunta_resposta, "
                 . "      r.id_prof_subcateg, "
                 . "      r.id_prof_pergunta, "
                 . "      r.id_prof_enunciado, "
                 . "      CASE "
                 . "          WHEN '{$sinal}' = '>' THEN r.vlr_primeiro + (r.vlr_primeiro * 0.10)  "
                 . "          WHEN '{$sinal}' = '<' THEN r.vlr_primeiro - (r.vlr_primeiro * 0.10)  "
                 . "          ELSE r.vlr_primeiro "
                 . "      END vlr_primeiro, "
                 . "      r.vlr_adicional, "
                 . "      r.vlr_porcent, "
                 . "      r.qntd, "
                 . "      r.checkbox, "
                 . "      r.faz_servico, "
                 . "      r.respondido, "
                 . "      r.sn_pula_proxima_pergunta, "
                 . "      r.sn_pula_categoria, "
                 . "      r.sn_vlr_visita, "
                 . "      r.sn_preco_por_qntde, "
                 . "      r.sn_qntd_por_add, "
                 . "      r.sinal, "
                 . "      r.sn_vlr_sinal, "
                 . "      '{$Prof_pergunta_resposta->ds_servico_remetente}' ds_servico, "
                 . "      r.ds_servico as ds_servico_remetente "
                 . " FROM tbl_prof_enunciado e "
                 . " LEFT JOIN tbl_prof_pergunta p ON (e.id_prof_enunciado = p.id_prof_enunciado) "
                 . " LEFT JOIN tbl_prof_pergunta_resposta r ON (r.id_prof_pergunta = p.id_prof_pergunta AND r.id_prof_enunciado =  e.id_prof_enunciado) "
                 . " WHERE e.id_subcategoria = {$id_subcategoria} "
                 . "  AND p.ds_servico = '{$Prof_pergunta_resposta->ds_servico}' "
                 . "  AND r.sinal NOT IN ('=','<','>') "
                 . "  AND r.sn_vlr_sinal NOT IN ('S','N') "
                 . "  AND r.ds_servico = p.ds_servico "
                 . "  AND r.id_prof_subcateg = {$Prof_pergunta_resposta->id_prof_subcateg} " ;
            $this->_instance->db->query($sql);
            $query = $this->_instance->db->query($sql);
        }
        return $query->custom_result_object('prof_pergunta_resposta');
    }

    public function ListaValoresRespondidoOff($id_prof_subcateg, $id_subcategoria, $ds_servico_remetente, $ds_servico, $vlr_porcent, $sinal) {
        $_porcent = ($vlr_porcent/100);
        $sql = "SELECT r.id_prof_pergunta_resposta, "
             . "      r.id_prof_subcateg, "
             . "      r.id_prof_pergunta, "
             . "      r.id_prof_enunciado, "
             . "      CASE "
             . "          WHEN '{$sinal}' = '>' THEN r.vlr_primeiro + (r.vlr_primeiro * 0.10)  "
             . "          WHEN '{$sinal}' = '<' THEN r.vlr_primeiro - (r.vlr_primeiro * 0.10)  "
             . "          ELSE r.vlr_primeiro "
             . "      END vlr_primeiro, "
             . "      r.vlr_adicional, "
             . "      r.vlr_porcent, "
             . "      r.qntd, "
             . "      r.checkbox, "
             . "      r.faz_servico, "
             . "      r.respondido, "
             . "      r.sn_pula_proxima_pergunta, "
             . "      r.sn_pula_categoria, "
             . "      r.sn_vlr_visita, "
             . "      r.sn_preco_por_qntde, "
             . "      r.sn_qntd_por_add, "
             . "      r.sinal, "
             . "      r.sn_vlr_sinal, "
             . "      '{$ds_servico_remetente}' ds_servico, "
             . "      r.ds_servico as ds_servico_remetente "
             . " FROM tbl_prof_enunciado e "
             . " LEFT JOIN tbl_prof_pergunta p ON (e.id_prof_enunciado = p.id_prof_enunciado) "
             . " LEFT JOIN tbl_prof_pergunta_resposta r ON (r.id_prof_pergunta = p.id_prof_pergunta AND r.id_prof_enunciado =  e.id_prof_enunciado) "
             . " WHERE e.id_subcategoria = {$id_subcategoria} "
             . "  AND p.ds_servico = '{$ds_servico}' "
             . "  AND r.sinal NOT IN ('=','<','>') "
             . "  AND r.sn_vlr_sinal NOT IN ('S','N') "
             . "  AND r.ds_servico = p.ds_servico "
             . "  AND r.id_prof_subcateg = {$id_prof_subcateg} " ;
             //echo $sql;
        $this->_instance->db->query($sql);
        $query = $this->_instance->db->query($sql);
        return $query->custom_result_object('prof_pergunta_resposta');
    }
}
