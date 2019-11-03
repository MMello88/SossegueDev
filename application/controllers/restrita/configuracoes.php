<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Configuracoes extends MY_Restrita {
	public function index() {
        $this->data['confCategorias'] = $this->superModel->select('configuracoes_categoria');

        foreach($this->data['confCategorias'] as $key => $categoria) {
            $data = array(
                'select' => 'id, campo, valor, complemento',
                'condicoes' => array(
                    array(
                        'campo' => 'id_configuracoes_categoria',
                        'valor' => $categoria->id_configuracoes_categoria
                    )
                ),
                'join' => array(
                    array(
                        'tabela' => 'configuracoes',
                        'on' => 'configuracoes.id = configuracoes_relacao.id_configuracoes'
                    )
                ),
                'order' => array(
                    array(
                        'campo' => 'campo'
                    )
                )
            );

            $this->data['confCategorias'][$key]->configuracoes = $this->superModel->select('configuracoes_relacao', $data);
        }

		$this->layout->view('restrita/configuracoes', $this->data);
	}

    public function alterar() {
        foreach($this->input->post() as $campo => $post) {
            foreach($post as $id => $valor) {
                $data = array(
                    $campo => $valor,
                );

                $condicoes = array(
                    array(
                        'campo' => 'id',
                        'valor' => $id
                    )
                );

                $this->superModel->update('configuracoes', $data, $condicoes);
            }
        }

        $this->session->set_flashdata('msg', $this->lang->line('cadastro_atualizado'));
        redirect('restrita/home');
    }
}
