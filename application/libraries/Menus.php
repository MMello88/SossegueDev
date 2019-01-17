<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Menus
{
    var $obj;
    var $menu;

    function __construct()
    {
        $this->obj =& get_instance();
    }
    
    public function getMenu($id){
        $results = $this->getMenus();
        foreach ($results as $result){
            if ($result->id_categoria == $id){
                $this->menu = $result;
                return $result;
            }
        }
        return array();
    }
    
    public function getMontaMenu(){
        $sql = "SELECT tbl_categoria.id_categoria, "
             . " tbl_categoria.categoria, "
             . " tbl_categoria.classe, "
             . " tbl_subcategoria.id_subcategoria, "
             . " tbl_subcategoria.subcategoria, "
             . " REPLACE(tbl_subcategoria.subcategoria,' ', '-') AS link "
             . " FROM tbl_categoria "
             . " LEFT JOIN tbl_subcategoria ON (tbl_categoria.id_categoria = tbl_subcategoria.id_categoria) "
             . "WHERE tbl_categoria.status = 'a'"
             . " AND tbl_subcategoria.status = 'a'";
        return $this->obj->superModel->query($sql);
    }
    
    public function getMenus(){
        $data = array (
            'select' => 'id_categoria, categoria',
            'condicoes' => array (
                array (
                    'campo' => 'status',
                    'valor' => 'a'
                )
            )
        );

        return $this->obj->superModel->select('categoria', $data );
    }
    
    public function getSubmenu($id = null){

        $data = array (
            'select' => 'id_subcategoria, subcategoria, titulo, REPLACE(subcategoria,\' \', \'-\') AS link',
            'condicoes' => array (
                array (
                    'campo' => 'status',
                    'valor' => 'a'
                ),
                array (
                    'campo' => 'id_categoria',
                    'valor' => $id == null ? $this->menu->id_categoria : $id
                )
            )
        );
        
        return $this->obj->superModel->select('subcategoria', $data );
    }
    
    public function getViewSubmenu($id, $idCategoria = null){

        $data = array (
            'select' => '*',
            'condicoes' => array (
                array (
                    'campo' => 'id_categoria',
                    'valor' => $idCategoria == null ? $this->menu->id_categoria : $idCategoria
                ),
                array (
                    'campo' => 'id_subcategoria',
                    'valor' => $id
                )
            )
        );
        
        return $this->obj->superModel->select('view_subcategoria', $data );
    }
}
?>