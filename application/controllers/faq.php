<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Faq extends MY_Front {

	public function index() {
        $data = array(
            'select' => 'pergunta, resposta, id',
            'condicoes' => array(
                array(
                    'campo' => 'status',
                    'valor' => 'a'
                )
            )
        );

        $data['condicoes'][1] = array(
            'campo' => 'tipo',
            'valor' => 'u'
        );

        $this->data['perguntaUsuarios'] = $this->superModel->select('faq', $data);

        $data['condicoes'][1] = array(
            'campo' => 'tipo',
            'valor' => 'a'
        );

        $this->data['perguntaAnunciantes'] = $this->superModel->select('faq', $data);

		$this->layout->view('faq', $this->data);
	}
}
