<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class CategoriaServico
{
    var $obj;
    var $menu;

    function __construct()
    {
        $this->obj =& get_instance();
    }
    
    public function getCategoriaServico($idCategoria, $idSubcategoria){
        $data = array (
            'select' => 'id_categoria_servico, descricao, imagem, pergunta_1',
            'condicoes' => array (
                array (
                    'campo' => 'status',
                    'valor' => 'a'
                ),
                array (
                    'campo' => 'id_categoria',
                    'valor' => $idCategoria
                ),
                array (
                    'campo' => 'id_subcategoria',
                    'valor' => $idSubcategoria
                )
            )
        );

        return $this->obj->superModel->select('categoria_servico', $data );
    }
}