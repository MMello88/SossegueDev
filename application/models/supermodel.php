<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class superModel extends CI_Model {
    public function  __construct() {
        parent::__construct();
    }

    public function salvaLog($texto, $dados = array()) {
        $this->load->library('user_agent');

        $data = array(
            'os' => $this->agent->platform(),
            'agent_string' => $this->agent->agent_string(),
            'texto' => $texto,
            'pagina' => $this->uri->uri_string(),
            'post' => json_encode($dados ? $dados : $this->input->post()),
            'data' => now(),
            'ip' => $this->input->ip_address()
        );

        $this->db->insert('log', $data);
    }

    public function insert($tabela, $data) {
        $this->db->insert($tabela, $data);
        $id = $this->db->insert_id();

        if($this->session->userdata('email')) {
            $data['usuario'] = $this->session->userdata('email');
        }

        $this->salvaLog('Novo registro', $data);

        return $id;
    }

    public function getRow($tabela) {
        return $this->db->get($tabela)->row();
    }

    public function delete($tabela, $data) {
        $registro = array(
            $data['campo'] => $data['valor']
        );

        $this->db->delete($tabela, $registro);
    }

    public function update($tabela, $data, $condicoes) {
        foreach($condicoes as $condicao) {
            $this->db->where($condicao['campo'] . (isset($condicao['sinal']) ? ' ' . $condicao['sinal'] : ' = ' ), $condicao['valor']);
        }

        $this->db->update($tabela, $data);

        if($this->session->userdata('email')) {
            $data['usuario'] = $this->session->userdata('email');
        }

        $this->salvaLog('Atualização de registro', $data);
    }

    public function query($query){
        $resultado = $this->db->query($query);
        if (method_exists($resultado , 'result' ))
          return $resultado->result();
    }
    
    public function select($tabela, $clausulas = array()) {
        if(isset($clausulas['select'])) {
            $this->db->select($clausulas['select'], false);
        }

        if(isset($clausulas['join'])) {
            foreach($clausulas['join'] as $join) {
                $this->db->join($join['tabela'], $join['on'], isset($join['option']) ? $join['option'] : 'inner');
            }
        }
        
        if(isset($clausulas['condicoes'])) {
            foreach($clausulas['condicoes'] as $condicao) {
                if(!isset($condicao['like'])) {
                    $condicoes = $condicao['campo'] . (isset($condicao['sinal']) ? ' ' . $condicao['sinal'] . ' ' : ' = ' );

                    if(!isset($condicao['or'])) {
                        $this->db->where($condicoes, $condicao['valor'], isset($condicao['escape']) ? false : true);
                    } else {
                        $this->db->or_where($condicoes, $condicao['valor'], isset($condicao['escape']) ? false : true);
                    }
                } else {
                    if(!isset($condicao['or'])) {
                        $this->db->like($condicao['campo'], $condicao['valor'], $condicao['like'], isset($condicao['escape']) ? false : true);
                    } else {
                        $this->db->or_like($condicao['campo'], $condicao['valor'], $condicao['like'], isset($condicao['escape']) ? false : true);
                    }
                }
            }
        }

        if(isset($clausulas['order'])) {
            foreach($clausulas['order'] as $order) {
                $this->db->order_by($order['campo'], isset($order['valor']) ? $order['valor'] : 'ASC');
            }
        }

        if(isset($clausulas['group'])) {
            $this->db->group_by($clausulas['group']);
        }

        $resultado = !isset($clausulas['limit']) ? $this->db->get($tabela) : $this->db->get($tabela, $clausulas['limit']['qtde'], $clausulas['limit']['page']);

        return (isset($clausulas['row']) ? $resultado->row() : $resultado->result());
    }

    public function getUrlAmigavel($x, $tabela = '', $campo = '', $campoCodigo = false, $codigo = false) {
        $x = trim($x);
		$x = str_replace("("," ",$x);
		$x = str_replace("&","e",$x);
		$x = html_entity_decode( $x);
		$x = removeAcentos($x);
		$x = preg_replace( '/\s|\'|\"/', '-', $x);
		preg_match_all('/[a-z]|\-[a-z]|[0-9]/i',$x,$Re);
		$x = implode('',$Re[0]);
		$x = strtolower( $x);
		$x = print_r( $x, true);

        if($tabela !== '' && $campo !== '') {
            if($campoCodigo !== false && $codigo !== false) {
                $resultado = $this->db->query("SELECT count(*) as total FROM " . $tabela . " WHERE " . $campo . " = '" . $x . "' AND " . $campoCodigo . " != '" . $codigo . "'");
            } else {
                $resultado = $this->db->query("SELECT count(*) as total FROM " . $tabela . " WHERE " . $campo . " = '" . $x . "'");
            }

            $resultado = $resultado->row();

            if($resultado->total > 0) {
                $x = $x."-".date('dmyHi');
            }
        }

        return $x;
    }

    public function count($tabela, $data = array()) {
        if($data['condicoes']) {
            foreach($data['condicoes'] as $condicao) {
                $this->db->where($condicao['campo'], $condicao['valor']);
            }
        }

        if(isset($data['join'])) {
            foreach($data['join'] as $join) {
                $this->db->join($join['tabela'], $join['on']);
            }
        }

        return $this->db->count_all_results($tabela);
    }
}
?>
