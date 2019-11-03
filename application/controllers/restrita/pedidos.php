<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pedidos extends MY_Restrita {

    public function __construct() {
        parent::__construct();
    }

    public function listar() {
        $this->data['script_listar_pedido'] = 1;

        $clausulas = array(
            'select' => 'orcamento.id_orcamento AS id_orcamento, 
                        orcamento.nome AS nome_cli, 
                        orcamento.email AS email_cli, 
                        orcamento.celular AS celular_cli, 
                        orcamento.id_subcategoria AS subcategoria_cli, 
                        cidade.nome As nome_cidade,
                        orcamento.bairro AS bairro_cli, 
                        orcamento.descricao AS descricao,  
                        DATE_FORMAT(data_orcamento, "%d/%m/%Y %H:%i:%s") AS data_orcamento,
                        categoria.id_categoria AS cat_id, categoria.categoria AS cat_nome, 
                        subcategoria.id_subcategoria AS sub_id, subcategoria.subcategoria AS sub_nome, subcategoria.id_categoria AS sub_id_cat',
            'join' => array(
                array(
                    'tabela' => 'subcategoria',
                    'option' => 'left',
                    'on' => 'orcamento.id_subcategoria = subcategoria.id_subcategoria'
                ),
                array(
                    'tabela' => 'categoria',
                    'option' => 'left',
                    'on' => 'subcategoria.id_categoria = categoria.id_categoria'
                ),
                array(
                    'tabela' => 'cidade',
                    'option' => 'left',
                    'on' => 'orcamento.id_cidade = cidade.id'
                )
            ),
        );

        $this->data['listaOrcamento'] = $this->superModel->select('orcamento', $clausulas);
        $this->data['titulo'] = 'Pedidos';

        $this->layout->view('restrita/pedidos/listar', $this->data);
    }

    public function lista_pedidos($id_orcamento) {
        $data = array(
            'select' => 'id_pedido, id_orcamento, id_servico, qntd, '
            . ' CASE status WHEN \'a\' THEN \'Andamento\' '
            . ' WHEN \'f\' THEN \'Finalizado\' '
            . ' WHEN \'e\' THEN \'Excluído\' '
            . ' ELSE \'\' END status',
            'condicoes' => array(
                array(
                    'campo' => 'id_orcamento',
                    'valor' => $id_orcamento
                )
            )
        );

        $pedidos = $this->superModel->select('pedido', $data);

        foreach ($pedidos as $key => $pedido) {
            $sql = "SELECT c.descricao AS pergunta, b.descricao AS filtro, a.valor "
                    . " FROM tbl_pedido_filtro a "
                    . " LEFT JOIN tbl_filtro b ON (b.id_filtro = a.id_filtro) "
                    . " LEFT JOIN tbl_pergunta c ON (c.id_pergunta = b.id_pergunta) "
                    . " WHERE a.id_pedido = $pedido->id_pedido";
            $filtro = $this->superModel->query($sql);

            $pedidos[$key]->filtros = $filtro;

            $data = array(
                'select' => 'id_categoria_servico, descricao',
                'condicoes' => array(
                    array(
                        'campo' => 'id_servico',
                        'valor' => $pedido->id_servico
                    )
                )
            );

            $servico = $this->superModel->select('servico', $data);
            $servico = $servico[0];

            $pedidos[$key]->Servico = $servico;

            $data = array(
                'select' => 'id_categoria_servico, id_subcategoria, descricao, imagem',
                'condicoes' => array(
                    array(
                        'campo' => 'id_categoria_servico',
                        'valor' => $servico->id_categoria_servico
                    )
                )
            );

            $categoria_servico = $this->superModel->select('categoria_servico', $data);
            $categoria_servico = $categoria_servico[0];

            $pedidos[$key]->Servico->categoria = $categoria_servico;

            $data = array(
                'select' => 'subcategoria as descricao',
                'condicoes' => array(
                    array(
                        'campo' => 'id_subcategoria',
                        'valor' => $categoria_servico->id_subcategoria
                    )
                )
            );

            $subcategoria = $this->superModel->select('subcategoria', $data);
            $subcategoria = $subcategoria[0];

            $pedidos[$key]->Servico->categoria->subcategoria = $subcategoria;
        }
        $jsonPedidos = json_encode($pedidos);
        echo $jsonPedidos;
    }

    public function remover_pedido() {
        if ($this->input->post('id_orcamento')) {
            $idOrcamento = $this->input->post('id_orcamento');
            $this->superModel->query("DELETE FROM tbl_pedido_filtro WHERE id_pedido IN (SELECT id_pedido FROM tbl_pedido WHERE id_orcamento = $idOrcamento)");
            $this->superModel->query("DELETE FROM tbl_pedido WHERE id_orcamento = $idOrcamento");
            $this->superModel->query("DELETE FROM tbl_teste_ab WHERE id_orcamento = $idOrcamento");
            $this->superModel->query("DELETE FROM tbl_orcamento WHERE id_orcamento = $idOrcamento");
            redirect('restrita/pedidos/listar');
        }
    }

}
