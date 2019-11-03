<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categorias extends MY_Restrita {
    public function __construct() {
		parent::__construct();
    }

    public function cadastrar() {
        $this->data['titulo'] = 'Nova categoria';
        $this->layout->view('restrita/categorias/cadastrar', $this->data);
    }

    public function editar($id) {
        $clausulas = array(
            'row' => true,
            'condicoes' => array(
                array(
                    'campo' => 'id_categoria',
                    'valor' => $id
                )
            )
        );

        $this->data['categoria'] = $this->superModel->select('categoria', $clausulas);
        $this->data['titulo'] = 'Editar categoria';
        $this->layout->view('restrita/categorias/editar', $this->data);
    }

    public function alterar() {
        if($this->form_validation->run('restrita/categorias/inserir')) {
            $condicoes = array(
                array(
                    'campo' => 'id_categoria',
                    'valor' => $this->input->post('id'),
                )
            );

            $this->superModel->update('categoria', array('categoria' => $this->input->post('categoria')), $condicoes);
            $this->session->set_flashdata('msg', $this->lang->line('cadastro_atualizado'));
            redirect('restrita/categorias/listar');
        }
    }

    public function inserir() {
        if($this->form_validation->run('restrita/categorias/inserir')) {
            $this->superModel->insert('categoria', $this->input->post());
            $this->session->set_flashdata('msg', $this->lang->line('cadastro_inserido'));
            redirect('restrita/categorias/listar');
        }
    }

	public function listar() {
        $clausulas = array(
            'condicoes' => array(
                array(
                    'campo' => 'status',
                    'sinal' => '<>',
                    'valor' => 'd'
                )
            )
        );

        $this->data['listaCategorias'] = $this->superModel->select('categoria', $clausulas);
        $this->data['titulo'] = 'Categorias';

		$this->layout->view('restrita/categorias/listar', $this->data);
	}

    public function cadastrarSubcategoria() {
        $clausulas = array(
            'condicoes' => array(
                array(
                    'campo' => 'status',
                    'valor' => 'a'
                )
            )
        );

        $this->data['listaCategorias'] = $this->superModel->select('categoria', $clausulas);
        $this->data['titulo'] = 'Nova subcategoria';
        $this->layout->view('restrita/subcategorias/cadastrar', $this->data);
    }

    public function inserirSubcategoria() {
        if($this->form_validation->run('restrita/categorias/inserirSubcategoria')) {
            $this->superModel->insert('subcategoria', $this->input->post());
            $this->session->set_flashdata('msg', $this->lang->line('cadastro_inserido'));
            redirect('restrita/categorias/subcategorias');
        }
    }

    public function editarSubcategoria($id) {
        $clausulas = array(
            'row' => true,
            'condicoes' => array(
                array(
                    'campo' => 'id_subcategoria',
                    'valor' => $id
                )
            )
        );

        $this->data['subcategoria'] = $this->superModel->select('subcategoria', $clausulas);

        $clausulas = array(
            'condicoes' => array(
                array(
                    'campo' => 'status',
                    'valor' => 'a'
                )
            )
        );

        $this->data['listaCategorias'] = $this->superModel->select('categoria', $clausulas);
        $this->data['titulo'] = 'Editar subcategoria';
        $this->layout->view('restrita/subcategorias/editar', $this->data);
    }

    public function alterarSubcategoria() {
        if($this->form_validation->run('restrita/categorias/inserirSubcategoria')) {
            $condicoes = array(
                array(
                    'campo' => 'id_subcategoria',
                    'valor' => $this->input->post('id'),
                )
            );

            $this->superModel->update('subcategoria', array(
                'id_categoria' => $this->input->post('id_categoria'),
                'subcategoria' => $this->input->post('subcategoria')
            ), $condicoes);
            $this->session->set_flashdata('msg', $this->lang->line('cadastro_atualizado'));
            redirect('restrita/categorias/subcategorias');
        }
    }

	public function subcategorias() {
        $clausulas = array(
            'select' => 'subcategoria.status AS status, id_subcategoria, categoria, subcategoria',
            'join' => array(
                array(
                    'tabela' => 'categoria',
                    'on' => 'categoria.id_categoria = subcategoria.id_categoria'
                )
            ),
            'condicoes' => array(
                array(
                    'campo' => 'subcategoria.status',
                    'sinal' => '<>',
                    'valor' => 'd'
                )
            )
        );

        $this->data['listaSubcategorias'] = $this->superModel->select('subcategoria', $clausulas);
        $this->data['titulo'] = 'Subcategorias';

		$this->layout->view('restrita/subcategorias/listar', $this->data);
    }
    
    public function explicativos($id_subcategoria){
        $clausulas = array(
            'select' => 'subcategoria.subcategoria, explicativos.titulo, explicativos.status, explicativos.texto, explicativos.id_explicativos, explicativos.id_subcategoria, explicativos.meta_description, explicativos.header_title',
            'join' => array(
                array(
                    'tabela' => 'subcategoria',
                    'on' => 'subcategoria.id_subcategoria = explicativos.id_subcategoria'
                )
            ),
            'condicoes' => array(
                array(
                    'campo' => 'explicativos.id_subcategoria',
                    'sinal' => '=',
                    'valor' => $id_subcategoria
                )
            )
        );

        $this->data['listaExplicativos'] = $this->superModel->select('explicativos', $clausulas);
        $this->data['id_subcategoria'] = $id_subcategoria;
        $this->data['titulo'] = 'Texto Explicativos';
        $this->layout->view('restrita/subcategorias/explicativos/listar', $this->data);
    }

    public function cadastrarExplicativo($id_subcategoria){
        insertTag('css', base_url('assets/plugins/summernote/summernote.css'), $this->data);
        insertTag('js', base_url('assets/plugins/summernote/summernote.min.js'), $this->data);
        $this->data['id_subcategoria'] = $id_subcategoria;
        $this->data['titulo'] = 'Novo Texto Explicativo';
        $this->layout->view('restrita/subcategorias/explicativos/cadastrar', $this->data);
    }

    public function inserirExplicativo(){
        if($this->form_validation->run('restrita/categorias/explicativos/inserirSubcategoria')) {
            
            $data = array(
                'id_subcategoria' => $this->input->post('id_subcategoria'),
                'titulo' => $this->input->post('titulo'),
                'status' => $this->input->post('status'),
                'texto' => $this->input->post('texto'),
                'meta_description' => $this->input->post('meta_description'),
                'header_title' => $this->input->post('header_title')
            );

            $this->superModel->insert('explicativos', $data);
            $this->session->set_flashdata('msg', $this->lang->line('cadastro_inserido'));
            redirect('restrita/categorias/explicativos/' . $this->input->post('id_subcategoria'));
        }
    }

    public function editarExplicativo($id){
        insertTag('css', base_url('assets/plugins/summernote/summernote.css'), $this->data);
        insertTag('js', base_url('assets/plugins/summernote/summernote.min.js'), $this->data);

        $clausulas = array(
            'row' => true,
            'condicoes' => array(
                array(
                    'campo' => 'id_explicativos',
                    'valor' => $id
                )
            )
        );

        $this->data['explicativo'] = $this->superModel->select('explicativos', $clausulas);
        $this->data['titulo'] = 'Editar Texto Explicativo';
        $this->layout->view('restrita/subcategorias/explicativos/editar', $this->data);
    }

    public function alterarExplicativo() {
        if($this->form_validation->run('restrita/categorias/explicativos/inserirSubcategoria')) {
            $data = array(
                'titulo' => $this->input->post('titulo'),
                'texto' => $this->input->post('texto'),
                'meta_description' => $this->input->post('meta_description'),
                'header_title' => $this->input->post('header_title')
            );
            $condicoes = array(
                array(
                    'campo' => 'id_explicativos',
                    'valor' => $this->input->post('id_explicativos'),
                )
            );

            $this->superModel->update('explicativos', $data, $condicoes);
            $this->session->set_flashdata('msg', $this->lang->line('cadastro_atualizado'));
            redirect('restrita/categorias/explicativos/'.$this->input->post('id_subcategoria'));
        }
    }
}
