<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class  ordensPrestador extends MY_Restrita {

private $id_manutencao = 1;
private $CategoriaServico;
private $Servicos;  


public function index() {

$this->layout->view('restrita/ordensServicosPrestador', $this->data);

}


}
?>