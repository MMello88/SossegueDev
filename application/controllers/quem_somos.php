<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Quem_somos extends MY_Front {

	public function index() {
        $data = array(
            'select' => 'texto',
            'row' => TRUE,
            'condicoes' => array(
                array(
                    'campo' => 'url',
                    'valor' => 'quem-somos'
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
		$this->layout->view('quemSomos', $this->data);
	}
}
