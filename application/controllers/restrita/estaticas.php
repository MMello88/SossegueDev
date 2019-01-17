<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Estaticas extends MY_Restrita {
	public function index($url) {
        if(!$url) {
            redirect('restrita/home');
        }

        $data = array(
            'row' => TRUE,
            'condicoes' => array(
                array(
                    'campo' => 'url',
                    'valor' => $url
                )
            )
        );

        $this->data['pagina'] = $this->superModel->select('pagina_estatica', $data);

        if(!$this->data['pagina']) {
            redirect('restrita/home');
        }

        insertTag('css', base_url('assets/plugins/summernote/summernote.css'), $this->data);
        insertTag('js', base_url('assets/plugins/summernote/summernote.min.js'), $this->data);

		$this->layout->view('restrita/estaticas', $this->data);
	}

    public function alterar() {
        if($this->form_validation->run('restrita/estatica/alterar')) {
            $data = array(
                'status' => $this->input->post('status'),
                'texto' => $this->input->post('texto')
            );

            $condicoes = array(
                array(
                    'campo' => 'id',
                    'valor' => $this->input->post('id')
                )
            );

            $this->superModel->update('pagina_estatica', $data, $condicoes);
            $this->session->set_flashdata('msg', $this->lang->line('cadastro_atualizado'));
            redirect('restrita/home');
        }
    }
}
