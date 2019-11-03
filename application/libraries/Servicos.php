<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Servicos
{
    var $obj;
    var $menu;

    function __construct()
    {
        $this->obj =& get_instance();
    }
    
    public function getServico($idCategoriaServico){
        $data = array (
            'select' => 'id_servico, descricao',
            'condicoes' => array (
                array (
                    'campo' => 'status',
                    'valor' => 'a'
                ),
                array (
                    'campo' => 'id_categoria_servico',
                    'valor' => $idCategoriaServico
                )
            )
        );

        return $this->obj->superModel->select('servico', $data );
    }
}