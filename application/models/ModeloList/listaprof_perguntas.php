<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."models/Modelo/prof_pergunta.php");
 
class ListaProf_perguntas extends Control {

    public function  __construct() {
        parent::__construct($this);
    }

    public function get() {
    }

    public function getProf_perguntaByProf_enunciado($id_prof_enunciado = '') {
        $query = $this->_instance->db->get_where('prof_pergunta', array('id_prof_enunciado' => $id_prof_enunciado));
        return $query->custom_result_object('prof_pergunta');
    }

    public function getPerguntaResposta($id_prof_enunciado, $id_profissional){
    	$sql = "SELECT 
				  e.id_prof_enunciado, p.id_prof_pergunta, r.id_prof_pergunta_resposta, p.ordem,
				  e.titulo, p.pergunta, p.sn_checkbox, r.vlr_checkbox, p.tem_faz_servico, r.vlr_faz_servico,
				  p.tem_vlr_primeiro, r.vlr_primeiro, p.tem_vlr_adicional, r.vlr_adicional, p.tem_vlr_procent, r.vlr_porcent,
				  p.tem_vlr_qntd, r.vlr_qntd, p.sn_vlr_sinal, r.vlr_sinal, p.tipo
				FROM 
				  tbl_prof_pergunta p
				LEFT JOIN
				  tbl_prof_enunciado e ON (e.id_prof_enunciado = p.id_prof_enunciado)
				LEFT JOIN
				  tbl_prof_pergunta_resposta r ON (p.id_prof_pergunta = r.id_prof_pergunta)
				LEFT JOIN 
				  tbl_prof_subcateg s ON (s.id_prof_subcateg = r.id_prof_subcateg)  
				WHERE 
				  p.id_prof_enunciado = $id_prof_enunciado
				or 
				  s.id_profissional = $id_profissional";
		$query = $this->_instance->db->query($sql);
		return $query->result();
    }
}
