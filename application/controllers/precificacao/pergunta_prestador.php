<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pergunta_Prestador extends MY_Restrita {
    
    private $conf_pagination;

	public function __construct() {
        parent::__construct();
        $this->output->enable_profiler(TRUE);
		$this->load->model('ModeloList/ListaProf_subcategs');
        $this->load->model('ModeloList/ListaProf_enunciado');
        $this->load->model('ModeloList/ListaProf_perguntas');
        $this->load->model('ModeloList/ListaProf_pergunta_respostas');
        $this->load->model('ModeloList/ListaSubcategorias');
        
        $this->conf_pagination['base_url'] = base_url("restrita/prof/pergunta/");
        
        $this->conf_pagination['full_tag_open'] = '<ul class="pagination"><li>';
        $this->conf_pagination['full_tag_close'] = '</li><li></li></ul>';
        
        $this->conf_pagination['prev_tag_open'] = '<li>';
        $this->conf_pagination['prev_tag_close'] = '</li>';
        
        $this->conf_pagination['next_tag_open'] = '<li>';
        $this->conf_pagination['next_tag_close'] = '</li>';
        
        $this->conf_pagination['cur_tag_open'] = '<li class="active"><a href="#">';
        $this->conf_pagination['cur_tag_close'] = '</a></li>';

        $this->conf_pagination['num_tag_open'] = '<li>';
        $this->conf_pagination['num_tag_close'] = '</li>';

		$this->conf_pagination['first_tag_open'] = '<li>';
        $this->conf_pagination['first_tag_close'] = '</li>';
        
        $this->conf_pagination['links'] = "";
    }
    
    public function getPaginationSubCategoria($idSubcategoria){
        $id_profissional = $this->session->userdata('id_profissional');
        $ProfSubCatgs = $this->ListaProf_subcategs->getProfSubcategoria($id_profissional);

        extract($this->conf_pagination);

        $html = $full_tag_open;
        foreach ($ProfSubCatgs as $key => $subCatg) {
            if($subCatg->id_subcategoria == $idSubcategoria)
                $html .= $cur_tag_open . $subCatg->subcategoria . $cur_tag_close;
            else
                $html .= $num_tag_open . "<a href='" . $base_url ."/". $subCatg->id_subcategoria . "/1'>" . $subCatg->subcategoria . "</a>" . $num_tag_close;
        }
        $html .= $full_tag_close;

        return $html;
    }

    public function getPaginationEnunciadoPerguntas($idSubcategoria, $ordem){
        $Enunciados = $this->ListaProf_enunciado->getProf_enunciadoBySubcategoria($idSubcategoria);

        extract($this->conf_pagination);

        $html = $full_tag_open;
        foreach ($Enunciados as $key => $enun) {
            if($enun->ordem == $ordem){
                $this->data['cur_ordem' ] = $enun->ordem;
        
                if (isset($Enunciados[$key-1]))
                    $this->data['prev_ordem'] = $Enunciados[$key-1]->ordem;
                
                if (isset($Enunciados[$key+1]))
                    $this->data['next_ordem'] = $Enunciados[$key+1]->ordem;
                
                $html .= $cur_tag_open . $enun->ordem . $cur_tag_close;
            }
            else
                $html .= $num_tag_open . "<a href='" . $base_url ."/". $enun->id_subcategoria . "/". $enun->ordem ."'>" . $enun->ordem . "</a>" . $num_tag_close;
        }
        $html .= $full_tag_close;

        return $html;

    }

    public function pergunta($idSubcategoria, $ordem){
        if($_POST) {
            $this->load->model('Modelo/Prof_pergunta_resposta');
            foreach ($this->input->post('id_prof_pergunta') as $id_pergunta) {
                $resposta = array(
                    'id_prof_pergunta' => $id_pergunta,
                    'id_prof_subcateg' => $this->input->post('id_prof_subcateg'),
                    'vlr_primeiro'     => !empty($this->input->post('vlr_primeiro')[$id_pergunta]) ? $this->input->post('vlr_primeiro')[$id_pergunta] : "",
                    'vlr_adicional'    => !empty($this->input->post('vlr_adicional')[$id_pergunta]) ? $this->input->post('vlr_adicional')[$id_pergunta] : "",
                    'vlr_porcent'      => !empty($this->input->post('vlr_porcent')[$id_pergunta]) ? $this->input->post('vlr_porcent')[$id_pergunta] : "",
                    'vlr_qntd'         => !empty($this->input->post('vlr_qntd')[$id_pergunta]) ? $this->input->post('vlr_qntd')[$id_pergunta] : "",
                    'vlr_faz_servico'  => !empty($this->input->post('vlr_faz_servico')[$id_pergunta]) ? $this->input->post('vlr_faz_servico')[$id_pergunta] : "",
                    'vlr_sinal'        => !empty($this->input->post('vlr_sinal')[$id_pergunta]) ? $this->input->post('vlr_sinal')[$id_pergunta] : "",
                    'vlr_tipo'         => !empty($this->input->post('vlr_tipo')[0]) ? $this->input->post('vlr_tipo')[0] : "",
                    'vlr_checkbox'     => !empty($this->input->post('vlr_checkbox')[$id_pergunta]) ? $this->input->post('vlr_checkbox')[$id_pergunta] : ""
                );
                
                if(!empty($_POST['id_prof_pergunta_resposta'][$id_pergunta])){
                    $condicao = array(
                        'id_prof_pergunta_resposta' => $this->input->post('id_prof_pergunta_resposta')[$id_pergunta],  
                    );
                    $this->Prof_pergunta_resposta->update($resposta, $condicao);
                } else {
                    $resposta['id_prof_pergunta_resposta'] = null;
                    $this->Prof_pergunta_resposta->insert($resposta);
                }
            }
        }
        if (!empty($_POST['btnCur'])){
            redirect("restrita/prof/mensagem/NaoFinalizado");
        }
        $this->data['idSubcategoria'] = $idSubcategoria;
        $this->data['ordem'] = $ordem;
        $this->data['pagination_enunciado'] = $this->getPaginationEnunciadoPerguntas($idSubcategoria, $ordem);
        $this->data['pagination_subcatego'] = $this->getPaginationSubCategoria($idSubcategoria);
        $this->data['ProfSubCatg'] = $this->getBuildPerguntasResposta($idSubcategoria, $ordem);

        $this->layout->view('restrita/valor_mao_obra/pergunta', $this->data);
    }

	public function iniciar(){
        $id_profissional = $this->session->userdata('id_profissional');
		$ProfSubCatgs = $this->ListaProf_subcategs->getProfSubcatByProfAndSubcatg($id_profissional);
		$arrTodasPerguntas = array();
		foreach($ProfSubCatgs as $key1 => $ProfSubCatg){
			$Enunciados = $this->ListaProf_enunciado->getProf_enunciadoBySubcategoria($ProfSubCatg->id_subcategoria);
			foreach($Enunciados as $key2 => $Enun){
				$arrTodasPerguntas[] =  $ProfSubCatg->id_subcategoria.'/'.$Enun->id_prof_enunciado.'/'.$Enun->ordem;
				
				/*$Perguntas = $this->ListaProf_perguntas->getProf_perguntaByProf_enunciado($Enun->id_prof_enunciado);
				foreach($Perguntas as $key3 => $Pergunta){
					echo $Pergunta->id_subcategoria . '<br/>';
					
				}*/
			}
		}

		
		$this->session->set_userdata('arrTodasPerguntas', $arrTodasPerguntas);
		$param = explode('/',$arrTodasPerguntas[1]);
		$ProfSubCatg = $this->MontaSubCategEnunciadoPerguntaResposta($param[0],$param[1],$param[2]);
		
		$this->data['ProfSubCatg'] = $ProfSubCatg;
		$this->data['possicao_array'] = '1';


		$total = count($arrTodasPerguntas);

        $config['base_url'] = base_url("restrita/prof/pergunta/proxima/");
        
        $config['full_tag_open'] = '<ul class="pagination"><li></li>';
        $config['full_tag_close'] = '<li></li></ul>';
        
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';

        
        $config['total_rows'] = $total-1;
        $config['per_page'] = 1;
        $config['cur_page'] = 1;
        $config['num_links'] = $total;
        $config['last_link'] = TRUE;
        $config['first_link'] = TRUE;
        $config['first_url'] = base_url("restrita/prof/pergunta/proxima/1");
		$config['last_url'] = '';//base_url("restrita/prof/pergunta/proxima/$total");


        $config['use_page_numbers'] = TRUE;
        $config['uri_segment'] = 5;


		$this->pagination->initialize($config);

		
		$this->layout->view('restrita/valor_mao_obra/pergunta', $this->data);
	}

	public function gravar(){
		$this->SalvarPost();
		$possicao_array = $this->input->post('possicao_array') + 1;
		//if ($this->finalizar($possicao_array))
			//redirect('restrita/prof/mensagem/finalizado');
		//redirect('restrita/prof/pergunta/proxima/'. $possicao_array);
    }
	
	public function proxima($possicao_array){
		$arrTodasPerguntas = $this->session->userdata('arrTodasPerguntas');
		$param = explode('/',$arrTodasPerguntas[$possicao_array]);
		$ProfSubCatg = $this->MontaSubCategEnunciadoPerguntaResposta($param[0],$param[1],$param[2]);
		

		$total = count($arrTodasPerguntas);

        $config['base_url'] = base_url("restrita/prof/pergunta/proxima/");
        
        $config['full_tag_open'] = '<ul class="pagination"><li></li>';
        $config['full_tag_close'] = '<li></li></ul>';
        
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';

        
        $config['total_rows'] = $total-1;
        $config['per_page'] = 1;
        $config['cur_page'] = $possicao_array;
        $config['num_links'] = $total;
        $config['last_link'] = TRUE;
        $config['first_link'] = TRUE;
        $config['first_url'] = base_url("restrita/prof/pergunta/proxima/1");
		$config['last_url'] = '';//base_url("restrita/prof/pergunta/proxima/$total");


        $config['use_page_numbers'] = TRUE;
        $config['uri_segment'] = 5;


		$this->pagination->initialize($config);

		$this->data['ProfSubCatg'] = $ProfSubCatg;
		$this->data['possicao_array'] = $possicao_array;

		$this->layout->view('restrita/valor_mao_obra/pergunta', $this->data);
	}
	
	public function anterior($possicao_array){
		$arrTodasPerguntas = $this->session->userdata('arrTodasPerguntas');
		$param = explode('/',$arrTodasPerguntas[$possicao_array]);
		$ProfSubCatg = $this->MontaSubCategEnunciadoPerguntaResposta($param[0],$param[1],$param[2]);
		
		$total = count($arrTodasPerguntas);

        $config['base_url'] = base_url("restrita/prof/pergunta/proxima/");
        
        $config['full_tag_open'] = '<ul class="pagination"><li></li>';
        $config['full_tag_close'] = '<li></li></ul>';
        
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';

        
        $config['total_rows'] = $total-1;
        $config['per_page'] = 1;
        $config['cur_page'] = $possicao_array;
        $config['num_links'] = $total;
        $config['last_link'] = TRUE;
        $config['first_link'] = TRUE;
        $config['first_url'] = base_url("restrita/prof/pergunta/proxima/1");
		$config['last_url'] = '';//base_url("restrita/prof/pergunta/proxima/$total");


        $config['use_page_numbers'] = TRUE;
        $config['uri_segment'] = 5;


		$this->pagination->initialize($config);

		$this->data['ProfSubCatg'] = $ProfSubCatg;
		$this->data['possicao_array'] = $possicao_array;
		$this->layout->view('restrita/valor_mao_obra/pergunta', $this->data);
	}
	
    private function getBuildPerguntasResposta($idSubcategoria, $ordem){
        $id_profissional = $this->session->userdata('id_profissional');
        $ProfSubCatg  = $this->ListaProf_subcategs->getProfSubcategoria($id_profissional, $idSubcategoria);
        $SubCategoria = $this->ListaSubcategorias->get($ProfSubCatg->id_subcategoria);
        $Enunciado    = $this->ListaProf_enunciado->getProf_enunciadoBySubcategoria($idSubcategoria, $ordem);
        $Perguntas    = $this->ListaProf_perguntas->getPerguntaResposta($Enunciado->id_prof_enunciado, $id_profissional);

        $Enunciado->Perguntas = $Perguntas;
        $ProfSubCatg->Enunciado = $Enunciado;
        $ProfSubCatg->SubCategoria = $SubCategoria;

        return $ProfSubCatg;
    }

	private function MontaSubCategEnunciadoPerguntaResposta($id_subcategoria, $id_prof_enunciado, $ordem_enunciado){
		$id_profissional = $this->session->userdata('id_profissional');
		$ProfSubCatg = $this->ListaProf_subcategs->getProfSubcatByProfAndSubcatg($id_profissional, $id_subcategoria);
		$SubCategoria = $this->ListaSubcategorias->get($ProfSubCatg->id_subcategoria);
		
		$Enunciado = $this->ListaProf_enunciado->get($id_prof_enunciado);
		$Perguntas = $this->ListaProf_perguntas->getProf_perguntaByProf_enunciado($Enunciado->id_prof_enunciado);
		
		foreach($Perguntas as $key => $Pergunta){
			$Resposta = $this->ListaProf_pergunta_respostas->getResposta($ProfSubCatg->id_prof_subcateg, $Enunciado->id_prof_enunciado, $Pergunta->id_prof_pergunta);
			$Perguntas[$key]->Resposta = $Resposta;
		}
		
		$Enunciado->Perguntas = $Perguntas;
		$ProfSubCatg->Enunciado = $Enunciado;
		$ProfSubCatg->SubCategoria = $SubCategoria;
		
		return $ProfSubCatg;
	}
	
	private function finalizar($possicao_array){
		$arrTodasPerguntas = $this->session->userdata('arrTodasPerguntas');
		if (isset($arrTodasPerguntas[$possicao_array]))
			return false;
		return true;
	}
	
	private function SalvarPost()
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
            $tipo          = $this->getValuePostArr('tipo', $i);
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


            $this->Prof_pergunta_resposta->sinal = $this->getValuePostArr('sinal', $i);
            $this->Prof_pergunta_resposta->sn_vlr_sinal = $this->getValuePostArr('sn_vlr_sinal', $i);

            if ($checkbox == "S"){
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
                foreach ($ListaRespostas as $Resposta) {
                    $modelo = $this->ListaProf_pergunta_respostas->BuscaPorProf_pergunta_respostas(
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
	
	private function getValuePostArr($name, $index, $tipo = 'Varchar'){
        if ($tipo == 'Varchar' )
            return isset($_POST[$name][$index]) ? empty($_POST[$name][$index]) ? "" : $_POST[$name][$index] : "";
        if ($tipo == 'Number')
            return isset($_POST[$name][$index]) ? empty($_POST[$name][$index]) ? "null" : $_POST[$name][$index] : "null";
        if ($tipo == 'Char')
            return isset($_POST[$name][$index]) ? empty($_POST[$name][$index]) ? "" : $_POST[$name][$index] == "on" ? "S" : "N" : "";
    }
}