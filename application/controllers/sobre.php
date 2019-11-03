<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sobre extends MY_Front {

     public function __construct() {
        parent::__construct();

    }
    
    public function index($slug) {
        $this->slug($slug);
    }

    private function slug($slug)
    {
 
        if ($slug=="quem-somos"){ 
    
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


        elseif($slug="termos-e-condicoes"){ 
        
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
}