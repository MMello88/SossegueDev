<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Restrita {
	
	public function index() {
	      
      
           $condicoes = array(
       'condicoes' => array(
              
                array(
                    'campo' => 'id_usuario',
                    'valor' =>$this->session->userdata('id')
                ),

            )
        );
        
        $idUsuario = $this->superModel->select('usuario', $condicoes);
        
         $clausula = array(
                'condicao' => array( 
                
                  array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata('id') 
                  )
                )
        );

  
       $this->data['dadosAprov'] = $this->superModel->select('aprovador', $clausula);
       $this->data['dadosReq'] = $this->superModel->select('requerente', $clausula);
       $this->data['dados'] = $this->superModel->select('usuario', $condicoes);
         $this->session->unset_userdata('msgOs');
       $this->layout->view('restrita/home', $this->data);
}
  
 

}
