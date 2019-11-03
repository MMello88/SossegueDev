<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Paginacao_Controle extends MY_Restrita {

    public $sn_pula_proxima_pergunta;
    public $sn_pula_categoria;
    public $sn_vlr_visita;
    public $sn_preco_por_qntde;
    public $sn_qntd_por_add;
    public $sinal;
    public $sn_vlr_sinal;    

    public function __construct() {
        parent::__construct();
    }

    public function mensagemInicial(){
        $this->session->set_userdata('arrConfigSubCateg', '');
        $this->session->set_userdata('arrConfigEnunciado', '');
        $this->layout->view('restrita/valor_mao_obra/mensagemInicial',$this->data);
    }

    private function AddEnunciadoFinalizado($IdSubcategoria, $NrEnunciado){
        if (!$this->ValidaEnunciadoFinalizado($IdSubcategoria, $NrEnunciado)){
            $arrConfigEnunciado = $this->session->userdata('arrConfigEnunciado');
            if (empty($arrConfigEnunciado))
                $arrConfigEnunciado[$IdSubcategoria] = array($NrEnunciado);
            else{
                if (empty($arrConfigEnunciado[$IdSubcategoria]))
                    $arrConfigEnunciado[$IdSubcategoria] = array($NrEnunciado);
                else
                    array_push($arrConfigEnunciado[$IdSubcategoria], $NrEnunciado);
            }
            $this->session->set_userdata('arrConfigEnunciado', $arrConfigEnunciado);
        }
    }

    private function ValidaEnunciadoFinalizado($IdSubcategoria, $NrEnunciado){
        $arrConfigEnunciado = $this->session->userdata('arrConfigEnunciado');
        if (!empty($arrConfigEnunciado)) {
            foreach ($arrConfigEnunciado as $IdSubCateg => $Enunciados) {
                if ($IdSubCateg == $IdSubcategoria){
                    foreach ($Enunciados as $Enunciado) {
                        if ($NrEnunciado == $Enunciado){
                            return true;
                        }
                    }
                }
            }
            return false;
        }
        return false;
    }

    private function DeleteEnunciadoFinalizado($IdSubcategoria, $NrEnunciado){
         $arrConfigEnunciado = $this->session->userdata('arrConfigEnunciado');
        if (!empty($arrConfigEnunciado)) {
            foreach ($arrConfigEnunciado as $IdSubCateg => $Enunciados) {
                if ($IdSubCateg == $IdSubcategoria){
                    foreach ($Enunciados as $key => $Enunciado) {
                        if ($NrEnunciado == $Enunciado){
                            unset($arrConfigEnunciado[$IdSubCateg][$key]);
                        }
                    }
                }
            }
        }
        $this->session->set_userdata('arrConfigEnunciado', $arrConfigEnunciado);
    }

    private function AddSubCategFinalizado($IdSubcategoria){
        if (!$this->ValidaSubCategFinalizado($IdSubcategoria)){
            $arrConfigSubCateg = $this->session->userdata('arrConfigSubCateg');
            if (empty($arrConfigSubCateg))
                $arrConfigSubCateg = array($IdSubcategoria);
            else
                array_push($arrConfigSubCateg, $IdSubcategoria);
            $this->session->set_userdata('arrConfigSubCateg', $arrConfigSubCateg);
        }
    }

    private function ValidaSubCategFinalizado($IdSubcategoria){
        $arrConfigSubCateg = $this->session->userdata('arrConfigSubCateg');
        if (!empty($arrConfigSubCateg)){
            foreach ($arrConfigSubCateg as $key => $value) {
                if ($IdSubcategoria == $value){
                    return true;
                }
            }
            return false;
        }
        return false;
    }

    private function DeleteSubCategFinalizado($IdSubcategoria){
        $arrConfigSubCateg = $this->session->userdata('arrConfigSubCateg');
        foreach ($arrConfigSubCateg as $key => $value) {
            if ($IdSubcategoria == $value){
                unset($arrConfigSubCateg[$key]);
            }
        }
        $this->session->set_userdata('arrConfigSubCateg', $arrConfigSubCateg);
    }

    public function nextPagina() {
        $this->load->model('ModeloList/ListaProf_subcategs');
        $this->load->model('ModeloList/ListaProf_enunciado');
        $this->load->model('ModeloList/ListaProf_perguntas');
        $this->load->model('ModeloList/ListaProf_pergunta_respostas');
        $this->load->model('ModeloList/ListaSubcategorias');
        

        $listaSubCateg = $this->ListaProf_subcategs->getProf_subcategByProfissional($this->session->userdata('id_profissional'));


	    if($_POST){
            //print_r($_POST);
            $this->SalvarPost();
    	}

        $processoPausado = "False";
        foreach ($listaSubCateg as $Subcateg) {
            //echo $Subcateg->id_subcategoria;

            if (!$this->ValidaSubCategFinalizado($Subcateg->id_subcategoria)){
                $subcategoria = $this->ListaSubcategorias->get($Subcateg->id_subcategoria);
                $enunciados = $this->ListaProf_enunciado->getProf_enunciadoBySubcategoria($Subcateg->id_subcategoria);
                
                foreach ($enunciados as $enunciado) {
                    if (!$this->ValidaEnunciadoFinalizado($Subcateg->id_subcategoria, $enunciado->ordem)){

                        if ($this->sn_pula_categoria == "S"){
                            $this->sn_pula_categoria = "";
                            $this->AddSubCategFinalizado($Subcateg->id_subcategoria);
                        } else {
                            $this->AddEnunciadoFinalizado($Subcateg->id_subcategoria, $enunciado->ordem);
                        
                            $processoPausado = "True";
                            $perguntas = $this->ListaProf_perguntas->getProf_perguntaByProf_enunciado($enunciado->id_prof_enunciado);
                            if ($perguntas[0]->sn_vlr_sinal_off == 'S'){
                                $ListaRespostas = $this->ListaProf_pergunta_respostas->ListaValoresRespondidoOff(
                                    $Subcateg->id_prof_subcateg, 
                                    $Subcateg->id_subcategoria, 
                                    $perguntas[0]->ds_servico_remetente, 
                                    $perguntas[0]->ds_servico, 
                                    $perguntas[0]->valor_sinal_off, 
                                    $perguntas[0]->sinal);

                                //print_r($ListaRespostas);
                                foreach ($ListaRespostas as $Resposta) {
                                    $modelo = $this->ListaProf_pergunta_respostas->BuscaPorProf_pergunta_respostas(
                                        $Resposta->ds_servico,
                                        $Resposta->id_prof_subcateg,
                                        $Resposta->id_prof_pergunta,
                                        $Resposta->id_prof_enunciado);

                                    if (empty($modelo)){
                                        $Resposta->id_prof_pergunta_resposta = 'null';
                                        $Resposta->insert();
                                    }
                                    else {
                                        $Resposta->id_prof_pergunta_resposta = $modelo[0]->id_prof_pergunta_resposta;
                                        $Resposta->update();
                                    }
                                }

                            } else {
                                $respostas = $this->ListaProf_pergunta_respostas->getProf_pergunta_respostasByProf_enunciado($Subcateg->id_prof_subcateg, $enunciado->id_prof_enunciado);

                                $this->data['subcategoria'] = $subcategoria[0]->subcategoria;
                                $this->data['id_prof_subcateg'] = $Subcateg->id_prof_subcateg;
                                $this->data['enunciado'] = $enunciado;
                                $this->data['perguntas'] = $perguntas;
                                $this->data['respostas'] = $respostas;
                                $this->layout->view('restrita/valor_mao_obra/pergunta', $this->data);
                                break;
                            }
                            //$this->DeleteEnunciadoFinalizado($Subcateg->id_subcategoria, $enunciado->ordem);
                        }
                    }
                }
            }
            
            if ($processoPausado == "False"){
                $this->AddSubCategFinalizado($Subcateg->id_subcategoria);
            } else {
                break;
            }
        }

        if ($processoPausado == "False"){
            $this->session->set_userdata('arrConfigSubCateg', '');
            $this->session->set_userdata('arrConfigEnunciado', '');
            redirect("restrita/lista/prestador/categorias");
        }
    }

    public function prevPagina(){
    	$OrdemEnunciado = '';
        if ($this->session->userdata('OrdemEnunciado')){
        	$OrdemEnunciado = $this->session->userdata('OrdemEnunciado');
        	$OrdemEnunciado = $OrdemEnunciado - 1;
        }
        
        $this->session->set_userdata('OrdemEnunciado', $OrdemEnunciado);

    	$this->nextPagina();
    }

    public function SalvarPost()
    {
        $this->load->model('Modelo/Prof_pergunta_resposta');
        $id_prof_subcateg = $_POST['id_prof_subcateg'];
        $id_prof_enunciado = $_POST['id_prof_enunciado'];
        $id_subcategoria = $_POST['id_subcategoria'];
        

        foreach ($_POST['id_prof_pergunta'] as $key => $value) {
            $id_prof_pergunta = $_POST['id_prof_pergunta'][$key];
            $i = $id_prof_pergunta;

            $id_prof_pergunta_resposta = empty($_POST['id_prof_pergunta_resposta'][$i]) ? "null" : $_POST['id_prof_pergunta_resposta'][$i];
            $vlr_primeiro  = $this->getValuePostArr('vlr_primeiro', $i, 'Number');
            $vlr_adicional = $this->getValuePostArr('vlr_adicional', $i, 'Number');
            $vlr_porcent   = $this->getValuePostArr('vlr_porcent', $i, 'Number');
            $qntd          = $this->getValuePostArr('qntd', $i, 'Number');
            $checkbox      = $this->getValuePostArr('checkbox', $i, 'Char');
            $faz_servico   = $this->getValuePostArr('faz_servico', $i, 'Char');
            $respondido    = "S";

            $this->Prof_pergunta_resposta->id_prof_pergunta_resposta = $id_prof_pergunta_resposta;
            $this->Prof_pergunta_resposta->id_prof_subcateg  = $id_prof_subcateg; 
            $this->Prof_pergunta_resposta->id_prof_pergunta  = $id_prof_pergunta; 
            $this->Prof_pergunta_resposta->id_prof_enunciado = $id_prof_enunciado;
            $this->Prof_pergunta_resposta->vlr_primeiro      = str_replace(',','.',$vlr_primeiro);
            $this->Prof_pergunta_resposta->vlr_adicional     = str_replace(',','.',$vlr_adicional);
            $this->Prof_pergunta_resposta->vlr_porcent       = str_replace(',','.',$vlr_porcent);
            $this->Prof_pergunta_resposta->qntd              = str_replace(',','.',$qntd);
            $this->Prof_pergunta_resposta->checkbox          = $checkbox;
            $this->Prof_pergunta_resposta->faz_servico       = $faz_servico;
            $this->Prof_pergunta_resposta->respondido        = $respondido;

            $this->Prof_pergunta_resposta->sn_pula_proxima_pergunta = $this->getValuePostArr('sn_pula_proxima_pergunta', $i);
            $this->Prof_pergunta_resposta->sn_pula_categoria = $this->getValuePostArr('sn_pula_categoria', $i);
            $this->Prof_pergunta_resposta->sn_vlr_visita = $this->getValuePostArr('sn_vlr_visita', $i);
            $this->Prof_pergunta_resposta->sn_preco_por_qntde = $this->getValuePostArr('sn_preco_por_qntde', $i);
            $this->Prof_pergunta_resposta->sn_qntd_por_add = $this->getValuePostArr('sn_qntd_por_add', $i);
            $this->Prof_pergunta_resposta->sinal = $this->getValuePostArr('sinal', $i);
            $this->Prof_pergunta_resposta->sn_vlr_sinal = $this->getValuePostArr('sn_vlr_sinal', $i);
            $this->Prof_pergunta_resposta->ds_servico = $this->getValuePostArr('ds_servico',$i);
            $this->Prof_pergunta_resposta->ds_servico_remetente = $this->getValuePostArr('ds_servico_remetente',$i);
            $this->Prof_pergunta_resposta->sn_vlr_sinal_todos_categ = $this->getValuePostArr('sn_vlr_sinal_todos_categ',$i);
            $this->Prof_pergunta_resposta->sn_vlr_sinal_off = $this->getValuePostArr('sn_vlr_sinal_off',$i);
            $this->Prof_pergunta_resposta->valor_sinal_off = $this->getValuePostArr('valor_sinal_off',$i);

            if ($checkbox == "S"){
                $this->sn_pula_proxima_pergunta = $this->Prof_pergunta_resposta->sn_pula_proxima_pergunta;
                $this->sn_pula_categoria        = $this->Prof_pergunta_resposta->sn_pula_categoria;
                $this->sn_vlr_visita            = $this->Prof_pergunta_resposta->sn_vlr_visita;
                $this->sn_preco_por_qntde       = $this->Prof_pergunta_resposta->sn_preco_por_qntde;
                $this->sn_qntd_por_add          = $this->Prof_pergunta_resposta->sn_qntd_por_add;
                $this->sinal                    = $this->Prof_pergunta_resposta->sinal;
                $this->sn_vlr_sinal             = $this->Prof_pergunta_resposta->sn_vlr_sinal;
            }
            

            if (empty($_POST['id_prof_pergunta_resposta'][$i])){
                $this->Prof_pergunta_resposta->insert();
            } else {
                $this->Prof_pergunta_resposta->update();
            }

            if ($this->Prof_pergunta_resposta->sn_vlr_sinal == "S"){
                $ListaRespostas = $this->ListaProf_pergunta_respostas->ListaValoresRespondido($this->Prof_pergunta_resposta, $id_subcategoria);
                //print_r($ListaRespostas);
                foreach ($ListaRespostas as $Resposta) {
                    $modelo = $this->ListaProf_pergunta_respostas->BuscaPorProf_pergunta_respostas(
                        $Resposta->ds_servico,
                        $Resposta->id_prof_subcateg,
                        $Resposta->id_prof_pergunta,
                        $Resposta->id_prof_enunciado);

                    if (empty($modelo)){
                        $Resposta->id_prof_pergunta_resposta = 'null';
                        $Resposta->insert();
                    }
                    else {
                        $Resposta->id_prof_pergunta_resposta = $modelo[0]->id_prof_pergunta_resposta;
                        $Resposta->update();
                    }
                }
            }

        }
    }

    private function ReplicarValores(){

    }

    public function getValuePostArr($name, $index, $tipo = 'Varchar'){
        if ($tipo == 'Varchar' )
            return isset($_POST[$name][$index]) ? empty($_POST[$name][$index]) ? "" : $_POST[$name][$index] : "";
        if ($tipo == 'Number')
            return isset($_POST[$name][$index]) ? empty($_POST[$name][$index]) ? "null" : $_POST[$name][$index] : "null";
        if ($tipo == 'Char')
            return isset($_POST[$name][$index]) ? empty($_POST[$name][$index]) ? "" : $_POST[$name][$index] == "on" ? "S" : "N" : "";
    }
}
?>