<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
  manu onde esta esta função actionPeddio
 */

class Ordem extends MY_Restrita {

    private $id_manutencao = 1;
    private $CategoriaServico;
    private $Servicos;

    public function __construct() {
        parent::__construct();
    }

    public function index($funcao) {

        $clausulas = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata('id')
                )
            )
        );

        $idUsuario = $this->superModel->select('usuario', $clausulas);



        $this->session->set_userdata('url', $funcao);


        $this->data['categoria'] = $this->Menus->getMenu($this->id_manutencao);

        $subcategorias = $this->Menus->getSubmenu($this->id_manutencao);


        foreach ($subcategorias as $subcategoria) {

            if (removeAcentos($subcategoria->link) == removeAcentos($funcao)) {

                $this->data['subcategoria'] = $subcategoria;
                $viewsubcategorias = $this->Menus->getViewSubmenu($subcategoria->id_subcategoria);

                $this->data['viewsubcategoria'] = $viewsubcategorias;
                $this->data['link_subcategoria'] = $funcao;

                $this->CategoriaServico = new CategoriaServico();
                $this->Servicos = new Servicos();

                $categoria_servicos = $this->CategoriaServico->getCategoriaServico($this->id_manutencao, $subcategoria->id_subcategoria);
                foreach ($categoria_servicos as $key => $item_cate_servico) {
                    $servicos = $this->Servicos->getServico($item_cate_servico->id_categoria_servico);
                    $categoria_servicos[$key]->Servicos = $servicos;
                }

                $this->data['categoria_servicos'] = $categoria_servicos;


                $total_pedido = $this->superModel->query(
                        "SELECT COUNT(*) total_pedido "
                        . "  FROM tbl_pedido            "
                        . " WHERE status = 'a'         "
                        . "   AND id_usuario= " . $idUsuario[0]->id_usuario);

                $this->data['total_pedido'] = $total_pedido[0];
                $this->data['script_filtro'] = 1;


                $this->session->unset_userdata('msgOs');

                $this->layout->view('restrita/pedidosAutomatico', $this->data);
            }
        }
    }

    public function cancelar() {
        $clausulas = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata('id')
                )
            )
        );

        $idUsuario = $this->superModel->select('usuario', $clausulas);
        $data = array(
            'status' => 'c'
        );

        $condicao = array(
            array(
                'campo' => 'id_usuario',
                'valor' => $idUsuario[0]->id_usuario
            ),
            array(
                'sinal' => '=',
                'campo' => 'status',
                'valor' => 'a'
            )
        );

        $this->superModel->update('pedido', $data, $condicao);


        $this->data ['pedidos'] = NULL;
        $this->session->unset_userdata('nroOs');
        $this->session->unset_userdata('idOsUpdateTeste');
        $this->session->unset_userdata('msgOs');
        $this->layout->view('restrita/home', $this->data);
    }

    public function actionOs() {

        $clausulas = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata('id')
                )
            )
        );

        $idUsuario = $this->superModel->select('usuario', $clausulas);


        if (TRUE) {  //this->form_validation->run('restrita/ordem/actionOs')
            if ($idUsuario[0]->id_tipo_usuario == 5) {
                $stringNome = $idUsuario[0]->nome;
                $stringStatus = "Aprovada e Enviada";
                $this->session->set_userdata('msgOs', 'Sua O.S. foi Aprovada e Enviada!');
            } else if ($idUsuario[0]->id_tipo_usuario == 6) {
                $stringNome = $idUsuario[0]->nome;
                $stringStatus = "Aprovada e Enviada";
                $this->session->set_userdata('msgOs', 'Sua O.S. foi Aprovada e Enviada!');
            } else if ($idUsuario[0]->id_tipo_usuario == 4) {

                $stringNome = "Pendentes de Aprovação";
                $stringStatus = "Pendentes de Aprovação";
                $this->session->set_userdata('msgOs', 'Sua O.S. foi Enviada para Aprovação!');
            }



            $condicao = array(
                array(
                    'campo' => 'id_os',
                    'valor' => $this->session->userdata('idOsUpdateTeste')
                )
            );


            $data = array(
                // 'nro_os'             => $this->session->userdata('nroOs'),
                'nome_sol' => $this->session->userdata('nome'),
                'nome_aprov' => $stringNome,
                'descricao' => $this->input->post('descricao'),
                'localidade' => $this->input->post('setor'),
                'normas' => $this->input->post('normas'),
                'qualificacoes' => $this->input->post('qualificacoes'),
                'status' => $stringStatus,
                'prioridade' => $this->input->post('prioridade'),
                'data_os' => date("Y-m-d"),
                'hora_os' => date("H:i:s")
            );


            $this->superModel->update('os', $data, $condicao);



            $data = array(
                'status' => 'f'
            );

            $condicao = array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $idUsuario[0]->id_usuario
                ),
                array(
                    'sinal' => '=',
                    'campo' => 'status',
                    'valor' => 'a'
                )
            );

            $this->superModel->update('pedido', $data, $condicao);
            $this->session->unset_userdata('nroOs');
            $this->session->unset_userdata('idOsUpdateTeste');





            //redirect("restrita/home");
            $this->layout->view('restrita/home', $this->data);
            //redirect('restrita/ordem', $this->os);
        } else {
            $clausulas = array(
                'order' => array(
                    array(
                        'campo' => 'ordem',
                        'valor' => 'ASC'
                    )
                )
            );
            $data = array(
                'status' => 'f'
            );

            $condicao = array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $idUsuario[0]->id_usuario
                ),
                array(
                    'sinal' => '=',
                    'campo' => 'status',
                    'valor' => 'a'
                )
            );

            $this->superModel->update('pedido', $data, $condicao);
            $clausulas['order'] = array(
                array(
                    'campo' => 'categoria',
                )
            );

            $this->data['listaCategorias'] = $this->superModel->select('categoria', $clausulas);

            $this->session->unset_userdata('nroOs');
            $this->session->unset_userdata('idOsUpdateTeste');

            $this->layout->view('restrita/home', $this->data);
            // $this->actionPedido();
            //$this->index($this->session->userdata('url'));
        }
    }

    public function actionOsAlterar($idPedidoOrcamento) {

        if ($idPedidoOrcamento == "") {  //$this->form_validation->run('restrita/ordem/actionOs')
            echo "ID do pedido de orçamento inválido.";
        } else if (TRUE) {  //$this->form_validation->run('restrita/ordem/actionOs')
            $clausulas = array(
                'condicoes' => array(
                    array(
                        'campo' => 'id_usuario',
                        'valor' => $this->session->userdata('id')
                    )
                )
            );

            $idUsuario = $this->superModel->select('usuario', $clausulas);

            $condicao = array(
                array(
                    'campo' => 'id_pedido_de_orcamento',
                    'valor' => $idPedidoOrcamento
                )
            );

            $data = array(
                'descricao' => $this->input->post('descricao'),
                'localidade' => $this->input->post('setor'),
                'normas' => $this->input->post('normas'),
                'qualificacoes' => $this->input->post('qualificacoes'),
                'prioridade' => $this->input->post('prioridade'),
            );


            $this->superModel->update('pedido_de_orcamento', $data, $condicao);

            foreach ($this->input->post('qntd') as $idPedido => $qntd) {

                $data = array(
                    'qntd' => $qntd
                );

                $condicoes = array(
                    array(
                        'campo' => 'id_pedido',
                        'valor' => $idPedido
                    )
                );


                $this->superModel->update('pedido', $data, $condicoes);
            }

            $this->session->unset_userdata('idPedidoOrcamento');
            $this->session->unset_userdata('idOsUpdateTeste');
            //redirect("restrita/home");
            $this->layout->view('restrita/home', $this->data);
            //redirect('restrita/ordem', $this->os);
        } else {
            $clausulas = array(
                'order' => array(
                    array(
                        'campo' => 'ordem',
                        'valor' => 'ASC'
                    )
                )
            );

            $clausulas['order'] = array(
                array(
                    'campo' => 'categoria',
                )
            );

            $this->data['listaCategorias'] = $this->superModel->select('categoria', $clausulas);

            $this->session->unset_userdata('idOsUpdateTeste');

            $this->session->unset_userdata('msgOs');

            $this->layout->view('restrita/home', $this->data);
        }
    }

    public function OsManual($funcao = ' ') { //continuar
        $clausulas = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata('id')
                )
            )
        );

        $idUsuario = $this->superModel->select('usuario', $clausulas);



        $this->session->set_userdata('url', $funcao);


        $this->data['categoria'] = $this->Menus->getMenu($this->id_manutencao);

        $subcategorias = $this->Menus->getSubmenu($this->id_manutencao);


        foreach ($subcategorias as $subcategoria) {

            if (removeAcentos($subcategoria->link) == removeAcentos($funcao)) {

                $this->data['subcategoria'] = $subcategoria;
                $viewsubcategorias = $this->Menus->getViewSubmenu($subcategoria->id_subcategoria);

                $this->data['viewsubcategoria'] = $viewsubcategorias;
                $this->data['link_subcategoria'] = $funcao;

                $this->CategoriaServico = new CategoriaServico();
                $this->Servicos = new Servicos();

                $categoria_servicos = $this->CategoriaServico->getCategoriaServico($this->id_manutencao, $subcategoria->id_subcategoria);
                foreach ($categoria_servicos as $key => $item_cate_servico) {
                    $servicos = $this->Servicos->getServico($item_cate_servico->id_categoria_servico);
                    $categoria_servicos[$key]->Servicos = $servicos;
                }

                $this->data['categoria_servicos'] = $categoria_servicos;


                $total_pedido = $this->superModel->query(
                        "SELECT COUNT(*) total_pedido "
                        . "  FROM tbl_pedido            "
                        . " WHERE status = 'a'         "
                        . "   AND id_usuario= " . $idUsuario[0]->id_usuario);

                $this->data['total_pedido'] = $total_pedido[0];
                $this->data['script_filtro'] = 1;
            }
        }

        $pedidos = $this->getPedidos();
        $this->data ['pedidos'] = $pedidos;
        $this->data['script_increment'] = 1;


        if ($idUsuario[0]->id_tipo_usuario == 5) {

            $sql = "SELECT a.nome"
                    . " FROM tbl_setor a "
                    . " LEFT JOIN tbl_setor_empresa b ON (b.id_setor = a.id_setor) "
                    . " WHERE b.id_empresa=" . $idUsuario[0]->id_usuario
                    . " AND status <> 'Removido' ";

            $setores = $this->superModel->query($sql);
            $this->data['setores'] = $setores;

            //$this->data['nroOs'] = $this->geraNroOs($idUsuario[0]->id_usuario);
        } else if ($idUsuario[0]->id_tipo_usuario == 6) {

            $clausulasA = array(
                'condicoes' => array(
                    array(
                        'campo' => 'id_usuario',
                        'valor' => $idUsuario[0]->id_usuario
                    )
                )
            );

            $idAprovador = $this->superModel->select('aprovador', $clausulasA);
            $this->data['setor'] = $idAprovador[0]->setor;

            // $this->data['nroOs'] = $this->geraNroOs($idAprovador[0]->id_usuario_empresa);
        } else if ($idUsuario[0]->id_tipo_usuario == 4) {

            $clausulasR = array(
                'condicoes' => array(
                    array(
                        'campo' => 'id_usuario',
                        'valor' => $idUsuario[0]->id_usuario
                    )
                )
            );

            $idRequerente = $this->superModel->select('requerente', $clausulasR);
            $this->data['setor'] = $idRequerente[0]->setor;

            // $this->data['nroOs'] = $this->geraNroOs($idRequerente[0]->id_usuario_empresa);
        }




        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata('id')
                )
            )
        );

        $this->data['user'] = $this->superModel->select('usuario', $condicoes);

        $clausulasOs = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_os',
                    'valor' => $this->session->userdata('idOsUpdateTeste')
                )
            )
        );

        $os = $this->superModel->select('os', $clausulasOs);
        $this->data['nroOs'] = $os[0]->nro_os;
        $this->session->set_userdata('nroOs', $os[0]->nro_os);

        $this->session->unset_userdata('msgOs');
        $this->layout->view('restrita/ordemServicosManual', $this->data);
    }
