<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuarios extends MY_Restrita {
    public function __construct() {
        parent::__construct();
        insertTag('js', 'jquery.maskedinput.min.js', $this->data);
    }

    public function cadastrarProfissional() {
        $clausulas = array(
            'condicoes' => array(
                array(
                    'campo' => 'status',
                    'valor' => 'a'
                )
            )
        );

        $this->data['links'] = $this->superModel->select('links', $clausulas);
        $this->data['planos'] = $this->superModel->select('planos', $clausulas);
        $this->data['estados'] = $this->superModel->select('estado');

        $clausulas['order'] = array(
            array(
                'campo' => 'categoria'
            )
        );

        $this->data['listaCategorias'] = $this->superModel->select('categoria', $clausulas);
        $this->data['titulo'] = 'Novo profissional';
        $this->layout->view('restrita/profissionais/cadastrar', $this->data);
    }

    public function galeria($id) {
        insertTag('css', base_url('assets/plugins/dropzone/css/dropzone.css'), $this->data);
        insertTag('js', base_url('assets/plugins/dropzone/dropzone.js'), $this->data);
        insertTag('js', base_url('assets/plugins/jquery-ui.js'), $this->data);

        $clausulas = array(
            'row' => true,
            'select' => 'usuario.nome, profissional.id_profissional, plano',
            'condicoes' => array(
                array(
                    'campo' => 'usuario.id_usuario',
                    'valor' => $id
                )
            ),
            'join' => array(
                array(
                    'tabela' => 'profissional',
                    'on' => 'profissional.id_usuario = usuario.id_usuario'
                ),
                array(
                    'tabela' => 'profissional_fatura',
                    'on' => 'profissional_fatura.id_profissional = profissional.id_profissional'
                ),
                array(
                    'tabela' => 'planos',
                    'on' => 'planos.id_plano = profissional_fatura.id_planos'
                )
            ),
        );

        $this->data['usuario'] = $this->superModel->select('usuario', $clausulas);

        $data = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_profissional',
                    'valor' => $this->data['usuario']->id_profissional
                )
            ),
            'order' => array(
                array(
                    'campo' => 'ordem'
                )
            )
        );

        $this->data['fotos'] = $this->superModel->select('fotos', $data);

        $data = array(
            'row' => 'true',
            'select' => 'valor as maxFotos',
            'condicoes' => array(
                array(
                    'campo' => 'campo',
                    'valor' => 'max_fotos'
                ),
                array(
                    'campo' => 'complemento',
                    'valor' => $this->data['usuario']->plano
                )
            )
        );

        $this->data['maxFotos'] = (int) $this->superModel->select('configuracoes', $data)->maxFotos - count($this->data['fotos']);
        $this->data['titulo'] = 'Galeria de Fotos / ' . $this->data['usuario']->nome;
        $this->layout->view('restrita/profissionais/galeria', $this->data);
    }

    public function editarProfissional($id) {
        $this->data['meses'] = array('Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');

        $clausulas = array(
            'condicoes' => array(
                array(
                    'campo' => 'status',
                    'valor' => 'a'
                )
            )
        );

        $this->data['links'] = $this->superModel->select('links', $clausulas);
        $this->data['planos'] = $this->superModel->select('planos', $clausulas);
        $this->data['estados'] = $this->superModel->select('estado');

        $clausulas['order'] = array(
            array(
                'campo' => 'categoria'
            )
        );

        $this->data['listaCategorias'] = $this->superModel->select('categoria', $clausulas);

        $clausulas = array(
            'row' => true,
            'select' => 'usuario.id_usuario AS id_usuario, profissional_fatura.status AS status, usuario.nome, usuario.email, usuario.cpf, usuario.telefone, usuario.telefone2, profissional.descricao, profissional.foto, id_cidade, bairro, endereco, numero, complemento, profissional_fatura.id_profissional, id_planos, id_subcategoria, total, preco, DATE_FORMAT(inicio, "%d/%m/%Y %H:%i:%s") AS inicio, DATE_FORMAT(fim, "%d/%m/%Y %H:%i:%s") AS fim, id_transacao, situacao, id_usuario_endereco, id_profissional_fatura',
            'condicoes' => array(
                array(
                    'campo' => 'usuario.id_usuario',
                    'valor' => $id
                )
            ),
            'join' => array(
                array(
                    'tabela' => 'profissional',
                    'on' => 'profissional.id_usuario = usuario.id_usuario'
                ),
                array(
                    'tabela' => 'tipo_usuario',
                    'on' => 'tipo_usuario.id_tipo_usuario = usuario.id_tipo_usuario'
                ),
                array(
                    'tabela' => 'profissional_fatura',
                    'on' => 'profissional_fatura.id_profissional = profissional.id_profissional'
                ),
                array(
                    'tabela' => 'usuario_endereco',
                    'on' => 'usuario_endereco.id_usuario = usuario.id_usuario',
                    'option' => 'left'
                ),
                array(
                    'tabela' => 'planos',
                    'on' => 'planos.id_plano = profissional_fatura.id_planos'
                )
            ),
            'order' => array(
                array(
                    'campo' => 'id_profissional_fatura',
                    'valor' => 'DESC'
                )
            )
        );

        $this->data['usuario'] = $this->superModel->select('usuario', $clausulas);

        foreach($this->data['links'] as $link) {
            $data = array(
                'row' => true,
                'select' => 'link',
                'condicoes' => array(
                    array(
                        'campo' => 'id_links',
                        'valor' => $link->id_links
                    ),
                    array(
                        'campo' => 'id_profissional',
                        'valor' => $this->data['usuario']->id_profissional
                    )
                )
            );

            $consulta = $this->superModel->select('profissional_links', $data);

            $this->data['usuario']->link[$link->nome] = $consulta ? $consulta->link : '';
        }

        if($this->data['usuario']->id_cidade) {
            $clausulas = array(
                'row' => true,
                'select' => 'estado',
                'condicoes' => array(
                    array(
                        'campo' => 'id',
                        'valor' => $this->data['usuario']->id_cidade
                    )
                )
            );

            $this->data['usuario']->id_estado = $this->superModel->select('cidade', $clausulas)->estado;
        } else {
            $this->data['usuario']->id_estado = '';
        }


        $clausulas = array(
            'select' => 'id, nome',
            'condicoes' => array(
                array(
                    'campo' => 'estado',
                    'valor' => $this->data['usuario']->id_estado
                )
            )
        );

        $this->data['cidades'] = $this->superModel->select('cidade', $clausulas);

        $clausulas = array(
            'row' => true,
            'select' => 'id_categoria',
            'condicoes' => array(
                array(
                    'campo' => 'id_subcategoria',
                    'valor' => $this->data['usuario']->id_subcategoria
                )
            )
        );

        $this->data['usuario']->id_categoria = $this->superModel->select('subcategoria', $clausulas)->id_categoria;

        $clausulas = array(
            'select' => 'id_subcategoria, subcategoria',
            'condicoes' => array(
                array(
                    'campo' => 'id_categoria',
                    'valor' => $this->data['usuario']->id_categoria
                )
            )
        );

        $this->data['subcategorias'] = $this->superModel->select('subcategoria', $clausulas);
        $this->data['titulo'] = 'Editar profissional';
        $this->layout->view('restrita/profissionais/editar', $this->data);
    }

    public function alterarProfissional() {
        if($this->form_validation->run('restrita/atualizaCadastroProfissional')) {
            $idUsuario = $this->input->post('id_usuario');

            $usuario = array(
                'nome' => $this->input->post('nome'),
                'email' => $this->input->post('email'),
                'cpf' => $this->input->post('cpf'),
                //'nascimento' => convertData($this->input->post('nascimento')),
                'telefone' => $this->input->post('telefone'),
               // 'celular' => $this->input->post('celular')
            );

            $condicoes = array(
                'select' => 'id_usuario',
                'row' => TRUE,
                'condicoes' => array(
                    array(
                        'campo' => 'id_usuario',
                        'valor' => $idUsuario,
                        'sinal' => '<>'
                    ),
                    array(
                        'campo' => '(email = "' . $usuario['email'] . '" OR cpf = "' . $usuario['cpf'] . '")',
                        'valor' => NULL,
                        'sinal' => false,
                    )
                )
            );

            $consulta = $this->superModel->select('usuario', $condicoes);

            if($consulta) {
                $this->session->set_flashdata('erro', 'true');
                $this->session->set_flashdata('msg', 'Email e/ou CPF já cadastrados!');
                redirect('restrita/usuarios/profissionais');
            }

            if($this->input->post('senha')) {
                $usuario['senha'] = Bcrypt::hash($this->input->post('senha'));
            }

            $condicoes = array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $idUsuario,
                )
            );

            $this->superModel->update('usuario', $usuario, $condicoes);

            $endereco = array(
                'id_cidade' => $this->input->post('cidade'),
                'bairro' => $this->input->post('bairro'),
                'endereco' => $this->input->post('endereco'),
                'numero' => $this->input->post('numero'),
                'complemento' => $this->input->post('complemento'),
            );

            if($this->input->post('id_usuario_endereco')) {
                $condicoes = array(
                    array(
                        'campo' => 'id_usuario_endereco',
                        'valor' => $this->input->post('id_usuario_endereco'),
                    )
                );

                $this->superModel->update('usuario_endereco', $endereco, $condicoes);
            } else {
                $endereco['id_usuario'] = $idUsuario;
                $this->superModel->insert('usuario_endereco', $endereco);
            }


            $url = $this->superModel->getUrlAmigavel($this->input->post('nome'), 'tbl_profissional', 'url', 'id_profissional', $this->input->post('id_profissional'));
            $foto = $this->input->post('fotoAntiga');

            if(!$_FILES['foto']['error']) {
                if($foto) {
                    unlink(DIR_PROFISSIONAL . $foto);
                }

                $data = array(
                    'dir' => './uploads/profissionais/',
                    'fileName' => $url,
                    'inputName' => 'foto',
                );

                $foto = $this->realizaUpload($data, true);
            }

            $profissional = array(
                'id_subcategoria' => $this->input->post('subcategoria'),
                'descricao' => $this->input->post('descricao'),
                'url' => $url,
                'foto' => $foto
            );

            $condicoes = array(
                array(
                    'campo' => 'id_profissional',
                    'valor' => $this->input->post('id_profissional'),
                )
            );

            $this->superModel->update('profissional', $profissional, $condicoes);

            foreach($this->input->post('links') as $idLinks => $link) {
                $data = array(
                    'row' => TRUE,
                    'select' => 'id_profissional_links',
                    'condicoes' => array(
                        array(
                            'campo' => 'id_links',
                            'valor' => $idLinks
                        ),
                        array(
                            'campo' => 'id_profissional',
                            'valor' => $this->input->post('id_profissional')
                        )
                    )
                );

                $consulta = $this->superModel->select('profissional_links', $data);
                $idProfissionalLinks = $consulta ? $consulta->id_profissional_links : '';

                if($link) {
                    if($idProfissionalLinks) {
                        $data = array(
                            'link' => $link
                        );

                        $condicoes = array(
                            array(
                                'campo' => 'id_profissional_links',
                                'valor' => $idProfissionalLinks
                            )
                        );

                        $this->superModel->update('profissional_links', $data, $condicoes);
                    } else {
                        $data = array(
                            'id_links' => $idLinks,
                            'id_profissional' => $this->input->post('id_profissional'),
                            'link' => $link
                        );

                        $this->superModel->insert('profissional_links', $data);
                    }
                } else {
                    if($idProfissionalLinks) {
                        $condicoes = array(
                                'campo' => 'id_profissional_links',
                                'valor' => $idProfissionalLinks
                        );

                        $this->superModel->delete('profissional_links', $condicoes);
                    } else {
                        $data = array(
                            'id_links' => $idLinks,
                            'id_profissional' => $this->input->post('id_profissional'),
                            'link' => $link
                        );

                        $this->superModel->insert('profissional_links', $data);
                    }
                }
            }

            $clausulas = array(
                'select' => 'dias, preco',
                'row' => true,
                'condicoes' => array(
                    array(
                    'campo' => 'status',
                    'valor' => 'a'
                    ),
                    array(
                        'campo' => 'id_plano',
                        'valor' => $this->input->post('plano')
                    )
                )
            );

            $plano = $this->superModel->select('planos', $clausulas);

            $fatura = array(
                'id_planos' => $this->input->post('plano'),
                'inicio' => convertData($this->input->post('inicio')),
                'fim' => convertData($this->input->post('fim')),
                'total' => $plano->preco,
                'situacao' => $this->input->post('situacao'),
                'status' => $this->input->post('status'),
                'id_transacao' => $this->input->post('id_transacao')
            );

            $condicoes = array(
                array(
                    'campo' => 'id_profissional_fatura',
                    'valor' => $this->input->post('id_profissional_fatura'),
                )
            );

            $this->superModel->update('profissional_fatura', $fatura, $condicoes);

            $this->session->set_flashdata('msg', $this->lang->line('cadastro_atualizado'));
            redirect('restrita/usuarios/profissionais');
        }
    }

    public function inserirProfissional() {
        if($this->form_validation->run('cadastro')) {
            $clausulas = array(
                'select' => 'id_usuario',
                'row' => TRUE,
                'condicoes' => array(
                    array(
                        'campo' => '(email = "' . $this->input->post('email') . '" OR cpf = "' . $this->input->post('cpf') . '")',
                        'valor' => NULL,
                        'sinal' => false,
                    )
                )
            );

            $consulta = $this->superModel->select('usuario', $clausulas);

            if($consulta) {
                $this->session->set_flashdata('erro', 'true');
                $this->session->set_flashdata('msg', $this->lang->line('registro_ja_cadastrado'));
                redirect('restrita/usuarios/profissionais');
            }

            $clausulas = array(
                'select' => 'id_tipo_usuario',
                'row' => true,
                'condicoes' => array(
                    array(
                    'campo' => 'status',
                    'valor' => 'a'
                    ),
                    array(
                        'campo' => 'tipo_usuario',
                        'valor' => 'Profissional'
                    )
                )
            );

            $usuario = array(
                'id_tipo_usuario' => $this->superModel->select('tipo_usuario', $clausulas)->id_tipo_usuario,
                'nome' => $this->input->post('nome'),
                'email' => $this->input->post('email'),
                'senha' => Bcrypt::hash($this->input->post('senha')),
                'cpf' => $this->input->post('cpf'),
                //'nascimento' => convertData($this->input->post('nascimento')),
                'telefone' => $this->input->post('telefone'),
                //'celular' => $this->input->post('celular'),
                'status' => 'a',
                'confirmado' => 's'
            );

            $idUsuario = $this->superModel->insert('usuario', $usuario);

            $endereco = array(
                'id_usuario' => $idUsuario,
                'id_cidade' => $this->input->post('cidade'),
                'bairro' => $this->input->post('bairro'),
                'endereco' => $this->input->post('endereco'),
                'numero' => $this->input->post('numero'),
                'complemento' => $this->input->post('complemento'),
            );

            $this->superModel->insert('usuario_endereco', $endereco);

            $url = $this->superModel->getUrlAmigavel($this->input->post('nome'), 'tbl_profissional', 'url');
            $foto = '';

            if(!$_FILES['foto']['error']) {
                $data = array(
                    'dir' => './uploads/profissionais/',
                    'fileName' => $url,
                    'inputName' => 'foto',
                );

                $foto = $this->realizaUpload($data, true);
            }

            $profissional = array(
                'id_usuario' => $idUsuario,
                'id_subcategoria' => $this->input->post('subcategoria'),
                'descricao' => $this->input->post('descricao'),
                'url' => $url,
                'foto' => $foto,
                'status' => 'a'
            );

            $idProfissional = $this->superModel->insert('profissional', $profissional);

            foreach($this->input->post('links') as $idLinks => $link) {
                if($link) {
                    $data = array(
                        'id_links' => $idLinks,
                        'id_profissional' => $idProfissional,
                        'link' => $link
                    );

                    $this->superModel->insert('profissional_links', $data);
                }
            }

            $clausulas = array(
                'select' => 'plano, dias, preco',
                'row' => true,
                'condicoes' => array(
                    array(
                    'campo' => 'status',
                    'valor' => 'a'
                    ),
                    array(
                        'campo' => 'id_plano',
                        'valor' => $this->input->post('plano')
                    )
                )
            );

            $plano = $this->superModel->select('planos', $clausulas);
            $now = date('Y-m-d H:i:s');

            $fatura = array(
                'id_profissional' => $idProfissional,
                'id_planos' => $this->input->post('plano'),
                'inicio' => $now,
                'fim' => date('Y-m-d H:i:s', strtotime($now . " + 31 days")),
                'total' => $plano->preco,
                'situacao' => 'p',
                'status' => 'i'
            );

            $idFatura = $this->superModel->insert('profissional_fatura', $fatura);

            $this->session->set_flashdata('msg', $this->lang->line('cadastro_inserido'));

            $data = array(
                'row' => true,
                'select' => 'valor AS maxFotos',
                'condicoes' => array(
                    array(
                        'campo' => 'campo',
                        'valor' => 'max_fotos',
                    ),
                    array(
                        'campo' => 'complemento',
                        'valor' => $plano->plano,
                    )
                )
            );

            $maxFotos = $this->superModel->select('configuracoes', $data)->maxFotos;
            $url = 'restrita/usuarios/' . ($maxFotos ? 'galeria/' . $idUsuario : 'profissionais');
            redirect($url);
        }
    }

    public function profissionais() {
        $clausulas = array(
            'select' => 'usuario.id_usuario AS id_usuario, profissional_fatura.status AS status, profissional.id_profissional, usuario.nome, usuario.email, plano, DATE_FORMAT(fim, "%d/%m/%Y") as expira, id_profissional_fatura, situacao',
            'condicoes' => array(
                array(
                    'campo' => 'usuario.status',
                    'sinal' => '<>',
                    'valor' => 'd'
                ),
                array(
                    'campo' => 'profissional.status',
                    'sinal' => '<>',
                    'valor' => 'd'
                ),
                array(
                    'campo' => 'tipo_usuario',
                    'valor' => 'Profissional'
                )
            ),
            'join' => array(
                array(
                    'tabela' => 'profissional',
                    'on' => 'profissional.id_usuario = usuario.id_usuario'
                ),
                array(
                    'tabela' => 'tipo_usuario',
                    'on' => 'tipo_usuario.id_tipo_usuario = usuario.id_tipo_usuario'
                ),
                array(
                    'tabela' => 'profissional_fatura',
                    'on' => 'profissional_fatura.id_profissional = profissional.id_profissional'
                ),
                array(
                    'tabela' => 'planos',
                    'on' => 'planos.id_plano = profissional_fatura.id_planos'
                )
            ),
            'order' => array(
                array(
                    'campo' => 'id_profissional_fatura',
                    'valor' => 'DESC'
                )
            )
        );

        $this->data['usuarios'] = $this->superModel->select('usuario', $clausulas);
        $this->data['titulo'] = 'Profissionais';

        $this->layout->view('restrita/profissionais/listar', $this->data);
    }

    public function listar() { 
       
    $clausulas = array(
            'select' => 'id_usuario, usuario.status, nome, email, tipo_usuario.tipo_usuario as tipo',
            'join' => array(
                    array(
                        'tabela' => 'tipo_usuario', 
                        'option' => 'left',
                        'on'     => 'usuario.id_tipo_usuario = tipo_usuario.id_tipo_usuario  '
                    )
             ),       

            'condicoes' => array(
                array(
                    'campo' => 'usuario.status',
                    'sinal' => '<>',
                    'valor' => 'd'
                ),
                 
                 array(
                    'campo' => 'usuario.id_tipo_usuario',
                    'sinal' => '=',
                    'valor' => '5'
                )
            )
        );

        //$this->data['usuariosEmpresa'] = $this->superModel->select('usuario', $clausulas);
        //$this->data['titulo'] = 'Usuários';
    
        $empresas= $this->superModel->select('usuario', $clausulas);
           
        
         $this->data['empresas'] = $empresas;        


        $this->layout->view('restrita/usuarios/listar', $this->data);
}

