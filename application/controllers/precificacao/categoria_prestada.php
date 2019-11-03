<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categoria_prestada extends MY_Restrita {

    public function __construct() {
        parent::__construct();
    }

    public function listarSubcategoria() {
        $this->data['form'] = get_class($this);
        $this->data['listaSubcategorias'] = $this->getHtmlCheckBox();
        $this->data['titulo'] = 'Categoria de Serviço que Você Presta';
        $this->layout->view('restrita/valor_mao_obra/listarSubcategoria', $this->data);
    }

    private function getHtmlCheckBox(){
        $this->load->model('ModeloList/listaSubcategorias');
        $listaCategoria = $this->listaSubcategorias->getSubCategoriaByUsuarioCheckBox($this->session->userdata('id'));

        $html = form_open('', array('id' => 'formSubCatUsuario'));
        foreach ($listaCategoria as $result) {
            $html .= " <input type='hidden' name='id_profissional' value='$result->id_profissional' > " .
                     "  <div class='form-check'> " .
                     "    <label class='form-check-label'> " .
                     "      <input type='checkbox' class='form-check-input' name='id_subcategoria[]' value='$result->id_prof_subcateg/$result->id_subcategoria/' $result->status > $result->subcategoria" .
                     "    </label> " .
                     "  </div> " ;
        }

        $html .= "<br/><br/>".
                 "  <div class='w3-bar'>" .
                 "    <button class='w3-button w3-red' id='btnVoltar'>Voltar</button>" .
                 "    <button class='w3-button w3-black' id='btnAfterFinish'>Terminar Mais Tarde</button> " .
                 "    <button type='submit' class='w3-button w3-yellow' id='btnNext'>Próxima</button> " .
                 "  </div> " .
                 "</form> " ;
        return $html;
    }

}
?>