//
//    public function actionRemoverPedido($idPedido) {
//        if ($idPedido != "") {
//            $data = array(
//                'status' => 'c',
//                'id_usuario_exclusao' => $this->session->userdata('id'),
//                'data_hora_exclusao' => date("Y-m-d H:i:s")
//            );
//
//            $condicao = array(
//                array(
//                    'campo' => 'id_pedido',
//                    'valor' => $idPedido
//                )
//            );
//
//            $this->superModel->update('pedido', $data, $condicao);
//            redirect($_SERVER['HTTP_REFERER']);
//        } else {
//            echo "Falha ao executar ação.";
//        }
//    }

    public function actionPedido($funcao = '') {

        $clausulas = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata('id')
                )
            )
        );
        $idUsuario = $this->superModel->select('usuario', $clausulas);



        $this->session->set_userdata('url', $funcao);


        $this->data['categoria'] = $this->Menus->getMenu($this->id_manutencao);

        $subcategorias = $this->Menus->getSubmenu($this->id_manutencao);


        foreach ($subcategorias as $subcategoria) {

            if (removeAcentos($subcategoria->link) == removeAcentos($funcao)) {

                $this->data['subcategoria'] = $subcategoria;
                $viewsubcategorias = $this->Menus->getViewSubmenu($subcategoria->id_subcategoria);

                $this->data['viewsubcategoria'] = $viewsubcategorias;
                $this->data['link_subcategoria'] = $funcao;

                $this->CategoriaServico = new CategoriaServico();
                $this->Servicos = new Servicos();

                $categoria_servicos = $this->CategoriaServico->getCategoriaServico($this->id_manutencao, $subcategoria->id_subcategoria);
                foreach ($categoria_servicos as $key => $item_cate_servico) {
                    $servicos = $this->Servicos->getServico($item_cate_servico->id_categoria_servico);
                    $categoria_servicos[$key]->Servicos = $servicos;
                }

                $this->data['categoria_servicos'] = $categoria_servicos;
            }
        }





        if (!$this->session->userdata('idOsUpdateTeste')) {


            if ($idUsuario[0]->id_tipo_usuario == 5) {


                $idUsuarioEmpresa = $idUsuario[0]->id_usuario;
            } else if ($idUsuario[0]->id_tipo_usuario == 6) {

                $clausulas = array(
                    'condicoes' => array(
                        array(
                            'campo' => 'id_usuario',
                            'valor' => $idUsuario[0]->id_usuario
                        )
                    )
                );

                $usuario = $this->superModel->select('aprovador', $clausulas);
                $idUsuarioEmpresa = $usuario[0]->id_usuario_empresa;
            } else if ($idUsuario[0]->id_tipo_usuario == 4) {
                $clausulas = array(
                    'condicoes' => array(
                        array(
                            'campo' => 'id_usuario',
                            'valor' => $idUsuario[0]->id_usuario
                        )
                    )
                );

                $usuario = $this->superModel->select('requerente', $clausulas);
                $idUsuarioEmpresa = $usuario[0]->id_usuario_empresa;
            }



            $os = array(
                'id_usuario_empresa' => $idUsuario[0]->id_usuario,
                'id_empresa' => $idUsuarioEmpresa
            );
            $idOs = $this->superModel->insert('os', $os);

            $this->session->set_userdata('idOsUpdateTeste', $idOs);
            $this->session->set_userdata('idOsUpdateTeste', $idOs);
            //  echo $this->session->userdata('idOsUpdateTeste');
        } else {

            $idOs = $this->session->userdata('idOsUpdateTeste');
        }

        $pedido = array(
            'id_usuario' => $this->session->userdata('id'),
            'id_servico' => $this->input->post('id_servico'),
            'id_os' => $idOs,
            'qntd' => $this->input->post('qntd'),
            'status' => 'a'
        );

        $idPedido = $this->superModel->insert('pedido', $pedido);
        $data['id_pedido'] = $idPedido;

        $total_pedido = $this->superModel->query(
                "SELECT COUNT(*) total_pedido "
                . "  FROM tbl_pedido            "
                . " WHERE status = 'a'         "
                . "   AND id_usuario= " . $idUsuario[0]->id_usuario);

        $this->data['total_pedido'] = $total_pedido[0];
        $this->data['script_filtro'] = 1;




        $idFiltros = $this->input->post('id_filtros');

        if (!empty($idFiltros)) {
            foreach ($idFiltros as $key => $idFiltro) {
                if (isset($idFiltro['id_filtro'])) {
                    $pedido_filtro = array(
                        'id_pedido' => $idPedido,
                        'id_filtro' => $idFiltro['id_filtro'],
                        'valor' => '',
                    );
                } else if (isset($idFiltro['value'])) {
                    $pedido_filtro = array(
                        'id_pedido' => $idPedido,
                        'id_filtro' => $key,
                        'valor' => $idFiltro['value'],
                    );
                }
                $this->superModel->insert('pedido_filtro', $pedido_filtro);
            }
        }


        $this->session->unset_userdata('msgOs');

        $this->layout->view('restrita/ordemServicosAutomatica', $this->data);
    }

    public function getFiltrosOrcamento($idServico) {
        $query = "SELECT a.id_pergunta, b.descricao AS pergunta, b.input_type "
                . " FROM tbl_filtro a "
                . " LEFT JOIN tbl_pergunta b ON (b.id_pergunta = a.id_pergunta) "
                . " WHERE a.id_servico = $idServico "
                . " AND a.status = 'a' "
                . " AND b.status = 'a' "
                . " GROUP BY 1, 2";

        $perguntas = $this->superModel->query($query);

        foreach ($perguntas as $chave => $pergunta) {
            $query = "SELECT a.id_filtro, a.descricao AS filtro "
                    . " FROM tbl_filtro a "
                    . " WHERE a.id_servico = $idServico "
                    . " AND a.id_pergunta = $pergunta->id_pergunta "
                    . " AND a.status = 'a'";
            $filtros = $this->superModel->query($query);

            $perguntas[$chave]->Filtros = $filtros;
        }
        $myJSON = json_encode($perguntas);
        echo $myJSON;
    }

    private function getPedidos() {
        //$mix = array();
        $data = array(
            'select' => 'id_pedido, id_usuario, id_servico, qntd',
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata('id')
                ),
                array(
                    'sinal' => '=',
                    'campo' => 'status',
                    'valor' => 'a'
                )
            )
        );

        $pedidos = $this->superModel->select('pedido', $data);

        foreach ($pedidos as $key => $pedido) {
            $sql = "SELECT c.descricao AS pergunta, b.descricao AS filtro, a.valor "
                    . " FROM tbl_pedido_filtro a "
                    . " LEFT JOIN tbl_filtro b ON (b.id_filtro = a.id_filtro) "
                    . " LEFT JOIN tbl_pergunta c ON (c.id_pergunta = b.id_pergunta) "
                    . " WHERE a.id_pedido = $pedido->id_pedido";
            $filtro = $this->superModel->query($sql);

            $pedidos[$key]->filtros = $filtro;

            $data = array(
                'select' => 'id_categoria_servico, descricao',
                'condicoes' => array(
                    array(
                        'campo' => 'id_servico',
                        'valor' => $pedido->id_servico
                    )
                )
            );

            $servico = $this->superModel->select('servico', $data);
            $servico = $servico[0];

            $pedidos[$key]->Servico = $servico;

            $data = array(
                'select' => 'id_categoria_servico, id_subcategoria, descricao, imagem',
                'condicoes' => array(
                    array(
                        'campo' => 'id_categoria_servico',
                        'valor' => $servico->id_categoria_servico
                    )
                )
            );

            $categoria_servico = $this->superModel->select('categoria_servico', $data);
            $categoria_servico = $categoria_servico[0];

            $pedidos[$key]->Servico->categoria = $categoria_servico;

            $data = array(
                'select' => 'subcategoria as descricao',
                'condicoes' => array(
                    array(
                        'campo' => 'id_subcategoria',
                        'valor' => $categoria_servico->id_subcategoria
                    )
                )
            );

            $subcategoria = $this->superModel->select('subcategoria', $data);
            $subcategoria = $subcategoria[0];

            $pedidos[$key]->Servico->categoria->subcategoria = $subcategoria;
        }

        //$mix[0] = $pedidos;

        return $pedidos;
    }

}
