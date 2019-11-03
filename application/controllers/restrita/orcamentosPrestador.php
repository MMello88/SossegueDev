<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class OrcamentosPrestador extends MY_Restrita {

private $id_manutencao = 1;
private $CategoriaServico;
private $Servicos;  


public function index() {

$this->layout->view('restrita/meusPedidosOrcamentosPrestador', $this->data);

}


}
?>