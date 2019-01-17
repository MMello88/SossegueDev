<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class pedidoModel extends CI_Model {
    public function  __construct() {
        parent::__construct();
    }


    public function getUsuarioCProf($id_prof){

    	$query = $this->db->select('usu.id_usuario, 		usu.nome, 				usu.email, 				usu.telefone, 	
    								usu.celular, 			usu.id_tipo_usuario, 
    								prof.id_profissional, 	prof.id_usuario, 		prof.id_subcategoria, 
    								sub.id_categoria, 		sub.id_subcategoria, 	sub.subcategoria,
    								cat.id_categoria, 		cat.categoria')
		                  ->join('tbl_profissional AS prof', 'usu.id_usuario = prof.id_usuario', 'left')
		                  ->join('tbl_subcategoria AS sub', 'prof.id_subcategoria = sub.id_subcategoria', 'left')
		                  ->join('tbl_categoria    AS cat', 'sub.id_categoria = cat.id_categoria', 'left')
		                  ->where('usu.id_usuario', $id_prof)
		                  ->where('usu.id_tipo_usuario', 1)
		                  ->get('tbl_usuario AS usu');
		return $query;
    }

    public function getUsuarioComum($id_cliente){

    	$id_cliente = $this->session->userdata('id');

    	$query = $this->db->select('usu.id_usuario, 		usu.nome, 				usu.email, 				usu.telefone, 	
    								usu.celular, 			usu.id_tipo_usuario')
		                  ->where('usu.id_usuario', $id_cliente)
		                  ->get('tbl_usuario AS usu');
		return $query;
    }


    public function getLog(){

    	$id_cliente = $this->session->userdata('id');

    	$query = $this->db->select('log.id_log, log.texto, log.pagina, log.post')
    	                  ->where('pagina', 'busca/profissionais')
    	                  ->like('post', '"id_usuario":"'.$id_cliente.'" ')
    	                  ->get('tbl_log AS log');

    	return $query;

    }


}

?>
