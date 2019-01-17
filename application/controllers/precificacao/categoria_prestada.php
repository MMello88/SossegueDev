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
		$prof = $this->listaSubcategorias->getProfissionalByUsuario($this->session->userdata('id'));
        $listaCategoria = $this->listaSubcategorias->getSubCategoriaByUsuarioCheckBox($prof->id_profissional);

        //echo form_checkbox('newsletter', 'accept', TRUE);
        $html = form_open('restrita/prof/categ/salvar', array('id' => 'formSubCatUsuario'));
		$html .= "<input type='hidden' name='id_profissional' value='$prof->id_profissional' >";
        foreach ($listaCategoria as $result) {
            $html .= " <div class='form-check'>
                         <label class='form-check-label'> 
						 " . form_checkbox("id_subcategoria[]", "$result->id_prof_subcateg/$result->id_subcategoria/", $result->chacked) . "
                            $result->subcategoria
                        </label> 
                       </div> " ;
        }
        $html .= "<br/><br/>".
                 "  <div class='w3-bar'>" .
                 "    <button type='submit' class='w3-button w3-yellow'>Dar inicio as perguntas</button> " .
                 "  </div> " .
                 "</form> " ;
        return $html;
    }

	public function salvar() {
        $this->load->model('Modelo/prof_subcateg');
		if($_POST){
			$this->prof_subcateg->insert();
			redirect('restrita/prof/mensagem/aviso');
		} else {
			redirect('restrita/lista/prestador/categorias');
		}
    }
	
    public function mensagemInicial(){
        $this->session->set_userdata('arrConfigSubCateg', '');
        $this->session->set_userdata('arrConfigEnunciado', '');
        $this->layout->view('restrita/valor_mao_obra/mensagemInicial',$this->data);
    }
	
	public function mensagemNaoTerminado(){
        $this->session->set_userdata('arrConfigSubCateg', '');
        $this->session->set_userdata('arrConfigEnunciado', '');
        $this->layout->view('restrita/valor_mao_obra/mensagemNaoTerminado',$this->data);
    }
	
	public function mensagemTerminado(){
        $this->session->set_userdata('arrConfigSubCateg', '');
        $this->session->set_userdata('arrConfigEnunciado', '');
        $this->layout->view('restrita/valor_mao_obra/mensagemTerminado',$this->data);
    }
}
?>