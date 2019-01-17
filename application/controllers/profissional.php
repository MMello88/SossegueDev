<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profissional extends MY_Front {
    var $qtde = 30;

    public function __construct() {
        parent::__construct();

    }

	public function visualizar($url = '') {
        insertTag('css',base_url('assets/plugins/fancybox/jquery.fancybox.css'),$this->data);
        insertTag('js',base_url('assets/plugins/fancybox/jquery.fancybox.pack.js'),$this->data);
        insertTag('js','http://maps.googleapis.com/maps/api/js?sensor=true',$this->data);

        $this->data['profissional'] = $this->superModel->select('profissional', array(
            'row' => true,
            'select' => 'url, usuario.nome, email, telefone, celular, cidade.nome AS cidade, estado.uf AS estado, descricao, subcategoria, DATE_FORMAT(nascimento, "%d/%m/%Y") as nascimento, endereco, numero, complemento, bairro, profissional.id_profissional, foto, id_planos',
            'join' => array(
                array(
                    'tabela' => 'profissional_fatura',
                    'on' => 'profissional_fatura.id_profissional = profissional.id_profissional'
                ),
                array(
                    'tabela' => 'usuario',
                    'on' => 'profissional.id_usuario = usuario.id_usuario'
                ),
                array(
                    'tabela' => 'usuario_endereco',
                    'on' => 'usuario_endereco.id_usuario = usuario.id_usuario'
                ),
                array(
                    'tabela' => 'cidade',
                    'on' => 'usuario_endereco.id_cidade = cidade.id'
                ),
                array(
                    'tabela' => 'estado',
                    'on' => 'cidade.estado = estado.id'
                ),
                array(
                    'tabela' => 'subcategoria',
                    'on' => 'subcategoria.id_subcategoria = profissional.id_subcategoria'
                )
            ),
            'condicoes' => array(
                array(
                    'campo' => 'url',
                    'valor' => $url
                ),
                array(
                    'campo' => 'profissional.status',
                    'valor' => 'a'
                ),
                array(
                    'campo' => 'profissional_fatura.situacao',
                    'valor' => 'a'
                ),
                array(
                    'campo' => 'profissional_fatura.status',
                    'valor' => 'a'
                ),
                array(
                    'campo' => 'NOW() BETWEEN inicio AND fim',
                    'valor' => NULL,
                    'sinal' => false
                )
            )
        ));

        if(!$this->data['profissional']) {
            redirect('');
        }

        $data = array(
            'select' => 'id',
            'row' => true,
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata('id')
                ),
                array(
                    'campo' => 'id_profissional',
                    'valor' => $this->data['profissional']->id_profissional
                ),
                array(
                    'campo' => 'tipo',
                    'valor' => 'd'
                ),
                array(
                    'campo' => 'DATE_FORMAT(data, "%d/%m/%Y")',
                    'valor' => "'" . date('d/m/Y') . "'",
                    'escape' => true
                )
            )
        );

       

        $this->db->query('SET lc_time_names = "pt_BR"');
        $clausulas = array(
            'select' => 'nota, comentario, DATE_FORMAT(data, "%d de %M de %Y") AS data, nome',
            'condicoes' => array(
                array(
                    'campo' => 'nota.status',
                    'valor' => 'a'
                ),
                array(
                    'campo' => 'id_profissional',
                    'valor' => $this->data['profissional']->id_profissional
                )
            ),
            'join' => array(
                array(
                    'tabela' => 'usuario',
                    'on' => 'usuario.id_usuario = nota.id_usuario'
                )
            ),
            'order' => array(
                array(
                    'campo' => 'data',
                    'valor' => 'DESC',
                )
            ),
            'limit' => array(
                'page' => $this->input->get('pagina'),
                'qtde' => $this->qtde
            )
        );

        $this->data['avaliacoes'] = $this->superModel->select('nota', $clausulas);

        $clausulas = array(
            'condicoes' => array(
                array(
                    'campo' => 'status',
                    'valor' => 'a'
                ),
                array(
                    'campo' => 'img',
                    'valor' => '',
                    'sinal' => '<>'
                ),
                array(
                    'campo' => 'id_profissional',
                    'valor' => $this->data['profissional']->id_profissional
                )
            ),
            'join' => array(
                array(
                    'tabela' => 'links',
                    'on' => 'links.id_links = profissional_links.id_links'
                )
            )
        );

        $profissionalLinks = $this->superModel->select('profissional_links', $clausulas);

        foreach($profissionalLinks as $key => $link) {
            $data = array(
                'row' => TRUE,
                'condicoes' => array(
                    array(
                        'campo' => 'planos_relacao.id_planos',
                        'valor' => $this->data['profissional']->id_planos
                    ),
                    array(
                        'campo' => 'opcao',
                        'valor' => 'link',
                        'like' => 'both'
                    )
                ),
                'join' => array(
                    array(
                        'tabela' => 'planos_opcoes',
                        'on' => 'planos_relacao.id_planos_opcoes = planos_opcoes.id_planos_opcoes'
                    )
                )
            );

            $consulta = $this->superModel->select('planos_relacao', $data);

            if(!$consulta) {
                unset($profissionalLinks[$key]);
            }
        }

        $this->data['profissionalLinks'] = $profissionalLinks;

        $clausulas = array(
            'select' => 'fotos.foto AS foto',
            'condicoes' => array(
                array(
                    'campo' => 'status',
                    'valor' => 'a'
                ),
                array(
                    'campo' => 'fotos.id_profissional',
                    'valor' => $this->data['profissional']->id_profissional
                )
            ),
            'join' => array(
                array(
                    'tabela' => 'profissional',
                    'on' => 'profissional.id_profissional = fotos.id_profissional'
                )
            ),
            'order' => array(
                array(
                    'campo' => 'ordem',
                    'valor' => 'ASC',
                )
            ),
        );

        $this->data['fotos'] = $this->superModel->select('fotos', $clausulas);

        $clausulas = array(
            'row' => true,
            'select' => 'round(floor(avg(nota) * 2) / 2, 1) AS nota',
            'condicoes' => array(
                array(
                    'campo' => 'nota.status',
                    'valor' => 'a'
                ),
                array(
                    'campo' => 'id_profissional',
                    'valor' => $this->data['profissional']->id_profissional
                )
            )
        );

        $this->data['profissional']->nota = $this->superModel->select('nota', $clausulas)->nota;

        if($this->data['profissional']->nota) {
            $condicoes = array(
                'condicoes' => array(
                    array(
                        'campo' => 'status',
                        'valor' => 'a'
                    ),
                    array(
                        'campo' => 'id_profissional',
                        'valor' => $this->data['profissional']->id_profissional
                    )
                )
            );

            $this->data['profissional']->totalAvaliacoes = $this->superModel->count('nota', $condicoes);

            $config['base_url'] = base_url($this->uri->uri_string() . '?');
            $config['query_string_segment'] = 'pagina';
            $config['full_tag_open'] = '<ul class="pagination"><li></li>';
            $config['full_tag_close'] = '<li></li></ul>';
            $config['prev_tag_open'] = '<li>';
            $config['prev_link'] = '«';
            $config['prev_tag_close'] = '</li>';
            $config['next_tag_open'] = '<li>';
            $config['next_link'] = '»';
            $config['next_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="active"><a href="#">';
            $config['cur_tag_close'] = '</a></li>';
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';
            $config['first_link'] = FALSE;
            $config['last_link'] = FALSE;
            $config['page_query_string'] = TRUE;
            $config['total_rows'] = $this->data['profissional']->totalAvaliacoes;
            $config['per_page'] = $this->qtde;
            $this->pagination->initialize($config);
        }

        $data = array(
            'row' => TRUE,
            'select' => 'valor AS texto',
            'condicoes' => array(
                array(
                    'campo' => 'campo',
                    'valor' => 'como_avaliar'
                )
            )
        );

        $this->data['comoAvaliar'] = $this->superModel->select('configuracoes', $data);

		$this->layout->view('anuncio', $this->data);
	}

    public function avaliar() {
        if($this->form_validation->run('profissional/avaliar')) {
            $data = array(
                'select' => 'id_profissional',
                'row' => TRUE,
                'condicoes' => array(
                    array(
                        'campo' => 'url',
                        'valor' => $this->input->post('url')
                    ),
                    array(
                        'campo' => 'status',
                        'valor' => $this->input->post('a')
                    )
                )
            );

            $data = $this->superModel->select('profissional', $data);

            if(!$data || !$this->session->userdata('id')) {
                redirect($_SERVER['HTTP_REFERER']);
            }

            $idProfissional = $data->id_profissional;

            $data = array(
                'id_profissional' => $idProfissional,
                'id_usuario' => $this->session->userdata('id'),
                'nota' => $this->input->post('nota'),
                'comentario' => $this->input->post('comentario'),
                'status' => 'i',
                'data' => now()
            );

            $this->db->insert('nota', $data);

            $this->session->set_flashdata('msg', 'Sua avaliação foi enviada com sucesso! Em breve ela será publicada!');
        }

        redirect($_SERVER['HTTP_REFERER']);
    }
}
