<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Restrita extends MY_Front {
	var $data;

	public function __construct() {
		parent::__construct();
        //echo "MY".__LINE__."<br>";
		$this->load->model('restritaModel');

		if(!$this->autentica()) {
			redirect('restrita/acesso');
        }

		if(!$this->restritaModel->validaPermissao()) {
			//redirect('restrita/home');
        }

    
        insertTag('js', 'jquery.cookie.js', $this->data);
        insertTag('js', 'restrita.js?v=1.0', $this->data);

        $this->data['titulo'] = 'Sossegue';

        $data = array(
            'select' => 'id_tipo_usuario',
            'row' => TRUE,
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata('id')
                )
            )
        );

        $idTipoUsuario = $this->superModel->select('usuario', $data)->id_tipo_usuario;

        $clausulas = array(
            'select' => 'nome, url, menu.id_menu',
            'condicoes' => array(
                array(
                    'campo' => 'status',
                    'valor' => 'a'
                ),
                array(
                    'campo' => 'id_tipo_usuario',
                    'valor' => $idTipoUsuario
                )
            ),
            'join' => array(
                array(
                    'tabela' => 'menu',
                    'on' => 'menu.id_menu = menu_usuario.id_menu'
                )
            ),
            'order' => array(
                array(
                    'campo' => 'ordem'
                ),
                array(
                    'campo' => 'nome'
                )
            )
        );

        $this->data['menuRestrita'] = $this->superModel->select('menu_usuario', $clausulas);

        foreach($this->data['menuRestrita'] as $key => $menu) {
            $clausulas = array(
                'condicoes' => array(
                    array(
                        'campo' => 'status',
                        'valor' => 'a'
                    ),
                    array(
                        'campo' => 'id_menu',
                        'valor' => $menu->id_menu
                    )
                )
            );

            $submenu = $this->superModel->select('submenu', $clausulas);

            if($submenu) {
                $this->data['menuRestrita'][$key]->submenus = $submenu;
            }
        }

        $this->layout->setLayout('frontendRestrita'); 
        $this->lang->load('mensagens');
	}

    public function logoff() {
		$this->restritaModel->logoff();
	}

    public function alteraStatus() {
        if($this->form_validation->run('restrita/alterarStatus')) {
            $condicoes = array(
                array(
                    'campo' => $this->input->post('campo'),
                    'valor' => $this->input->post('id'),
                )
            );

            $this->superModel->update($this->input->post('tabela'), array('status' => $this->input->post('status')), $condicoes);
        }
    }

    public function alteraSituacao() {
        $condicoes = array(
            array(
                'campo' => $this->input->post('campo'),
                'valor' => $this->input->post('id'),
            )
        );

        $this->superModel->update($this->input->post('tabela'), array('situacao' => $this->input->post('situacao')), $condicoes);
    }

    public function deleta() {
        if($this->form_validation->run('restrita/deleta')) {
            $condicoes = array(
                array(
                    'campo' => $this->input->post('campo'),
                    'valor' => $this->input->post('id')
                )
            );

            if(!$this->input->post('definitivo')) {
                $this->superModel->update($this->input->post('tabela'), array('status' => 'd'), $condicoes);
            } else {
                $this->superModel->delete($this->input->post('tabela'), array(
                    'campo' => $this->input->post('campo'),
                    'valor' => $this->input->post('id')
                ));
            }

            $this->session->set_flashdata('msg', $this->lang->line('cadastro_excluido'));
        }
    }

    public function excluiFoto() {
        unlink($this->input->post('foto'));

        $condicoes = array(
            array(
                'campo' => $this->input->post('campo'),
                'valor' => $this->input->post('id'),
            )
        );

        $this->superModel->update($this->input->post('tabela'), array('foto' => ''), $condicoes);
    }

    public function realizaUpload($data = array(), $resize = false) {
        $config['upload_path'] = $data['dir'];
        $config['allowed_types'] = 'gif|jpg|png';
        $config['file_name'] = $data['fileName'];

        $this->load->library('upload', $config);

        $data['fileName'] = '';

        if($this->upload->do_upload($data['inputName'])) {
            $dataUpload = $this->upload->data();
            $data['fileName'] = $dataUpload['file_name'];

            if($resize) {
                $this->resize($data);
            }
        }

        return $data['fileName'];
    }

    public function resize($data = array()) {
        $config['source_image']	= $data['dir'] . $data['fileName'];
        $config['maintain_ratio'] = TRUE;
        $config['master_dim'] = 'width';
        $config['width'] = $this->superModel->select('configuracoes', array(
                'select' => 'valor AS width',
                'row' => 'true',
                'condicoes' => array(
                    array(
                        'campo' => 'campo',
                        'valor' => 'img_' . $data['inputName'] . '_width'
                    )
                )))->width;
        $config['height'] = $this->superModel->select('configuracoes', array(
                'select' => 'valor AS height',
                'row' => 'true',
                'condicoes' => array(
                    array(
                        'campo' => 'campo',
                        'valor' => 'img_' . $data['inputName'] . '_width'
                    )
                )))->height;

        $this->load->library('image_lib', $config);
        $this->image_lib->resize();
    }

    public function uploadAjax() {
        $data = array(
            'dir' => DIR_PROFISSIONAL,
            'inputName' => $this->input->post('inputName'),
            'fileName' => $this->superModel->getUrlAmigavel($this->input->post('nome'), 'tbl_profissional', 'url')
        );

        $fileName = $this->realizaUpload($data, TRUE);

        $data = array(
            'id_profissional' => $this->input->post('id'),
            'foto' => $fileName
        );

        $this->superModel->insert('fotos', $data);
    }

    public function ordenaGaleria() {
        if($this->input->post('fotos')) {
            foreach($this->input->post('fotos') as $foto) {
                $condicoes = array(
                    array(
                        'campo' => 'id_fotos',
                        'valor' => $foto['id']
                    )
                );

                $data = array(
                    'ordem' => $foto['ordem']
                );

                $this->superModel->update('fotos', $data, $condicoes);
            }
        }
    }

    public function excluirFotoGaleria() {
        if($this->input->post('id')) {
            $foto = DIR_PROFISSIONAL . $this->input->post('foto');

            if(is_file($foto)) {
                unlink($foto);
            }

            $data = array(
                'campo' => 'id_fotos',
                'valor' => $this->input->post('id')
            );

            $this->superModel->delete('fotos', $data);
        }
    }
}
?>