<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pedidos_Orcamentos extends MY_Restrita {

    function __construct() {
        parent::__construct();
        $this->load->library("geral");
    }

    public function historico() {

        insertTag('js', 'restrita/pedidos_orcamentos.js?v=1.0', $this->data);

        $this->data['script_listar_os'] = 1;

        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata['id']
                ),
            )
        );

        $usuario = $this->superModel->select('usuario', $condicoes);

        $clausulas = array(
            'condicoes' => array(
                array(
                    'campo' => 'status',
                    'sinal' => '<>',
                    'valor' => 'Removida'
                ),
                array(
                    'campo' => 'nome_sol',
                    'valor' => $usuario[0]->nome
                ),
            )
        );

        if ($usuario[0]->id_tipo_usuario == 5) {

            $sql = "SELECT a.id_pedido_de_orcamento, a.nro_pedido_de_orcamento, a.nome_sol, a.nome_aprov, a.id_usuario_empresa, a.descricao, a.localidade, a.normas, a.qualificacoes, a.status, a.prioridade, a.status_orc, a.data_pedido_de_orcamento  AS data, a.hora_pedido_de_orcamento AS hora, a.data_hora_alteracao, ua.nome nome_alteracao, a.data_hora_exclusao, ue.nome nome_exclusao "
                    . " FROM tbl_pedido_de_orcamento a "
                    . " left join tbl_usuario ua on a.id_usuario_alteracao = ua.id_usuario"
                    . " left join tbl_usuario ue on a.id_usuario_exclusao = ue.id_usuario"
                    . " WHERE a.id_empresa =" . $usuario[0]->id_usuario
                    . " AND a.nro_pedido_de_orcamento is not  null";


            $sessao = $this->superModel->query($sql);
            $this->data['pedidosUsuarioSessao'] = $sessao;
        } else if ($usuario[0]->id_tipo_usuario == 4) {

            $sql = "SELECT a.id_pedido_de_orcamento, a.nro_pedido_de_orcamento, a.nome_sol, a.nome_aprov, a.id_usuario_empresa, a.descricao, a.localidade, a.normas, a.qualificacoes, a.status, a.prioridade, a.status_orc, a.data_pedido_de_orcamento AS data, a.hora_pedido_de_orcamento  AS hora, a.data_hora_alteracao, ua.nome nome_alteracao, a.data_hora_exclusao, ue.nome nome_exclusao "
                    . " FROM tbl_pedido_de_orcamento a "
                    . " LEFT JOIN tbl_requerente b ON (b.id_usuario_empresa = a.id_empresa) "
                    . " left join tbl_usuario ua on a.id_usuario_alteracao = ua.id_usuario"
                    . " left join tbl_usuario ue on a.id_usuario_exclusao = ue.id_usuario"
                    . " WHERE b.id_usuario =" . $usuario[0]->id_usuario
                    . " AND b.setor = a.localidade"
                    . " AND a.nro_pedido_de_orcamento is not  null";

            $r = $this->superModel->query($sql);

            $this->data['self'] = $usuario[0]->nome;

            $this->data['pedidosUsuarioReq'] = $r;
        } else {
            $sql = "SELECT a.id_pedido_de_orcamento, a.nro_pedido_de_orcamento, a.nome_sol, a.nome_aprov, a.id_usuario_empresa, a.descricao, a.localidade, a.normas, a.qualificacoes, a.status, a.prioridade, a.status_orc, a.data_pedido_de_orcamento AS data, a.hora_pedido_de_orcamento  AS hora, a.data_hora_alteracao, ua.nome nome_alteracao, a.data_hora_exclusao, ue.nome nome_exclusao "
                    . " FROM tbl_pedido_de_orcamento a "
                    . " LEFT JOIN tbl_aprovador b ON (b.id_usuario_empresa = a.id_empresa) "
                    . " left join tbl_usuario ua on a.id_usuario_alteracao = ua.id_usuario"
                    . " left join tbl_usuario ue on a.id_usuario_exclusao = ue.id_usuario"
                    . " WHERE b.id_usuario =" . $usuario[0]->id_usuario
                    . " AND a.nro_pedido_de_orcamento is not  null";

            $a = $this->superModel->query($sql);

            $this->data['pedidosUsuarioAprov'] = $a;
        }

        $this->data['user'] = $usuario;
        $this->layout->view('restrita/historicoServicos', $this->data);
    }

    public function alterar($id) {
        insertTag('js', 'restrita/pedidos_orcamentos.js?v=1.0', $this->data);

        $this->data['script_listar_os'] = 1;

        $clausulas = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata('id')
                )
            )
        );

        $idUsuario = $this->superModel->select('usuario', $clausulas);


        $total_pedido = $this->superModel->query(
                "SELECT COUNT(*) total_pedido "
                . "  FROM tbl_pedido            "
                . " WHERE status = 'a'         "
                . "   AND id_usuario= " . $idUsuario[0]->id_usuario);

        $this->data['total_pedido'] = $total_pedido[0];
        $this->data['script_filtro'] = 1;


        $pedidos = $this->getPedidosId($id);


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
                    'campo' => 'id_pedido_de_orcamento',
                    'valor' => $id
                )
            )
        );

        $p = $this->superModel->select('pedido_de_orcamento', $clausulasOs);
        $this->data['pedidos'] = $pedidos;
        $this->session->set_userdata('nome_sol',$p[0]->nome_sol);
        $this->data['nroPedido'] = $p[0]->nro_pedido_de_orcamento;
        $this->data['pedidosOrcamentos'] = $p[0];
        $this->data['idPedidoOrcamento'] = $id;
        $this->session->set_userdata('nroOs', $os[0]->nro_pedido_de_orcamento);


        $this->layout->view('restrita/pedidos_orcamentos/pedidosManualAlterar', $this->data);
    }

    public function alterarStatusOrc() {
        $data = array(
            'status_orc' => $this->input->post('statusOrc'),
            'id_usuario_alteracao' => $this->session->userdata('id')
        );

        $condicoes = array(
            array(
                'campo' => 'id_pedido_de_orcamento',
                'valor' => $this->input->post('id_pedido_de_orcamento')
            )
        );
        $this->superModel->update('pedido_de_orcamento', $data, $condicoes);
        echo json_encode(array("codigo" => "999", "mensagem" => "Status alterador com sucesso."));
    }

    private function getPedidosId($id) {
        //$mix = array();

        $data = array(
            'select' => 'id_pedido, id_orcamento, id_servico, qntd',
            'condicoes' => array(
                array(
                    'campo' => 'id_pedido_de_orcamento',
                    'valor' => $id
                ),
                array(
                    'sinal' => '!=',
                    'campo' => 'status',
                    'valor' => 'c'
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

    public function gravar() {

        $clausulas = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata('id')
                )
            )
        );

        $idUsuario = $this->superModel->select('usuario', $clausulas);

       
        if ($this->form_validation->run('restrita/ordem/actionPedidoOrcamento')) {

            $clausulas = array(
                'condicoes' => array(
                    array(
                        'campo' => 'id_pedido_de_orcamento',
                        'valor' => $this->input->post('id_pedido_de_orcamento')
                    )
                )
            );

            $pedido_orcamento = $this->superModel->select('pedido_de_orcamento', $clausulas);

            $data = array();

            $mensagem = 'Seu Pedido alterado!';
             date_default_timezone_set("America/Sao_Paulo");
            if ($pedido_orcamento[0]->id_usuario_sol == "") {
            
                
                $data = array_merge($data, array(
                    'id_usuario_sol' => $this->session->userdata('id'),
                    'nome_sol' => $this->session->userdata('nome'),
                    'data_pedido_de_orcamento' => date("Y-m-d"),
                    'hora_pedido_de_orcamento' => date("H:i:s")
                        )
                );

                if ($idUsuario[0]->id_tipo_usuario == 5) {
                    $stringNome = $idUsuario[0]->nome;
                    $idAprovador = $this->session->userdata('id');
                    $stringStatus = "Aprovado e Enviado";
                    $mensagem = 'Seu Pedido foi Aprovado e Enviado!';
                } else if ($idUsuario[0]->id_tipo_usuario == 6) {
                    $stringNome = $idUsuario[0]->nome;
                    $idAprovador = $this->session->userdata('id');
                    $stringStatus = "Aprovado e Enviado";
                    $$mensagem = 'Seu Pedido foi Aprovado e Enviado!';
                } else if ($idUsuario[0]->id_tipo_usuario == 4) {
                    $stringNome = "Pendentes de Aprovação";
                    $idAprovador = null;
                    $stringStatus = "Pendentes de Aprovação";
                    $mensagem = 'Seu Pedido foi Enviado para Aprovação!';
                }

                if ($pedido_orcamento[0]->id_usuario_aprov == "") {
                    $data = array_merge($data, array(
                        'id_usuario_aprov' => $idAprovador,
                        'nome_aprov' => $stringNome,
                        'status' => $stringStatus,
                            )
                    );
                }
            }

           
            $data = array_merge($data, array(
                'descricao' => $this->input->post('descricao'),
                'localidade' => $this->input->post('setor'),
                'normas' => $this->input->post('normas'),
                'qualificacoes' => $this->input->post('qualificacoes'),
                'prioridade' => $this->input->post('prioridade'),
                'id_usuario_alteracao' => $this->session->userdata('id')
            ));

          

            $condicao = array(
                array(
                    'campo' => 'id_pedido_de_orcamento',
                    'valor' => $this->input->post('id_pedido_de_orcamento')
                )
            );
            $this->superModel->update('pedido_de_orcamento', $data, $condicao);


             if ($idUsuario[0]->id_tipo_usuario != 4 && $pedido_orcamento[0]->id_usuario_sol == "") {
                
                $this->enviaEmailPedido($idUsuario);
            }

        

            $data = array(
                'status' => 'f',
                'id_usuario_alteracao' => $this->session->userdata('id'),
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


            foreach ($this->input->post('qntd') as $idPedido => $qntd) {

                $data = array(
                    'qntd' => $qntd,
                    'id_usuario_alteracao' => $this->session->userdata('id')
                );

                $condicoes = array(
                    array(
                        'campo' => 'id_pedido',
                        'valor' => $idPedido
                    )
                );

                $this->superModel->update('pedido', $data, $condicoes);
            }

            $this->session->unset_userdata('nome_sol');
            $this->session->unset_userdata('nro');
            $this->session->unset_userdata('idPedidoOrcamento');
            echo json_encode(array("codigo" => "999", "mensagem" => $mensagem));
        } else {
            echo json_encode(array("codigo" => "111", "mensagem" => "O formulário contem erros: <br>" . validation_errors()));
        }
    }

    public function actionRemoverPedido($idPedido) {
        if ($idPedido != "") {
            $data = array(
                'status' => 'c',
                'id_usuario_exclusao' => $this->session->userdata('id'),
                'data_hora_exclusao' => date("Y-m-d H:i:s")
            );

            $condicao = array(
                array(
                    'campo' => 'id_pedido',
                    'valor' => $idPedido
                )
            );

            $this->superModel->update('pedido', $data, $condicao);
           

            redirect($_SERVER['HTTP_REFERER']);
        } else {
            echo "Falha ao executar ação.";
        }
    }

    public function cancelar_edicao() {
        $data = array(
            'status' => 'c',
            'id_usuario_exclusao' => $this->session->userdata('id'),
            'data_hora_exclusao' => date("Y-m-d H:i:s")
        );

        $condicao = array(
            array(
                'campo' => 'id_usuario',
                'valor' => $this->session->userdata('id')
            ),
            array(
                'sinal' => '=',
                'campo' => 'status',
                'valor' => 'a'
            )
        );

        $this->superModel->update('pedido', $data, $condicao);

        $this->session->unset_userdata('nome_sol');
        $this->data['pedidos'] = NULL;
        $this->session->unset_userdata('nro');
        $this->session->unset_userdata('idPedidoOrcamento');
        $this->session->unset_userdata('msgEnviouPedidoOrcamento');

        redirect('restrita/pedidos_orcamentos/historico');
    }

    public function cancelar($idPedidoOrcamento) {
        if ($this->input->post('id_pedido_de_orcamento')) {
            $idPedidoOrcamento = $this->input->post('id_pedido_de_orcamento');
        }
        if ($idPedidoOrcamento) {
            $data = array(
                'status' => 'c',
                'id_usuario_exclusao' => $this->session->userdata('id'),
                'data_hora_exclusao' => date("Y-m-d H:i:s")
            );

            $condicao = array(
                array(
                    'campo' => 'id_pedido_de_orcamento',
                    'valor' => $idPedidoOrcamento
                ),
                array(
                    'sinal' => '=',
                    'campo' => 'status',
                    'valor' => 'a'
                )
            );

            $this->superModel->update('pedido', $data, $condicao);

            $data = array(
                'id_usuario_exclusao' => $this->session->userdata('id'),
                'data_hora_exclusao' => date("Y-m-d H:i:s"),
                'status' => 'Removido'
            );

            $condicao = array(
                array(
                    'campo' => 'id_pedido_de_orcamento',
                    'valor' => $idPedidoOrcamento
                )
            );

            $this->superModel->update('pedido_de_orcamento', $data, $condicao);

            $this->data ['pedidos'] = NULL;
            $this->session->unset_userdata('nro');
            $this->session->unset_userdata('idPedidoOrcamento');
            $this->session->unset_userdata('msgEnviouPedidoOrcamento');
            $this->session->unset_userdata('nome_sol');
            
             $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_pedido_de_orcamento',
                    'valor' => $idPedidoOrcamento
                ),
            )
        );
             
            $pedido= $this->superModel->select('pedido_de_orcamento',$condicoes);

            



            $this->layout->view('restrita/home', $this->data);
        } else {
            $this->layout->view('restrita/home', $this->data);
        }
    }

    public function Aprovar($id) {

        $this->data['script_listar_os'] = 1;

        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata['id']
                ),
            )
        );


        $usuario = $this->superModel->select('usuario', $condicoes);

        $data = array(
            'status' => 'Aprovado e Enviado',
            'nome_aprov' => $usuario[0]->nome,
            'id_usuario_aprov' => $this->session->userdata('id'),
            'id_usuario_alteracao' => $this->session->userdata('id')
        );

        $condicoes2 = array(
            array(
                'campo' => 'id_pedido_de_orcamento',
                'valor' => $id
            )
        );

        $this->superModel->update('pedido_de_orcamento', $data, $condicoes2);

        
        $condicoesP = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_pedido_de_orcamento',
                    'valor' => $id
                ),
            )
        );

       $pedido = $this->superModel->select('pedido_de_orcamento',$condicoesP);
                
        $condicoesA = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $pedido[0]->id_usuario_aprov
                ),
            )
        );

    
        
                
        $idUsuarioA = $this->superModel->select('usuario',$condicoesA);
        $this->enviaEmailPedido($idUsuarioA);
           
        $condicoesR = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $pedido[0]->id_usuario_sol
                ),
            )
        );

        $idUsuarioR = $this->superModel->select('usuario',$condicoesR);
         $this->enviaEmailPedidoAprov($idUsuarioR,  $pedido[0]->nro_pedido_de_orcamento);
           

        
        redirect('restrita/pedidos_orcamentos/historico');
    }

    public function Recusar($id) {

        $this->data['script_listar_os'] = 1;

        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata['id']
                ),
            )
        );


        $usuario = $this->superModel->select('usuario', $condicoes);

        $data = array(
            'status' => 'Não Aprovado',
            'nome_aprov' => $usuario[0]->nome,
            'id_usuario_aprov' => $this->session->userdata('id'),
            'id_usuario_alteracao' => $this->session->userdata('id'),
        );
        $condicoes = array(
            array(
                'campo' => 'id_pedido_de_orcamento',
                'valor' => $id
            )
        );


        $this->superModel->update('pedido_de_orcamento', $data, $condicoes);
       

          $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_pedido_de_orcamento',
                    'valor' => $id
                ),
            )
        );
             
        
        $pedido= $this->superModel->select('pedido_de_orcamento',$condicoes);
        
        $condicoesR = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $pedido[0]->id_usuario_sol
                ),
            )
        );
       
        $idUsuarioR = $this->superModel->select('usuario',$condicoesR);
        $this->enviaEmailPedidoRecusa($idUsuarioR, $pedido[0]->nro_pedido_de_orcamento);
           




        redirect('restrita/pedidos_orcamentos/historico');
    }

    public function listarPedidos($id) {
        $this->data['script_listar_os'] = 1;

        $condicao = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_pedido_de_orcamento',
                    'valor' => $id
                )
            )
        );

        $os = $this->superModel->select('pedido_de_orcamento', $condicao);


        $data = array(
            'select' => 'id_pedido, id_pedido_de_orcamento, id_servico, qntd, '
            . ' CASE status WHEN \'a\' THEN \'Andamento\' '
            . ' WHEN \'f\' THEN \'Finalizado\' '
            . ' WHEN \'e\' THEN \'Excluído\' '
            . ' ELSE \'\' END status',
            'condicoes' => array(
                array(
                    'campo' => 'id_pedido_de_orcamento',
                    'valor' => $id
                ),array(
                    'sinal'=>'!=',
                    'campo' => 'status',
                    'valor' => "c"
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



        $dados = array(
            'pedidos' => $pedidos,
            'os' => $os[0]
        );


        $jsonPedidos = json_encode($dados);
        echo $jsonPedidos;
    }





    private function enviaEmailPedido($data) {
        $configEmail = $this->superModel->getRow('config_email');
        $this->load->library('email');

        $mensagem = '<!DOCTYPE html>
                <html lang="pt-br">
                <head>
                    <title>Sossegue - Solicitação de serviço</title>
                    <meta charset="utf-8">
                    <meta name="viewport" content="width=device-width">
                    <style type="text/css">
                        /* CLIENT-SPECIFIC STYLES */
                        #outlook a{padding:0;} /* Force Outlook to provide a "view in browser" message */
                        .ReadMsgBody{width:100%;} .ExternalClass{width:100%;} /* Force Hotmail to display emails at full width */
                        .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} /* Force Hotmail to display normal line spacing */
                        body, table, td, a{-webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;} /* Prevent WebKit and Windows mobile changing default text sizes */
                        table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;} /* Remove spacing between tables in Outlook 2007 and up */
                        img{-ms-interpolation-mode:bicubic;} /* Allow smoother rendering of resized image in Internet Explorer */
                        /* RESET STYLES */
                        body{margin:0; padding:0;}
                        img{border:0; height:auto; line-height:100%; outline:none; text-decoration:none;}
                        table{border-collapse:collapse !important;}
                        body{height:100% !important; margin:0; padding:0; width:100% !important;}
                        /* iOS BLUE LINKS */
                        .appleBody a {color:#68440a; text-decoration: none;}
                        .appleFooter a {color:#999999; text-decoration: none;}
                        /* MOBILE STYLES */
                        @media screen and (max-width: 525px) {
                            /* ALLOWS FOR FLUID TABLES */
                            table[class="wrapper"]{
                              width:100% !important;
                            }
                            /* ADJUSTS LAYOUT OF LOGO IMAGE */
                            td[class="logo"]{
                              text-align: left;
                              padding: 20px 0 20px 0 !important;
                            }
                            td[class="logo"] img{
                              margin:0 auto!important;
                            }
                            /* USE THESE CLASSES TO HIDE CONTENT ON MOBILE */
                            td[class="mobile-hide"]{
                              display:none;}
                            img[class="mobile-hide"]{
                              display: none !important;
                            }
                            img[class="img-max"]{
                              max-width: 100% !important;
                              height:auto !important;
                            }
                            /* FULL-WIDTH TABLES */
                            table[class="responsive-table"]{
                              width:100%!important;
                            }
                            /* UTILITY CLASSES FOR ADJUSTING PADDING ON MOBILE */
                            td[class="padding"]{
                              padding: 10px 5% 15px 5% !important;
                            }
                            td[class="padding-copy"]{
                              padding: 10px 5% 10px 5% !important;
                              text-align: center;
                            }
                            td[class="padding-meta"]{
                              padding: 30px 5% 0px 5% !important;
                              text-align: center;
                            }
                            td[class="no-pad"]{
                              padding: 0 0 20px 0 !important;
                            }
                            td[class="no-padding"]{
                              padding: 0 !important;
                            }
                            td[class="section-padding"]{
                              padding: 50px 15px 50px 15px !important;
                            }
                            td[class="section-padding-bottom-image"]{
                              padding: 50px 15px 0 15px !important;
                            }
                            /* ADJUST BUTTONS ON MOBILE */
                            td[class="mobile-wrapper"]{
                                padding: 10px 5% 15px 5% !important;
                            }
                            table[class="mobile-button-container"]{
                                margin:0 auto;
                                width:100% !important;
                            }
                            a[class="mobile-button"]{
                                width:80% !important;
                                padding: 15px !important;
                                border: 0 !important;
                                font-size: 16px !important;
                            }
                        }
                    </style>
                </head>
                <body style="margin: 0; padding: 0;">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td bgcolor="#ffffff">
                                <div align="center" style="padding: 0px 15px 0px 15px;">
                                    <table border="0" cellpadding="0" cellspacing="0" width="500" class="wrapper">
                                        <tr>
                                            <td style="padding: 20px 0px 0px 0px;" class="logo">
                                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td bgcolor="#ffffff" width="100" align="left"><a href="' . base_url() . '" target="_blank"><img alt="Logo Sossegue" src="' . base_url("assets/img/logo_mod-a.png") . '" width="130" style="display: block;" border="0"></a></td>
                                                        <td bgcolor="#ffffff" width="400" align="right" class="mobile-hide">
                                                            <table border="0" cellpadding="0" cellspacing="0">
                                                                <tr>
                                                                    <td align="right" style="padding: 0 0 5px 0; font-size: 14px; font-family: Arial, sans-serif; color: #666666; text-decoration: none;"><span style="color: #666666; text-decoration: none;"><a href="' . base_url() . '" target="_blank">SOSSEGUE</a><br>Sua vida mais tranquila</span></td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#ffffff" align="center" style="padding: 30px 15px 70px 15px;" class="section-padding">
                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="responsive-table">
                                    <tr>
                                        <td>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td>
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                            <tr>
    
                                                                <td align="center" style="padding: 20px 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding-copy">Olá, o cliente ' . $data[0]->nome. ' está a procura de profissionais. <br /> 
                                                                    Descrição: ' . $this->session->userdata('descricao') . '<br />
                                                                    Contato: ' . $data[0]->nome . ', ' .$data[0]->telefone.', ' . $data[0]->email . '</td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="mobile-button-container">
                                                            <tr>
                                                                <td align="center" style="padding: 20px 0 0 0;font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding-copy">

                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </body>
                </html>';

        $emailEnviado = $this->email
                ->from($configEmail->username, 'Sossegue')
                ->to('contato@sossegue.com.br, contato.sossegue@gmail.com')
                ->subject("Sossegue - Solicitação de orçamento")
                ->message($mensagem)
                ->send();
    }






    private function enviaEmailPedidoAprov($data,$nro) {
        $configEmail = $this->superModel->getRow('config_email');
        $this->load->library('email');

        $mensagem = '<!DOCTYPE html>
                <html lang="pt-br">
                <head>
                    <title>Sossegue - Solicitação de serviço</title>
                    <meta charset="utf-8">
                    <meta name="viewport" content="width=device-width">
                    <style type="text/css">
                        /* CLIENT-SPECIFIC STYLES */
                        #outlook a{padding:0;} /* Force Outlook to provide a "view in browser" message */
                        .ReadMsgBody{width:100%;} .ExternalClass{width:100%;} /* Force Hotmail to display emails at full width */
                        .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} /* Force Hotmail to display normal line spacing */
                        body, table, td, a{-webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;} /* Prevent WebKit and Windows mobile changing default text sizes */
                        table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;} /* Remove spacing between tables in Outlook 2007 and up */
                        img{-ms-interpolation-mode:bicubic;} /* Allow smoother rendering of resized image in Internet Explorer */
                        /* RESET STYLES */
                        body{margin:0; padding:0;}
                        img{border:0; height:auto; line-height:100%; outline:none; text-decoration:none;}
                        table{border-collapse:collapse !important;}
                        body{height:100% !important; margin:0; padding:0; width:100% !important;}
                        /* iOS BLUE LINKS */
                        .appleBody a {color:#68440a; text-decoration: none;}
                        .appleFooter a {color:#999999; text-decoration: none;}
                        /* MOBILE STYLES */
                        @media screen and (max-width: 525px) {
                            /* ALLOWS FOR FLUID TABLES */
                            table[class="wrapper"]{
                              width:100% !important;
                            }
                            /* ADJUSTS LAYOUT OF LOGO IMAGE */
                            td[class="logo"]{
                              text-align: left;
                              padding: 20px 0 20px 0 !important;
                            }
                            td[class="logo"] img{
                              margin:0 auto!important;
                            }
                            /* USE THESE CLASSES TO HIDE CONTENT ON MOBILE */
                            td[class="mobile-hide"]{
                              display:none;}
                            img[class="mobile-hide"]{
                              display: none !important;
                            }
                            img[class="img-max"]{
                              max-width: 100% !important;
                              height:auto !important;
                            }
                            /* FULL-WIDTH TABLES */
                            table[class="responsive-table"]{
                              width:100%!important;
                            }
                            /* UTILITY CLASSES FOR ADJUSTING PADDING ON MOBILE */
                            td[class="padding"]{
                              padding: 10px 5% 15px 5% !important;
                            }
                            td[class="padding-copy"]{
                              padding: 10px 5% 10px 5% !important;
                              text-align: center;
                            }
                            td[class="padding-meta"]{
                              padding: 30px 5% 0px 5% !important;
                              text-align: center;
                            }
                            td[class="no-pad"]{
                              padding: 0 0 20px 0 !important;
                            }
                            td[class="no-padding"]{
                              padding: 0 !important;
                            }
                            td[class="section-padding"]{
                              padding: 50px 15px 50px 15px !important;
                            }
                            td[class="section-padding-bottom-image"]{
                              padding: 50px 15px 0 15px !important;
                            }
                            /* ADJUST BUTTONS ON MOBILE */
                            td[class="mobile-wrapper"]{
                                padding: 10px 5% 15px 5% !important;
                            }
                            table[class="mobile-button-container"]{
                                margin:0 auto;
                                width:100% !important;
                            }
                            a[class="mobile-button"]{
                                width:80% !important;
                                padding: 15px !important;
                                border: 0 !important;
                                font-size: 16px !important;
                            }
                        }
                    </style>
                </head>
                <body style="margin: 0; padding: 0;">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td bgcolor="#ffffff">
                                <div align="center" style="padding: 0px 15px 0px 15px;">
                                    <table border="0" cellpadding="0" cellspacing="0" width="500" class="wrapper">
                                        <tr>
                                            <td style="padding: 20px 0px 0px 0px;" class="logo">
                                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td bgcolor="#ffffff" width="100" align="left"><a href="' . base_url() . '" target="_blank"><img alt="Logo Sossegue" src="' . base_url("assets/img/logo_mod-a.png") . '" width="130" style="display: block;" border="0"></a></td>
                                                        <td bgcolor="#ffffff" width="400" align="right" class="mobile-hide">
                                                            <table border="0" cellpadding="0" cellspacing="0">
                                                                <tr>
                                                                    <td align="right" style="padding: 0 0 5px 0; font-size: 14px; font-family: Arial, sans-serif; color: #666666; text-decoration: none;"><span style="color: #666666; text-decoration: none;"><a href="' . base_url() . '" target="_blank">SOSSEGUE</a><br>Sua vida mais tranquila</span></td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#ffffff" align="center" style="padding: 30px 15px 70px 15px;" class="section-padding">
                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="responsive-table">
                                    <tr>
                                        <td>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td>
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                            <tr>
    
                                                                <td align="center" style="padding: 20px 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding-copy">Seu Pedido de Orçamento  nro ' .$nro. ' foi aprovado!  <br /></td>
                                                            </tr>`
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="mobile-button-container">
                                                            <tr>
                                                                <td align="center" style="padding: 20px 0 0 0;font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding-copy">

                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </body>
                </html>';

        $emailEnviado = $this->email
                ->from($configEmail->username, 'Sossegue')->to($data[0]->email)
                ->subject("Sossegue - Aprovação de Pedido de Orçamento")
                ->message($mensagem)
                ->send();
    }

    
    private function enviaEmailPedidoRecusa($data,$nro) {
        $configEmail = $this->superModel->getRow('config_email');
        $this->load->library('email');

        $mensagem = '<!DOCTYPE html>
                <html lang="pt-br">
                <head>
                    <title>Sossegue - Solicitação de serviço</title>
                    <meta charset="utf-8">
                    <meta name="viewport" content="width=device-width">
                    <style type="text/css">
                        /* CLIENT-SPECIFIC STYLES */
                        #outlook a{padding:0;} /* Force Outlook to provide a "view in browser" message */
                        .ReadMsgBody{width:100%;} .ExternalClass{width:100%;} /* Force Hotmail to display emails at full width */
                        .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} /* Force Hotmail to display normal line spacing */
                        body, table, td, a{-webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;} /* Prevent WebKit and Windows mobile changing default text sizes */
                        table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;} /* Remove spacing between tables in Outlook 2007 and up */
                        img{-ms-interpolation-mode:bicubic;} /* Allow smoother rendering of resized image in Internet Explorer */
                        /* RESET STYLES */
                        body{margin:0; padding:0;}
                        img{border:0; height:auto; line-height:100%; outline:none; text-decoration:none;}
                        table{border-collapse:collapse !important;}
                        body{height:100% !important; margin:0; padding:0; width:100% !important;}
                        /* iOS BLUE LINKS */
                        .appleBody a {color:#68440a; text-decoration: none;}
                        .appleFooter a {color:#999999; text-decoration: none;}
                        /* MOBILE STYLES */
                        @media screen and (max-width: 525px) {
                            /* ALLOWS FOR FLUID TABLES */
                            table[class="wrapper"]{
                              width:100% !important;
                            }
                            /* ADJUSTS LAYOUT OF LOGO IMAGE */
                            td[class="logo"]{
                              text-align: left;
                              padding: 20px 0 20px 0 !important;
                            }
                            td[class="logo"] img{
                              margin:0 auto!important;
                            }
                            /* USE THESE CLASSES TO HIDE CONTENT ON MOBILE */
                            td[class="mobile-hide"]{
                              display:none;}
                            img[class="mobile-hide"]{
                              display: none !important;
                            }
                            img[class="img-max"]{
                              max-width: 100% !important;
                              height:auto !important;
                            }
                            /* FULL-WIDTH TABLES */
                            table[class="responsive-table"]{
                              width:100%!important;
                            }
                            /* UTILITY CLASSES FOR ADJUSTING PADDING ON MOBILE */
                            td[class="padding"]{
                              padding: 10px 5% 15px 5% !important;
                            }
                            td[class="padding-copy"]{
                              padding: 10px 5% 10px 5% !important;
                              text-align: center;
                            }
                            td[class="padding-meta"]{
                              padding: 30px 5% 0px 5% !important;
                              text-align: center;
                            }
                            td[class="no-pad"]{
                              padding: 0 0 20px 0 !important;
                            }
                            td[class="no-padding"]{
                              padding: 0 !important;
                            }
                            td[class="section-padding"]{
                              padding: 50px 15px 50px 15px !important;
                            }
                            td[class="section-padding-bottom-image"]{
                              padding: 50px 15px 0 15px !important;
                            }
                            /* ADJUST BUTTONS ON MOBILE */
                            td[class="mobile-wrapper"]{
                                padding: 10px 5% 15px 5% !important;
                            }
                            table[class="mobile-button-container"]{
                                margin:0 auto;
                                width:100% !important;
                            }
                            a[class="mobile-button"]{
                                width:80% !important;
                                padding: 15px !important;
                                border: 0 !important;
                                font-size: 16px !important;
                            }
                        }
                    </style>
                </head>
                <body style="margin: 0; padding: 0;">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td bgcolor="#ffffff">
                                <div align="center" style="padding: 0px 15px 0px 15px;">
                                    <table border="0" cellpadding="0" cellspacing="0" width="500" class="wrapper">
                                        <tr>
                                            <td style="padding: 20px 0px 0px 0px;" class="logo">
                                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td bgcolor="#ffffff" width="100" align="left"><a href="' . base_url() . '" target="_blank"><img alt="Logo Sossegue" src="' . base_url("assets/img/logo_mod-a.png") . '" width="130" style="display: block;" border="0"></a></td>
                                                        <td bgcolor="#ffffff" width="400" align="right" class="mobile-hide">
                                                            <table border="0" cellpadding="0" cellspacing="0">
                                                                <tr>
                                                                    <td align="right" style="padding: 0 0 5px 0; font-size: 14px; font-family: Arial, sans-serif; color: #666666; text-decoration: none;"><span style="color: #666666; text-decoration: none;"><a href="' . base_url() . '" target="_blank">SOSSEGUE</a><br>Sua vida mais tranquila</span></td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#ffffff" align="center" style="padding: 30px 15px 70px 15px;" class="section-padding">
                                <table border="0" cellpadding="0" cellspacing="0" width="500" class="responsive-table">
                                    <tr>
                                        <td>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td>
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                            <tr>
    
                                                                <td align="center" style="padding: 20px 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding-copy">Seu Pedido de Orçamento nro ' .$nro. ' foi reprovado!  <br /></td>
                                                            </tr>`
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="mobile-button-container">
                                                            <tr>
                                                                <td align="center" style="padding: 20px 0 0 0;font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding-copy">

                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </body>
                </html>';

        $emailEnviado = $this->email
                ->from($configEmail->username, 'Sossegue')->to($data[0]->email)
                ->subject("Sossegue - Reprovação de Pedido de Orçamento")
                ->message($mensagem)
                ->send();
    }


}
