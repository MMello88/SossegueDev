<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Termos_e_condicoes extends MY_Front {

	public function index() {
        $data = array(
            'select' => 'texto',
            'row' => TRUE,
            'condicoes' => array(
                array(
                    'campo' => 'url',
                    'valor' => 'termos-e-condicoes'
                ),
                array(
                    'campo' => 'status',
                    'valor' => 'a'
                )
            )
        );

        $pagina = $this->superModel->select('pagina_estatica', $data);

        if(!$pagina) {
            redirect('');
        }

        $this->data['pagina'] = $pagina;
		$this->layout->view('termos', $this->data);
	}
}
