<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Historico extends MY_Restrita {
//
//    public function index() {
//
//        insertTag('js', 'restrita/historico.js?v=1.0', $this->data);
//
//        $this->data['script_listar_os'] = 1;
//
//        $condicoes = array(
//            'condicoes' => array(
//                array(
//                    'campo' => 'id_usuario',
//                    'valor' => $this->session->userdata['id']
//                ),
//            )
//        );
//
//
//        $usuario = $this->superModel->select('usuario', $condicoes);
//
//        $clausulas = array(
//            'condicoes' => array(
//                array(
//                    'campo' => 'status',
//                    'sinal' => '<>',
//                    'valor' => 'Removida'
//                ),
//                array(
//                    'campo' => 'nome_sol',
//                    'valor' => $usuario[0]->nome
//                ),
//            )
//        );
//
//
//
//        if ($usuario[0]->id_tipo_usuario == 5) {
//
//            $sql = "SELECT a.id_pedido_de_orcamento, a.nro_pedido_de_orcamento, a.nome_sol, a.nome_aprov, a.id_usuario_empresa, a.descricao, a.localidade, a.normas, a.qualificacoes, a.status, a.prioridade, a.status_orc, a.data_pedido_de_orcamento  AS data, a.hora_pedido_de_orcamento AS hora"
//                    . " FROM tbl_pedido_de_orcamento a "
//                    . " WHERE a.id_empresa =" . $usuario[0]->id_usuario
//                    . " AND a.status <>  'Removida' ";
//
//
//            $sessao = $this->superModel->query($sql);
//            $this->data['pedidosUsuarioSessao'] = $sessao;
//        } else if ($usuario[0]->id_tipo_usuario == 4) {
//
//            $sql = "SELECT a.id_pedido_de_orcamento, a.nro_pedido_de_orcamento, a.nome_sol, a.nome_aprov, a.id_usuario_empresa, a.descricao, a.localidade, a.normas, a.qualificacoes, a.status, a.prioridade, a.status_orc, a.data_pedido_de_orcamento AS data, a.hora_pedido_de_orcamento  AS hora"
//                    . " FROM tbl_pedido_de_orcamento a "
//                    . " LEFT JOIN tbl_requerente b ON (b.id_usuario_empresa = a.id_empresa) "
//                    . " WHERE b.id_usuario =" . $usuario[0]->id_usuario
//                    . " AND b.setor = a.localidade"
//                    . " AND a.status <>  'Removida' ";
//
//            $r = $this->superModel->query($sql);
//
//            $this->data['self'] = $usuario[0]->nome;
//
//            $this->data['pedidosUsuarioReq'] = $r;
//        } else {
//            $sql = "SELECT a.id_pedido_de_orcamento, a.nro_pedido_de_orcamento, a.nome_sol, a.nome_aprov, a.id_usuario_empresa, a.descricao, a.localidade, a.normas, a.qualificacoes, a.status, a.prioridade, a.status_orc, a.data_pedido_de_orcamento AS data, a.hora_pedido_de_orcamento  AS hora"
//                    . " FROM tbl_pedido_de_orcamento a "
//                    . " LEFT JOIN tbl_aprovador b ON (b.id_usuario_empresa = a.id_empresa) "
//                    . " WHERE b.id_usuario =" . $usuario[0]->id_usuario
//                    . " AND a.status <>  'Removida' ";
//
//            $a = $this->superModel->query($sql);
//
//            $this->data['pedidosUsuarioAprov'] = $a;
//        }
//
//        $this->data['user'] = $usuario;
//        $this->layout->view('restrita/historicoServicos', $this->data);
//    }
//
//    public function excluir() {
//
//        $this->data['script_listar_os'] = 1;
//
//        $data = array(
//            'status' => 'Removida'
//        );
//        $condicoes = array(
//            array(
//                'campo' => 'id_pedido_de_orcamento ',
//                'valor' => $this->input->post('id_os')
//            )
//        );
//
//        $this->superModel->update('pedido_de_orcamento', $data, $condicoes);
//
//        $condicoes = array(
//            'condicoes' => array(
//                array(
//                    'campo' => 'id_usuario',
//                    'valor' => $this->session->userdata['id']
//                ),
//            )
//        );
//
//        $usuario = $this->superModel->select('usuario', $condicoes);
//
//        $clausulas = array(
//            'condicoes' => array(
//                array(
//                    'campo' => 'status',
//                    'sinal' => '<>',
//                    'valor' => 'Removida'
//                ),
//                array(
//                    'campo' => 'nome_sol',
//                    'valor' => $usuario[0]->nome
//                ),
//            )
//        );
//
//        if ($usuario[0]->id_tipo_usuario == 5) {
//            $sql = "SELECT a.id_pedido_de_orcamento, a.nro_pedido_de_orcamento, a.nome_sol, a.nome_aprov, a.id_usuario_empresa, a.descricao, a.localidade, a.normas, a.qualificacoes, a.status, a.prioridade, a.status_orc, a.data_pedido_de_orcamento  AS data, a.hora_pedido_de_orcamento AS hora"
//                    . " FROM tbl_pedido_de_orcamento a "
//                    . " WHERE a.id_empresa =" . $usuario[0]->id_usuario
//                    . " AND a.status <>  'Removida' ";
//
//
//            $sessao = $this->superModel->query($sql);
//            $this->data['pedidosUsuarioSessao'] = $sessao;
//        } else if ($usuario[0]->id_tipo_usuario == 4) {
//            $sql = "SELECT a.id_pedido_de_orcamento, a.nro_pedido_de_orcamento, a.nome_sol, a.nome_aprov, a.id_usuario_empresa, a.descricao, a.localidade, a.normas, a.qualificacoes, a.status, a.prioridade, a.status_orc, a.data_pedido_de_orcamento AS data, a.hora_pedido_de_orcamento  AS hora"
//                    . " FROM tbl_pedido_de_orcamento a "
//                    . " LEFT JOIN tbl_requerente b ON (b.id_usuario_empresa = a.id_empresa) "
//                    . " WHERE b.id_usuario =" . $usuario[0]->id_usuario
//                    . " AND b.setor = a.localidade"
//                    . " AND a.status <>  'Removida' ";
//
//            $r = $this->superModel->query($sql);
//
//
//            $this->data['pedidosUsuarioReq'] = $r;
//        } else {
//            $sql = "SELECT a.id_pedido_de_orcamento, a.nro_pedido_de_orcamento, a.nome_sol, a.nome_aprov, a.id_usuario_empresa, a.descricao, a.localidade, a.normas, a.qualificacoes, a.status, a.prioridade, a.status_orc, a.data_pedido_de_orcamento AS data, a.hora_pedido_de_orcamento  AS hora"
//                    . " FROM tbl_pedido_de_orcamento a "
//                    . " LEFT JOIN tbl_aprovador b ON (b.id_usuario_empresa = a.id_empresa) "
//                    . " WHERE b.id_usuario =" . $usuario[0]->id_usuario
//                    . " AND b.setor = a.localidade"
//                    . " AND a.status <>  'Removida' ";
//
//            $a = $this->superModel->query($sql);
//
//            $this->data['pedidosUsuarioAprov'] = $a;
//        }
//
//
//
//        $this->data['user'] = $usuario;
//        $this->layout->view('restrita/historicoServicos', $this->data);
//    }
//
//    public function Alterar($id) {
//        insertTag('js', 'restrita/historico.js?v=1.0', $this->data);
//
//        $this->data['script_listar_os'] = 1;
//
//        $clausulas = array(
//            'condicoes' => array(
//                array(
//                    'campo' => 'id_usuario',
//                    'valor' => $this->session->userdata('id')
//                )
//            )
//        );
//
//        $idUsuario = $this->superModel->select('usuario', $clausulas);
//
//
//        $total_pedido = $this->superModel->query(
//                "SELECT COUNT(*) total_pedido "
//                . "  FROM tbl_pedido            "
//                . " WHERE status = 'a'         "
//                . "   AND id_usuario= " . $idUsuario[0]->id_usuario);
//
//        $this->data['total_pedido'] = $total_pedido[0];
//        $this->data['script_filtro'] = 1;
//
//
//        $pedidos = $this->getPedidosId($id);
//
//
//        $this->data['script_increment'] = 1;
//
//
//        if ($idUsuario[0]->id_tipo_usuario == 5) {
//
//            $sql = "SELECT a.nome"
//                    . " FROM tbl_setor a "
//                    . " LEFT JOIN tbl_setor_empresa b ON (b.id_setor = a.id_setor) "
//                    . " WHERE b.id_empresa=" . $idUsuario[0]->id_usuario
//                    . " AND status <> 'Removido' ";
//
//            $setores = $this->superModel->query($sql);
//            $this->data['setores'] = $setores;
//
//            //$this->data['nroOs'] = $this->geraNroOs($idUsuario[0]->id_usuario);
//        } else if ($idUsuario[0]->id_tipo_usuario == 6) {
//
//            $clausulasA = array(
//                'condicoes' => array(
//                    array(
//                        'campo' => 'id_usuario',
//                        'valor' => $idUsuario[0]->id_usuario
//                    )
//                )
//            );
//
//            $idAprovador = $this->superModel->select('aprovador', $clausulasA);
//            $this->data['setor'] = $idAprovador[0]->setor;
//
//            // $this->data['nroOs'] = $this->geraNroOs($idAprovador[0]->id_usuario_empresa);
//        } else if ($idUsuario[0]->id_tipo_usuario == 4) {
//
//            $clausulasR = array(
//                'condicoes' => array(
//                    array(
//                        'campo' => 'id_usuario',
//                        'valor' => $idUsuario[0]->id_usuario
//                    )
//                )
//            );
//
//            $idRequerente = $this->superModel->select('requerente', $clausulasR);
//            $this->data['setor'] = $idRequerente[0]->setor;
//
//            // $this->data['nroOs'] = $this->geraNroOs($idRequerente[0]->id_usuario_empresa);
//        }
//
//
//        $condicoes = array(
//            'condicoes' => array(
//                array(
//                    'campo' => 'id_usuario',
//                    'valor' => $this->session->userdata('id')
//                )
//            )
//        );
//
//        $this->data['user'] = $this->superModel->select('usuario', $condicoes);
//
//        $clausulasOs = array(
//            'condicoes' => array(
//                array(
//                    'campo' => 'id_pedido_de_orcamento',
//                    'valor' => $id
//                )
//            )
//        );
//
//        $p = $this->superModel->select('pedido_de_orcamento', $clausulasOs);
//        $this->data['pedidos'] = $pedidos;
//        $this->data['nroOs'] = $p[0]->nro_pedido_de_orcamento;
//        $this->data['pedidosOrcamentos'] = $p[0];
//        $this->data['idPedidoOrcamento'] = $id;
//        $this->session->set_userdata('nroOs', $os[0]->nro_pedido_de_orcamento);
//
//
//        $this->layout->view('restrita/pedidosManualAlterar', $this->data);
//    }
//
//    private function getPedidosId($id) {
//        //$mix = array();
//
//        $data = array(
//            'select' => 'id_pedido, id_orcamento, id_servico, qntd',
//            'condicoes' => array(
//                array(
//                    'campo' => 'id_pedido_de_orcamento',
//                    'valor' => $id
//                ),
//                array(
//                    'sinal' => '!=',
//                    'campo' => 'status',
//                    'valor' => 'c'
//                )
//            )
//        );
//
//        $pedidos = $this->superModel->select('pedido', $data);
//
//        foreach ($pedidos as $key => $pedido) {
//            $sql = "SELECT c.descricao AS pergunta, b.descricao AS filtro, a.valor "
//                    . " FROM tbl_pedido_filtro a "
//                    . " LEFT JOIN tbl_filtro b ON (b.id_filtro = a.id_filtro) "
//                    . " LEFT JOIN tbl_pergunta c ON (c.id_pergunta = b.id_pergunta) "
//                    . " WHERE a.id_pedido = $pedido->id_pedido";
//            $filtro = $this->superModel->query($sql);
//
//            $pedidos[$key]->filtros = $filtro;
//
//            $data = array(
//                'select' => 'id_categoria_servico, descricao',
//                'condicoes' => array(
//                    array(
//                        'campo' => 'id_servico',
//                        'valor' => $pedido->id_servico
//                    )
//                )
//            );
//
//            $servico = $this->superModel->select('servico', $data);
//            $servico = $servico[0];
//
//            $pedidos[$key]->Servico = $servico;
//
//            $data = array(
//                'select' => 'id_categoria_servico, id_subcategoria, descricao, imagem',
//                'condicoes' => array(
//                    array(
//                        'campo' => 'id_categoria_servico',
//                        'valor' => $servico->id_categoria_servico
//                    )
//                )
//            );
//
//            $categoria_servico = $this->superModel->select('categoria_servico', $data);
//            $categoria_servico = $categoria_servico[0];
//
//            $pedidos[$key]->Servico->categoria = $categoria_servico;
//
//            $data = array(
//                'select' => 'subcategoria as descricao',
//                'condicoes' => array(
//                    array(
//                        'campo' => 'id_subcategoria',
//                        'valor' => $categoria_servico->id_subcategoria
//                    )
//                )
//            );
//
//            $subcategoria = $this->superModel->select('subcategoria', $data);
//            $subcategoria = $subcategoria[0];
//
//            $pedidos[$key]->Servico->categoria->subcategoria = $subcategoria;
//        }
//
//        //$mix[0] = $pedidos;
//
//        return $pedidos;
//    }
//
//    public function AlterarOrc() {
//
//        $this->data['script_listar_os'] = 1;
//        $data = array(
//            'status_orc' => $this->input->post('statusOrc')
//        );
//
//        $condicoes = array(
//            array(
//                'campo' => 'id_pedido_de_orcamento',
//                'valor' => $this->input->post('id_pedido_de_orcamento')
//            )
//        );
//        $this->superModel->update('pedido_de_orcamento', $data, $condicoes);
//    }
//
//    public function Aprovar($id) {
//
//        $this->data['script_listar_os'] = 1;
//
//        $condicoes = array(
//            'condicoes' => array(
//                array(
//                    'campo' => 'id_usuario',
//                    'valor' => $this->session->userdata['id']
//                ),
//            )
//        );
//
//
//        $usuario = $this->superModel->select('usuario', $condicoes);
//
//        $data = array(
//            'status' => 'Aprovada e Enviada',
//            'nome_aprov' => $usuario[0]->nome
//        );
//
//        $condicoes2 = array(
//            array(
//                'campo' => 'id_pedido_de_orcamento',
//                'valor' => $id
//            )
//        );
//
//
//        $this->superModel->update('pedido_de_orcamento', $data, $condicoes2);
//
//        redirect('restrita/historico');
//    }
//
//    public function Recusar($id) {
//
//        $this->data['script_listar_os'] = 1;
//
//        $condicoes = array(
//            'condicoes' => array(
//                array(
//                    'campo' => 'id_usuario',
//                    'valor' => $this->session->userdata['id']
//                ),
//            )
//        );
//
//
//        $usuario = $this->superModel->select('usuario', $condicoes);
//
//        $data = array(
//            'status' => 'Não Aprovada',
//            'nome_aprov' => $usuario[0]->nome
//        );
//        $condicoes = array(
//            array(
//                'campo' => 'id_pedido_de_orcamento',
//                'valor' => $id
//            )
//        );
//
//
//        $this->superModel->update('pedido_de_orcamento', $data, $condicoes);
//        redirect('restrita/historico');
//    }

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

?>