public function listar_usuarios($id){ 
       
           
           
        $sql1 = "SELECT a.*, e.*, q.tipo_usuario as tipo "
                 . " FROM tbl_usuario a "
                 . " LEFT JOIN tbl_requerente e ON (e.id_usuario = a.id_usuario) "
                 . " LEFT JOIN tbl_tipo_usuario q ON (q.id_tipo_usuario = a.id_tipo_usuario) "
                 . " WHERE a.status <> 'r' "
                 . " AND e.id_usuario_empresa=".$id;
                
        $sql2 = "SELECT a.*, e.*, q.tipo_usuario as tipo "
                 . " FROM tbl_usuario a "
                 . " LEFT JOIN tbl_aprovador e ON (e.id_usuario = a.id_usuario) "
                 . " LEFT JOIN tbl_tipo_usuario q ON (q.id_tipo_usuario = a.id_tipo_usuario) "
                 . " WHERE a.status <> 'r' "
                 . " AND e.id_usuario_empresa=".$id;
                
                $aprovadores = $this->superModel->query($sql2);
                 $requerentes= $this->superModel->query($sql1);
                 $this->data['aprovadores'] = $aprovadores;       
                 $this->data['requerentes'] = $requerentes;      


                 $this->layout->view('restrita/usuarios/listar_usuarios_da_empresa', $this->data);



}

    public function editar($id) {
        $this->data['estados'] = $this->superModel->select('estado');

        $clausulas = array(
            'row' => true,
            'select' => 'usuario.id_usuario, nome, email, cpf,telefone, id_cidade, bairro, endereco, numero, complemento, id_usuario_endereco',
            'condicoes' => array(
                array(
                    'campo' => 'usuario.id_usuario',
                    'valor' => $id
                )
            ),
            'join' => array(
                array(
                    'tabela' => 'usuario_endereco',
                    'on' => 'usuario_endereco.id_usuario = usuario.id_usuario',
                    'option' => 'left'
                )
            ),
        );

        $this->data['usuario'] = $this->superModel->select('usuario', $clausulas);
       
        if(isset($this->data['usuario']->id_cidade)) {
            $clausulas = array(
                'row' => true,
                'select' => 'estado',
                'condicoes' => array(
                    array(
                        'campo' => 'id',
                        'valor' => $this->data['usuario']->id_cidade
                    )
                )
            );

            $this->data['usuario']->id_estado = $this->superModel->select('cidade', $clausulas)->estado;

            $clausulas = array(
                'select' => 'id, nome',
                'condicoes' => array(
                    array(
                        'campo' => 'estado',
                        'valor' => $this->data['usuario']->id_estado
                    )
                )
            );

            $this->data['cidades'] = $this->superModel->select('cidade', $clausulas);
        }

        $this->data['titulo'] = 'Editar usuário';
     
        $this->layout->view('restrita/usuarios/editar', $this->data);
    }
    
    public function editar_usuarios_da_empresa($id) {
      
        $this->data['estados'] = $this->superModel->select('estado');

        $clausulas = array(
            'row' => true,
            'select' => 'usuario.id_usuario, usuario.id_tipo_usuario,nome, email, cpf,telefone, id_cidade, bairro, endereco, numero, complemento, id_usuario_endereco',
            'condicoes' => array(
                array(
                    'campo' => 'usuario.id_usuario',
                    'valor' => $id
                )
            ),
            'join' => array(
                array(
                    'tabela' => 'usuario_endereco',
                    'on' => 'usuario_endereco.id_usuario = usuario.id_usuario',
                    'option' => 'left'
                )
            ),
        );
         $usuario = $this->superModel->select('usuario', $clausulas);
         $this->data['usuario'] =  $this->superModel->select('usuario', $clausulas);

        if(isset($this->data['usuario']->id_cidade)) {
          
            $clausulas = array(
                'row' => true,
                'select' => 'estado',
                'condicoes' => array(
                    array(
                        'campo' => 'id',
                        'valor' => $this->data['usuario']->id_cidade
                    )
                )
            );

            $this->data['usuario']->id_estado = $this->superModel->select('cidade', $clausulas)->estado;

            $clausulas = array(
                'select' => 'id, nome',
                'condicoes' => array(
                    array(
                        'campo' => 'estado',
                        'valor' => $this->data['usuario']->id_estado
                    )
                )
            );

            $this->data['cidades'] = $this->superModel->select('cidade', $clausulas);
        }

          $clausulas2 = array(
                'row' => true,
                'condicoes' => array(
                    array(
                        'campo' => 'id_usuario',
                        'valor' => $id
                    )
                )
            );


        if($usuario->id_tipo_usuario == 4){
       
        $idEmpresa = $this->superModel->select('requerente', $clausulas2)->id_usuario_empresa;
    
        }else if ($usuario->id_tipo_usuario == 6) {
       
        $idEmpresa = $this->superModel->select('aprovador', $clausulas2)->id_usuario_empresa;
        }
        
        $this->data['id'] = $idEmpresa;
        $this->data['titulo'] = 'Editar usuário';
        $this->layout->view('restrita/usuarios/editar_usuarios_da_empresa', $this->data);
    }
    
    public function alterar() {
        if($this->form_validation->run('restrita/atualizaCadastro')) {
            $idUsuario = $this->input->post('id_usuario');

            $usuario = array(
                'nome' => $this->input->post('nome'),
                'email' => $this->input->post('email'),
                'cpf' => $this->input->post('cpf'),
               // 'nascimento' => convertData($this->input->post('nascimento')),
                'telefone' => $this->input->post('telefone'),
               // 'celular' => $this->input->post('celular')
            );

            $condicoes = array(
                'select' => 'id_usuario',
                'row' => TRUE,
                'condicoes' => array(
                    array(
                        'campo' => 'id_usuario',
                        'valor' => $idUsuario,
                        'sinal' => '<>'
                    ),
                    array(
                        'campo' => '(email = "' . $usuario['email'] . '" OR cpf = "' . $usuario['cpf'] . '")',
                        'valor' => NULL,
                        'sinal' => false,
                    )
                )
            );

            $consulta = $this->superModel->select('usuario', $condicoes);

            if($consulta) {
                $this->session->set_flashdata('erro', 'true');
                $this->session->set_flashdata('msg', 'Email e/ou CPF já cadastrados!');
                redirect('restrita/usuarios/listar');
            }

            if($this->input->post('senha')) {
                $usuario['senha'] = Bcrypt::hash($this->input->post('senha'));
            }

            $condicoes = array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $idUsuario,
                )
            );

            $this->superModel->update('usuario', $usuario, $condicoes);

            $endereco = array(
                'id_cidade' => $this->input->post('cidade'),
                'bairro' => $this->input->post('bairro'),
                'endereco' => $this->input->post('endereco'),
                'numero' => $this->input->post('numero'),
                'complemento' => $this->input->post('complemento'),
            );

            if($this->input->post('id_usuario_endereco')) {
                $condicoes = array(
                    array(
                        'campo' => 'id_usuario_endereco',
                        'valor' => $this->input->post('id_usuario_endereco'),
                    )
                );

                $this->superModel->update('usuario_endereco', $endereco, $condicoes);
            } else {
                $endereco['id_usuario'] = $idUsuario;
                $this->superModel->insert('usuario_endereco', $endereco);
            }

            $this->session->set_flashdata('msg', $this->lang->line('cadastro_atualizado'));
            redirect('restrita/usuarios/listar');
        }
    }

    public function cadastrar() {
        $this->data['estados'] = $this->superModel->select('estado');

        $this->data['titulo'] = 'Novo usuário';
        $this->layout->view('restrita/usuarios/cadastrar', $this->data);
    }

    public function inserir() {
        if($this->form_validation->run('restrita/cadastroUsuario')) {
            $clausulas = array(
                'select' => 'id_usuario, status',
                'row' => true,
                'condicoes' => array(
                    array(
                        'campo' => '(email = "' . $this->input->post('email') . '" OR cpf = "' . $this->input->post('cpf') . '")',
                        'valor' => NULL,
                        'sinal' => false
                    )
                )
            );

            $consulta = $this->superModel->select('usuario', $clausulas);
            $this->data['estados'] = $this->superModel->select('estado');


            $usuario = array(
                'nome' => $this->input->post('nome'),
                'email' => $this->input->post('email'),
                'senha' => Bcrypt::hash($this->input->post('senha')),
                'cpf' => $this->input->post('cpf'),
               // 'nascimento' => convertData($this->input->post('nascimento')),
                'telefone' => $this->input->post('telefone'),
               // 'celular' => $this->input->post('celular'),
                'status' => 'a',
                'confirmado' => 's'
            );

            if($consulta) {
                $this->session->set_flashdata('erro', 'true');
                $this->session->set_flashdata('msg', $this->lang->line('registro_ja_cadastrado'));
                redirect('restrita/usuarios/listar');
            }

            $clausulas = array(
                'select' => 'id_tipo_usuario',
                'row' => true,
                'condicoes' => array(
                    array(
                        'campo' => 'status',
                        'valor' => 'a'
                    ),
                    array(
                        'campo' => 'tipo_usuario',
                        'valor' => 'Comum'
                    )
                )
            );

            $usuario['id_tipo_usuario'] = $this->superModel->select('tipo_usuario', $clausulas)->id_tipo_usuario;

            $idUsuario = $this->superModel->insert('usuario', $usuario);

            $clausulas = array(
                'select' => 'id_usuario_endereco',
                'row' => true,
                'condicoes' => array(
                    array(
                        'campo' => 'id_usuario',
                        'valor' => $idUsuario
                    )
                )
            );

            $consulta = $this->superModel->select('usuario_endereco', $clausulas);

            $endereco = array(
                'id_usuario' => $idUsuario,
                'id_cidade' => $this->input->post('cidade'),
                'bairro' => $this->input->post('bairro'),
                'endereco' => $this->input->post('endereco'),
                'numero' => $this->input->post('numero'),
                'complemento' => $this->input->post('complemento'),
            );

            if(!$consulta) {
                $this->superModel->insert('usuario_endereco', $endereco);
            } else {
                $clausulas = array(
                    'condicoes' => array(
                        'campo' => 'id_usuario_endereco',
                        'valor' => $consulta->id_usuario_endereco
                    )
                );

                $this->superModel->update('usuario_endereco', $endereco, $clausulas);
            }

            $this->session->set_flashdata('msg', $this->lang->line('cadastro_inserido'));
            redirect('restrita/usuarios/listar');
        }
    }
}
