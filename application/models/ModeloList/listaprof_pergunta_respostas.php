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

	public function getResposta($id_prof_subcateg, $id_prof_enunciado, $id_prof_pergunta) {
        $query = $this->_instance->db->get_where('prof_pergunta_resposta', 
        	array('id_prof_subcateg'  => $id_prof_subcateg,
        		  'id_prof_pergunta'  => $id_prof_pergunta,
        		  'id_prof_enunciado' => $id_prof_enunciado));
        $rows = $query->custom_result_object('prof_pergunta_resposta');
		return empty($rows[0]) ? null : $rows[0];
    }
	
    public function BuscaPorProf_pergunta_respostas( $id_prof_subcateg, $id_prof_pergunta, $id_prof_enunciado) {
        $query = $this->_instance->db->get_where('prof_pergunta_resposta', 
        	array('id_prof_subcateg'  => $id_prof_subcateg,
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

             . " AND r.checkbox = 'S'"

             . " AND r.sinal IN ('=','<','> ')"
             . " AND r.id_prof_subcateg = {$Prof_pergunta_resposta->id_prof_subcateg}";
        $query = $this->_instance->db->query($sql);
        $sinal = "=";
        if(isset($query->result_object()[0]->sinal))
            $sinal = $query->result_object()[0]->sinal;

        
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
             . "      r.sinal, "
             . "      r.sn_vlr_sinal, "
             . "      r.tipo "
             . " FROM tbl_prof_enunciado e "
             . " LEFT JOIN tbl_prof_pergunta p ON (e.id_prof_enunciado = p.id_prof_enunciado) "
             . " LEFT JOIN tbl_prof_pergunta_resposta r ON (r.id_prof_pergunta = p.id_prof_pergunta AND r.id_prof_enunciado =  e.id_prof_enunciado) "
             . " WHERE e.id_subcategoria = {$id_subcategoria} "
             . "  AND r.sinal NOT IN ('=','<','>') "
             . "  AND r.sn_vlr_sinal NOT IN ('S','N') "

             . "  AND r.id_prof_subcateg = {$Prof_pergunta_resposta->id_prof_subcateg} " ;
        $this->_instance->db->query($sql);
        $query = $this->_instance->db->query($sql);
    
        return $query->custom_result_object('prof_pergunta_resposta');
    }
}
