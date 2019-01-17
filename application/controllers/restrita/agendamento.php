<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agendamento extends MY_Restrita {
    public function __construct() {
        parent::__construct();
    }


    public function listar() {

        $clausulas = array (
                    'select' => 'agendamento.id_agendamento AS id_agendamento, agendamento.nome AS nome_cli, agendamento.email AS email_cli, agendamento.celular AS celular_cli, 
                                 agendamento.id_subcategoria AS subcategoria_cli, agendamento.bairro AS bairro_cli, agendamento.descricao AS descricao, 
                                DATE_FORMAT(data_de, "%d/%m/%Y") as data_de, agendamento.horario AS horario,  
                                DATE_FORMAT(data_agendamento, "%d/%m/%Y %H:%i:%s") AS data_agendamento, 
                                categoria.id_categoria AS cat_id, categoria.categoria AS cat_nome, 
                                subcategoria.id_subcategoria AS sub_id, subcategoria.subcategoria AS sub_nome, subcategoria.id_categoria AS sub_id_cat', 
                'join' => array(
                    array(
                        'tabela' => 'subcategoria', 
                        'on'     => 'agendamento.id_subcategoria = subcategoria.id_subcategoria'
                    ),
                    array(
                        'tabela' => 'categoria', 
                        'on'     => 'subcategoria.id_categoria = categoria.id_categoria'
                    )
                ),
            );

        $this->data['listaAgendamento'] = $this->superModel->select('agendamento', $clausulas);
        $this->data['titulo'] = 'Agendamento';

        $this->layout->view('restrita/agendamento/listar', $this->data);
    }

	public function confirmacao_pedido(){
		$this->data['titulo'] = 'Teste de URL';
		$this->layout->view('confirmacao-pedido', $this->data);
	}
}
