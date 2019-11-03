<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Exclusivos extends MY_Restrita {

    private $id_manutencao = 1;
    private $CategoriaServico;
    private $Servicos;
    
    function __construct() {
        parent::__construct();
        $this->load->library("geral");
    }

    public function transformar() {

        insertTag('js','restrita/clientes_exclusivos.js', $this->data);
        
        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata('id')
                ),
            )
        );
        
        $usuario = $this->superModel->select('usuario', $condicoes);

        $email = $this->input->post('email');

        $pre_vinculo = array(
            'id_prestador' => $usuario[0]->id_usuario,
            'email' => $this->input->post('email'),
            'id_cliente' => $this->input->post('id_prestador_cliente_sem_contrato'),
            'tipo_contrato' => $this->input->post('tipo'),
            'data_hora_contratacao' => date("Y-m-d H:i:s"),
            'inicio' => $this->input->post('inicio') == "" ? null : date('Y-m-d',strtotime($this->input->post('inicio'))),
            'termino' => $this->input->post('termino') == "" ? null : date('Y-m-d',strtotime($this->input->post('termino'))),
            'status' => 'Pendente',
            'tipo_quem_incluiu' => 5,
            'outro_contrato' => $this->input->post('outro_contrato')
        );
        
        $id_prestador_cliente = $this->superModel->insert('prestador_cliente', $pre_vinculo);
        
        $this->session->set_userdata('idVinculo', $this->input->post('$id_prestador_cliente'));

        $id_prestador_cliente_encoded = base64_encode($id_prestador_cliente);
    
        $subcategorias = $this->Menus->getSubmenu($this->id_manutencao);
        $data = array(
            'row' => true,
            'select' => 'valor',
            'condicoes' => array(
                array(
                    'campo' => 'campo',
                    'valor' => 'dias_confirmacao_cadastro'
                )
            )
        );
        $diasConfirmacao = (int) $this->superModel->select('configuracoes', $data)->valor;
        $codigo_confirmacao = tokenGenerate(32);

        foreach ($subcategorias as $s) {
            if ($this->input->post($s->id_subcategoria)) {
                $servico = array(
                    'id_prestador_cliente' => $id_prestador_cliente,
                    'id_servico' => $this->input->post($s->id_subcategoria),
                    'nome' => $s->subcategoria,
                    'data_hora_cadastro' => date('d-m-Y H:i:s')
                );
                $id_prestador_cliente_servico = $this->superModel->insert('prestador_cliente_servico', $servico);
            }
        }

        $id = $this->input->post('id_prestador_cliente_sem_contrato');
        $condicoes = array(
            'campo' => 'id_prestador_cliente_sem_contrato',
            'valor' => $id
        );
        
        $this->superModel->delete('prestador_cliente_sem_contrato', $condicoes);
        
        $link = base_url('exclusivos/login/' . $id_prestador_cliente_encoded . '/' . $codigo_confirmacao);
        
        $bol = $this->enviaEmail($usuario[0], $email, $link);
        
        if ($bol) {
            $this->session->set_flashdata('mensagem', 'O Link foi Enviado para o Email!');
        } else {
            $this->session->set_flashdata('mensagem', 'O Link não Pode ser Enviado para o Email!');
        }

        redirect('restrita/exclusivos/clientes');
       
    }

   
//
    public function historico1($idVinculo) {


        $this->data['script_listar_os'] = 1;
        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_cliente_prestador',
                    'valor' => base64_decode($idVinculo)
                ),
            )
        );

        $pedido = $this->superModel->select('pedido_de_orcamento', $condicoes);

        $sql = "SELECT a.*, b.nome as nome" . " FROM tbl_prestador_cliente a " . " LEFT JOIN tbl_usuario b ON (a.id_cliente = b.id_usuario) " . " WHERE a.id_prestador_cliente=" . base64_decode($idVinculo);
        $cliente = $this->superModel->query($sql);

        $this->data['pedidos'] = $pedido;
        $this->data['nomeCliente'] = $cliente[0]->nome;
        $this->layout->view('restrita/pedidosVinculo', $this->data);
    }

    public function historico2($idVinculo) {

        $this->data['script_listar_os'] = 1;
        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_cliente_prestador',
                    'valor' => base64_decode($idVinculo)
                ),
            )
        );
        //echo base64_decode($idVinculo);

        $pedido = $this->superModel->select('pedido_de_orcamento', $condicoes);

        $sql = "SELECT a.*" . " FROM tbl_prestador_cliente_sem_contrato a " . " WHERE a.id_prestador_cliente_sem_contrato=" . base64_decode($idVinculo);
        $cliente = $this->superModel->query($sql);

        $this->data['pedidos'] = $pedido;
        //$this->data['data_visita'] = date( 'd/m/Y', strtotime( $pedido->data_hora_visita  ));
        //$this->data['hora_visita'] = date( 'H:i:s', strtotime( $pedido->data_hora_visita  ));
        if($cliente[0]->tipo_cliente == "PF"){ 
          $this->data['nomeCliente'] = $cliente[0]->nome.' '.$cliente[0]->sobrenome;
        }else{
            $this->data['nomeCliente'] = $cliente[0]->nome_fantasia;
        }
        $this->layout->view('restrita/pedidosVinculo', $this->data);
    }

    public function contrato() {

        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata['id']
                ),
            )
        );
        $usuario = $this->superModel->select('usuario', $condicoes);

        $this->data['categoria'] = $this->Menus->getMenu($this->id_manutencao);
        $subcategorias = $this->Menus->getSubmenu($this->id_manutencao);
        $this->data['subcategorias'] = $subcategorias;
 

        if($usuario[0]->id_tipo_usuario == 5){
            $sql = "SELECT a.*, b.*,c.*" . " FROM tbl_prestador_cliente a " . 
                   " LEFT JOIN tbl_usuario b ON (a.id_prestador = b.id_usuario) " . 
                   " LEFT JOIN tbl_profissional c ON (c.id_usuario = b.id_usuario) " . 
                   " WHERE a.id_cliente=" . $usuario[0]->id_usuario . " AND a.status <> 'Enviado' ";
            $vinculos = $this->superModel->query($sql);

            foreach ($vinculos as $key => $p) {
                $sql = "SELECT a.nome" . " FROM tbl_prestador_cliente_servico a" . 
                       " WHERE a.id_prestador_cliente=" . $p->id_prestador_cliente . 
                       " AND a.data_hora_exclusao is null";
                $vinculos[$key]->servico = $this->superModel->query($sql);
            }

            $this->data['vinculo'] = $vinculos;
        }else if($usuario[0]->id_tipo_usuario == 6){

            $clausulasA = array(
                    'condicoes' => array(
                        array(
                            'campo' => 'id_usuario',
                            'valor' => $usuario[0]->id_usuario
                        )
                    )
                );

            $aprovador = $this->superModel->select('aprovador', $clausulasA);
            
            $sql = "SELECT a.*, b.*,c.*" . " FROM tbl_prestador_cliente a " . 
                   " LEFT JOIN tbl_usuario b ON (a.id_prestador = b.id_usuario) " . 
                   " LEFT JOIN tbl_profissional c ON (c.id_usuario = b.id_usuario) " . 
                   " WHERE a.id_cliente=" . $aprovador[0]->id_usuario_empresa . " AND a.status <> 'Enviado' ";
            $vinculos = $this->superModel->query($sql);
            
            foreach ($vinculos as $key => $p) {
                $sql = "SELECT a.nome" . " FROM tbl_prestador_cliente_servico a" . 
                       " WHERE a.id_prestador_cliente=" . $p->id_prestador_cliente . 
                       " AND a.data_hora_exclusao is null";
                $vinculos[$key]->servico = $this->superModel->query($sql);
            }

            $this->data['vinculo'] = $vinculos;

        }else if($usuario[0]->id_tipo_usuario == 4){

            $clausulasA = array(
                    'condicoes' => array(
                        array(
                            'campo' => 'id_usuario',
                            'valor' => $usuario[0]->id_usuario
                        )
                    )
                );

            $requerente = $this->superModel->select('requerente', $clausulasA);
            
            $sql = "SELECT a.*, b.*,c.*" . " FROM tbl_prestador_cliente a " . 
                   " LEFT JOIN tbl_usuario b ON (a.id_prestador = b.id_usuario) " . 
                   " LEFT JOIN tbl_profissional c ON (c.id_usuario = b.id_usuario) " . 
                   " WHERE a.id_cliente=" . $requerente[0]->id_usuario_empresa . " AND a.status <> 'Enviado' ";
            $vinculos = $this->superModel->query($sql);
            
            
            foreach ($vinculos as $key => $p) {
                $sql = "SELECT a.nome" . " FROM tbl_prestador_cliente_servico a" . 
                       " WHERE a.id_prestador_cliente=" . $p->id_prestador_cliente. 
                       " AND a.data_hora_exclusao is null";
                $vinculos[$key]->servico = $this->superModel->query($sql);
            }

            $this->data['vinculo'] = $vinculos;

        }
        
        $this->session->set_userdata('contratos', 1);

        $this->layout->view('restrita/pedidosExclusivos', $this->data);
    }

    public function lista_servicos($idVinculo) {
        $lista_servicos = array();
        $sql = "SELECT sc.id_subcategoria, sc.subcategoria, a.id_prestador_cliente"
                . " FROM tbl_subcategoria sc "
                . " LEFT JOIN tbl_prestador_cliente_servico a on sc.id_subcategoria= a.id_servico AND a.id_prestador_cliente = " . $idVinculo . " AND a.data_hora_exclusao is null"
                . " WHERE sc.status='a'"
                . " ";

        $servicos = $this->superModel->query($sql);

        foreach ($servicos as $key => $s) {
            $lista_servicos[$key]['id_subcategoria'] = $s->id_subcategoria;
            $lista_servicos[$key]['subcategoria'] = $s->subcategoria;
            $lista_servicos[$key]['selecionado'] = $s->id_prestador_cliente == "" ? "n" : "s";
        }
        echo json_encode($lista_servicos);
    }

    public function gravar_lista_servicos() {
        $listaServicos = [];
        foreach ($this->input->post("servicos") as $key => $value) {
            $listaServicos[] = $key;
            $condicoes = array(
                'condicoes' => array(
                    array(
                        'campo' => 'id_servico',
                        'valor' => $key
                    ),
                    array(
                        'campo' => 'id_prestador_cliente',
                        'valor' => $this->input->post("id_prestador_cliente")
                    ),
                )
            );

            $servicoCadastrado = $this->superModel->select('prestador_cliente_servico', $condicoes);

            if (count($servicoCadastrado) == 0) {
                $sql = "SELECT subcategoria FROM tbl_subcategoria WHERE id_subcategoria = " . $key;
                $result = $this->superModel->query($sql);

                $servico = array(
                    'id_prestador_cliente' => $this->input->post("id_prestador_cliente"),
                    'id_servico' => $key,
                    'nome' => $result[0]->subcategoria,
                    'data_hora_cadastro' => date('d-m-Y H:i:s')
                );
                $id_prestador_cliente_servico = $this->superModel->insert('prestador_cliente_servico', $servico);
            }
        }
        $query = 'UPDATE tbl_prestador_cliente_servico '
                . '  set data_hora_exclusao = "' . date('d-m-Y H:i:s') . '"'
                . ' WHERE id_prestador_cliente =' . $this->input->post("id_prestador_cliente") . ''
                . ' AND id_servico not in (' . join($listaServicos, ",") . ')'
                . '';

        $this->superModel->query($query);

        $query = 'UPDATE tbl_prestador_cliente_servico '
                . '  set data_hora_exclusao = null'
                . ' WHERE id_prestador_cliente =' . $this->input->post("id_prestador_cliente") . ''
                . ' AND id_servico in (' . join($listaServicos, ",") . ')'
                . '';

        $this->superModel->query($query);
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function pedidos_clientes($vinculo) {

        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata['id']
                ),
            )
        );


        $usuario = $this->superModel->select('usuario', $condicoes);
        
        $sql = "SELECT a.nome" . " FROM tbl_prestador_cliente_servico a" . " WHERE a.id_prestador_cliente=" . $vinculo;
        $servicos = $this->superModel->query($sql);
        
        $this->session->set_userdata('idPrestadorCliente', $vinculo);
        $this->session->set_userdata('idCliente', $usuario[0]->id_usuario);
        $this->session->set_userdata('vinculo', $vinculo);
        $this->session->set_userdata('url_servico',$servicos[0]->nome);
        $this->session->set_userdata('cliente', $usuario[0]->id_usuario);
        redirect('restrita/pedido/'.$servicos[0]->nome);
    }

    public function historico() {
        insertTag('js', '../plugins/datetimepicker/jquery.datetimepicker.js?v=1.0', $this->data); //16-05-2018 Adiciona JS
        insertTag('js', 'jquery.maskedinput.min.js?v=1.0', $this->data); //16-05-2018 Adiciona JS
        insertTag('js', 'insere_mascara.js?v=1.0', $this->data);
        insertTag('js', 'restrita/historico_exclusivo.js', $this->data);
        insertTag('js', 'restrita/clientes_exclusivos.js', $this->data);
        $this->data['script_listar_os'] = 1;
        

        $this->data['estadosTotais'] = $this->superModel->select('estado');
        date_default_timezone_set("America/Sao_Paulo");
        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata['id']
                ),
            )
        );

        $usuario = $this->superModel->select('usuario', $condicoes);

       
        $sql = "SELECT (@row_number := @row_number + 1) AS num, x.* FROM ("
                ." SELECT pc.id_cliente, a.id_cliente_prestador, pc.id_prestador_cliente, a.nome_cliente_prestador ,a.id_pedido_de_orcamento, a.nro_pedido_de_orcamento, a.nome_sol, a.nome_aprov, a.id_usuario_empresa, a.descricao, a.localidade, a.normas, a.qualificacoes, a.status, a.prioridade, a.status_orc, a.data_pedido_de_orcamento  AS data, a.hora_pedido_de_orcamento AS hora, a.data_hora_alteracao, ua.nome nome_alteracao, a.data_hora_exclusao, ue.nome nome_exclusao, a.data_hora_visita, a.dm_atividade, 'statusOrc2' AS class"
                . " FROM tbl_pedido_de_orcamento a "
                . " left join tbl_usuario ua on a.id_usuario_alteracao = ua.id_usuario"
                . " left join tbl_usuario ue on a.id_usuario_exclusao = ue.id_usuario"
                . " left join tbl_prestador_cliente pc on pc.id_prestador_cliente = a.id_cliente_prestador"
                . " WHERE pc.id_prestador =".$usuario[0]->id_usuario
                . " AND a.id_usuario_sol=".$usuario[0]->id_usuario. "  "
                ." union all "
                . "SELECT un.id_usuario, pc.id_prestador as id_cliente_prestador, pc.id_prestador_cliente, un.nome as nome_cliente_prestador, a.id_pedido_de_orcamento, a.nro_pedido_de_orcamento, a.nome_sol, a.nome_aprov, a.id_usuario_empresa, a.descricao, a.localidade, a.normas, a.qualificacoes, a.status, a.prioridade, a.status_orc, a.data_pedido_de_orcamento  AS data, a.hora_pedido_de_orcamento AS hora, a.data_hora_alteracao, ua.nome nome_alteracao, a.data_hora_exclusao, ue.nome nome_exclusao, a.data_hora_visita, a.dm_atividade, 'statusOrc3' AS class"
                . " FROM tbl_pedido_de_orcamento a "
                . " left join tbl_usuario ua on a.id_usuario_alteracao = ua.id_usuario"
                . " left join tbl_usuario ue on a.id_usuario_exclusao = ue.id_usuario"
                . " left join tbl_usuario un on a.id_empresa = un.id_usuario"
                . " left join tbl_prestador_cliente pc on a.id_cliente_prestador = pc.id_prestador_cliente"
                . " WHERE pc.id_prestador =".$usuario[0]->id_usuario
                . " AND un.id_tipo_usuario <> 1"
                . " AND a.status NOT IN ('Pendentes de Aprovação', 'Não Aprovado', 'Removido')"
                .") x, (SELECT @row_number := 0) AS t ORDER BY x.data DESC, x.hora DESC";

          $servicos_prestador = $this->superModel->query($sql);

        $sql = "SELECT  ps.id_prestador_cliente_sem_contrato,ps.tipo_cliente, ps.nome_fantasia, ps.nome, ps.sobrenome, a.id_cliente_prestador, a.nome_cliente_prestador ,a.id_pedido_de_orcamento, a.nro_pedido_de_orcamento, a.nome_sol, a.nome_aprov, a.id_usuario_empresa, a.descricao, a.localidade, a.normas, a.qualificacoes, a.status, a.prioridade, a.status_orc, a.data_pedido_de_orcamento  AS data, a.hora_pedido_de_orcamento AS hora, a.data_hora_alteracao, ua.nome nome_alteracao, a.data_hora_exclusao, ue.nome nome_exclusao, a.data_hora_visita, a.dm_atividade
                 FROM tbl_pedido_de_orcamento a 
                left join tbl_usuario ua on a.id_usuario_alteracao = ua.id_usuario
                left join tbl_usuario ue on a.id_usuario_exclusao = ue.id_usuario
                left join tbl_prestador_cliente_sem_contrato ps on ps.id_prestador_cliente_sem_contrato = a.id_cliente_prestador
                WHERE ps.id_prestador=".$usuario[0]->id_usuario."
                 AND a.id_usuario_sol=".$usuario[0]->id_usuario. " ORDER BY a.data_pedido_de_orcamento DESC, a.hora_pedido_de_orcamento DESC";

        $servicos_prestador_sem_contrato = $this->superModel->query($sql);

        $this->data['servicos_prestador'] = $servicos_prestador;
        $this->data['servicos_prestador_sem_contrato'] = $servicos_prestador_sem_contrato;           
        
        $this->layout->view('restrita/historicoExclusivos', $this->data);
    }

//--------- parte mais antiga
    public function atividade() {

        insertTag('js', 'insere_mascara.js?v=1.0', $this->data);
        insertTag('js', 'restrita/clientes_exclusivos.js', $this->data);
        $this->data['scripts_listar_servicos'] = 1;
        insertTag('js', 'jquery.maskedinput.min.js?v=1.0', $this->data); //16-05-2018 Adiciona JS
       // insertTag('js', 'jquery.select-to-autocomplete.js?v=1.0');
        //insertTag('js', 'insere_mascara.js?v=1.0', $this->data); //16-05-2018 Adiciona JSinsertTag('js', 'jquery.maskedinput.min.js', $this->data);
        insertTag('js', 'combobox.js?v=1.0', $this->data);
        insertTag('js', 'restrita/gerar_atividade.js?v=1.0', $this->data);

        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata['id']
                ),
            )
        );
        $usuario = $this->superModel->select('usuario', $condicoes);

        $sql = "SELECT a.*, b.* FROM tbl_prestador_cliente a LEFT JOIN tbl_usuario b ON (a.id_cliente = b.id_usuario)
                 WHERE a.id_prestador = " . $usuario[0]->id_usuario . " AND a.status = 'Ativo' ";
        $clientes_com_contrato = $this->superModel->query($sql);

        $sql = "SELECT a.* FROM tbl_prestador_cliente_sem_contrato  a WHERE a.id_prestador = " . $usuario[0]->id_usuario;
        $clientes_sem_contrato = $this->superModel->query($sql);

        $this->data['clientes_com_contrato'] = $clientes_com_contrato;
        $this->data['clientes_sem_contrato'] = $clientes_sem_contrato;

        $this->data['estadosTotais'] = $this->superModel->select('estado');
     //  $this->data['cidadeTotais'] = $this->_combo_cidade($userEndereco[0]->id_estado, $userEndereco[0]->id_cidade);

        $this->layout->view('restrita/gerarAtividade', $this->data);
    }

    public function pedidos($tipo = "", $cliente = "") {

        if ($tipo == "com_contrato") {

            $condicoes = array(
                'condicoes' => array(
                    array(
                        'campo' => 'id_prestador_cliente',
                        'valor' => $cliente
                    ),
                )
            );

            $pc = $this->superModel->select('prestador_cliente', $condicoes);

            $condicoes = array(
                'condicoes' => array(
                    array(
                        'campo' => 'id_usuario',
                        'valor' => $pc[0]->id_cliente
                    ),
                )
            );

            $c = $this->superModel->select('usuario', $condicoes);
            $this->session->set_userdata('nome_cliente', $c[0]->nome);
        }

        if ($tipo == "sem_contrato") {
            $condicoes = array(
                'condicoes' => array(
                    array(
                        'campo' => 'id_prestador_cliente_sem_contrato',
                        'valor' => $cliente
                    ),
                )
            );

            $c = $this->superModel->select('prestador_cliente_sem_contrato', $condicoes);
            $this->session->set_userdata('nome_cliente', $c[0]->nome." ".$c[0]->sobrenome." ".$c[0]->nome_fantasia);
        }
        
        $this->session->set_userdata('idPrestadorCliente2', $cliente);
        $this->session->set_userdata('tipo', $tipo);
       
        redirect('restrita/pedido/Eletricista');
    }

    public function clientes() {
        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata['id']
                ),
            )
        );
        $usuario = $this->superModel->select('usuario', $condicoes);

        $subcategorias = $this->Menus->getSubmenu($this->id_manutencao);
        $this->data['subcategorias'] = $subcategorias;
        
        $sql = "
            SELECT 
                a.id_prestador_cliente,
                a.id_prestador,
                a.id_cliente,
                a.tipo_contrato,
                a.data_hora_contratacao,
                a.inicio,
                a.termino,
                a.nro_prestador_cliente,
                a.tipo_quem_incluiu,
                a.outro_contrato,
                a.status AS status_vinculo,
                a.email AS email_cliente,
                b.email,
                b.nome,
                b.id_tipo_usuario,
                b.status
            FROM
                tbl_prestador_cliente a 
            LEFT JOIN tbl_usuario b 
                    ON (a.id_cliente = b.id_usuario) 
           WHERE (  a.id_prestador = ". $usuario[0]->id_usuario ."
                    OR ( a.id_prestador = 0 
                     AND a.email = '" . $usuario[0]->email . "')
                 )";         

        $vinculos = $this->superModel->query($sql);
        foreach ($vinculos as $key => $p) {
            $sql = "SELECT a.nome" . " FROM tbl_prestador_cliente_servico a" . 
            " WHERE a.id_prestador_cliente=" . $p->id_prestador_cliente . " AND a.data_hora_exclusao is null";

            $vinculos[$key]->servico = $this->superModel->query($sql);

            $sql = "SELECT nome_original, local_gravacao "
                    . " FROM tbl_prestador_cliente_contrato "
                    . " WHERE data_hora_exclusao is null "
                    . " AND id_prestador_cliente = " . $p->id_prestador_cliente;

            $vinculos[$key]->contrato = $this->superModel->query($sql);
        }

        $this->data['usuario'] = $usuario[0];
        $this->data['servicos'] = $vinculos;
        $sql = "SELECT a.*" . " FROM tbl_prestador_cliente_sem_contrato a " . " WHERE a.id_prestador=" . $usuario[0]->id_usuario;
        $clientes_sem_vinculo = $this->superModel->query($sql);
        $this->data['clientes'] = $clientes_sem_vinculo;
        $this->data['vinculo'] = $vinculos;

        $clausulas = array(
            'order' => array(
                array(
                    'campo' => 'ordem',
                    'valor' => 'ASC'
                )
            )
        );

        $this->data['estadosTotais'] = $this->superModel->select('estado');
        $this->data['scripts_listar_servicos'] = 1;
        
        //insertTag('js', 'custom.js?v=1.0', $this->data);
        insertTag('js', 'jquery.maskedinput.min.js?v=1.0', $this->data); //16-05-2018 Adiciona JS
        insertTag('js', 'insere_mascara.js?v=1.0', $this->data);
        insertTag('js', 'combobox.js?v=1.0', $this->data);

        insertTag('js', 'restrita/clientes_exclusivos.js',$this->data);
        insertTag('js', 'restrita/vinculos.js',$this->data);
        insertTag('js', 'restrita/transformar.js?v=1.0', $this->data);
        insertTag('js', 'restrita/gerar_atividade.js?v=1.0', $this->data);
        
        
       
        $this->layout->view('restrita/clientesExclusivos', $this->data);
    }

    public function prestadores() {

        insertTag('js', 'custom.js?v=1.0', $this->data);
        insertTag('js', 'jquery.maskedinput.min.js?v=1.0', $this->data); //16-05-2018 Adiciona JS
        insertTag('js', 'insere_mascara.js?v=1.0', $this->data); //16-05-2018 Adiciona JSinsertTag('js', 'jquery.maskedinput.min.js', $this->data);
        insertTag('js', 'restrita/prestadores_exclusivos.js', $this->data);
        insertTag('js', 'restrita/clientes_exclusivos.js?v=1.0',$this->data);

        $this->data['script_2'] = 1;

        $this->data['scripts_listar_servicos'] = 1;

        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata['id']
                ),
            )
        );
        $usuario = $this->superModel->select('usuario', $condicoes);
        
        $this->data['categoria'] = $this->Menus->getMenu($this->id_manutencao);

        $subcategorias = $this->Menus->getSubmenu($this->id_manutencao);
        $this->data['subcategorias'] = $subcategorias;
        // ajeitar - fazer como clientes()
        if($usuario[0]->id_tipo_usuario == 5){

        $sql = "
            SELECT 
                a.id_prestador_cliente,
                a.id_prestador,
                a.id_cliente,
                a.tipo_contrato,
                a.data_hora_contratacao,
                a.inicio,
                a.termino,
                a.nro_prestador_cliente,
                a.tipo_quem_incluiu,
                a.outro_contrato,
                a.status AS situacao,
                a.email AS email_cliente,
                case when b.email <> '' then b.email else a.email end email,
                b.nome,
                b.id_tipo_usuario,
                b.status
            FROM
                tbl_prestador_cliente a 
            LEFT JOIN tbl_usuario b 
                    ON (a.id_prestador = b.id_usuario) 
           WHERE  a.id_cliente = ". $usuario[0]->id_usuario ;

        $vinculos = $this->superModel->query($sql);
        foreach ($vinculos as $key => $p) {
            $sql = "SELECT a.nome FROM tbl_prestador_cliente_servico a WHERE a.id_prestador_cliente = " . $p->id_prestador_cliente . " AND a.data_hora_exclusao is null";
            $vinculos[$key]->servico = $this->superModel->query($sql);
            $sql = "SELECT nome_original, local_gravacao "
                    . " FROM tbl_prestador_cliente_contrato "
                    . " WHERE data_hora_exclusao is null "
                    . " AND id_prestador_cliente=" . $p->id_prestador_cliente;

            $vinculos[$key]->contrato = $this->superModel->query($sql);
        }
        $this->data['vinculos'] = $vinculos;
    
    }else if($usuario[0]->id_tipo_usuario  == 6){

            $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata['id']
                ),
            )
        );
            $aprovador = $this->superModel->select('aprovador', $condicoes);

            $sql = "SELECT a.*, b.*" . " "
                    . "FROM tbl_prestador_cliente a "
                    . " LEFT JOIN tbl_usuario b ON (a.id_cliente = b.id_usuario) "
                    . " WHERE a.id_cliente=" . $aprovador[0]->id_usuario_empresa ;

            $vinculos = $this->superModel->query($sql);
            
            foreach ($vinculos as $key => $p) {
                $sql = "SELECT a.nome" . " FROM tbl_prestador_cliente_servico a" . " WHERE a.id_prestador_cliente=" . $p->id_prestador_cliente . " AND a.data_hora_exclusao is null";
                $vinculos[$key]->servico = $this->superModel->query($sql);

                $sql = "SELECT nome_original, local_gravacao "
                        . " FROM tbl_prestador_cliente_contrato "
                        . " WHERE data_hora_exclusao is null "
                        . " AND id_prestador_cliente=" . $p->id_prestador_cliente;

                $vinculos[$key]->contrato = $this->superModel->query($sql);
            }

            $this->data['vinculos'] = $vinculos;
    }
        $this->layout->view('restrita/prestadoresExclusivos', $this->data);
    }

    public function adiciona() {
        date_default_timezone_set("America/Sao_Paulo");

        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata['id']
                ),
            )
        );
        $usuario = $this->superModel->select('usuario', $condicoes);

        if ($usuario[0]->id_tipo_usuario == 1) {

            if ($this->form_validation->run('restrita/exclusivos/adiciona')) {
                
                $email = $this->input->post('email');
                $pre_vinculo = array(
                    'id_prestador' => $usuario[0]->id_usuario,
                    'id_cliente' => 0,
                    'tipo_contrato' => $this->input->post('tipo'),
                    'data_hora_contratacao' => date("d-m-Y H:i:s"),
                    'inicio' => $this->input->post('inicio') == "" ? null : date('Y-m-d',strtotime($this->input->post('inicio'))),
                    'termino' => $this->input->post('termino') == "" ? null : date('Y-m-d',strtotime($this->input->post('termino'))),
                    'status' => 'Pendente',
                    'tipo_quem_incluiu' => 5,
                    'outro_contrato' => $this->input->post('outro_contrato'),
                    'email' => $this->input->post('email'),
                );
                
                $id_prestador_cliente = $this->superModel->insert('prestador_cliente', $pre_vinculo);
                $this->session->set_userdata('idVinculo', $id_prestador_cliente);
                $id_prestador_cliente_encoded = base64_encode($id_prestador_cliente);

                $subcategorias = $this->Menus->getSubmenu($this->id_manutencao);
                $data = array(
                    'row' => true,
                    'select' => 'valor',
                    'condicoes' => array(
                        array(
                            'campo' => 'campo',
                            'valor' => 'dias_confirmacao_cadastro'
                        )
                    )
                );
                $diasConfirmacao = (int) $this->superModel->select('configuracoes', $data)->valor;
                $codigo_confirmacao = tokenGenerate(32);

                foreach ($subcategorias as $s) {
                    if ($this->input->post($s->id_subcategoria)) {
                        $servico = array(
                            'id_prestador_cliente' => $id_prestador_cliente,
                            'id_servico' => $this->input->post($s->id_subcategoria),
                            'nome' => $s->subcategoria,
                            'data_hora_cadastro' => date('d-m-Y H:i:s')
                        );
                        $id_prestador_cliente_servico = $this->superModel->insert('prestador_cliente_servico', $servico);
                    }
                }

                $link = base_url('exclusivos/login/' . $id_prestador_cliente_encoded . '/' . $codigo_confirmacao);
                $bol = $this->enviaEmail($usuario[0], $email, $link);
                if ($bol) {
                    $this->session->set_userdata('mensagem', 'O Link foi Enviado para o Email!');
                } else {
                    $this->session->set_userdata('mensagem', 'O Link não Pode ser Enviado para o Email!');
                }

                redirect('restrita/exclusivos/clientes');
            } else {

                /**
                 * @todo .validation_errors()
                 *              
                 */
                $this->session->set_flashdata('msg2', 'Dados informados incorretos!<br>');
                redirect('restrita/exclusivos/clientes');
            }
        } else {

            if ($this->form_validation->run('restrita/exclusivos/adiciona')) {
                $email = $this->input->post('email');
                $pre_vinculo = array(
                    'id_prestador' => 0,
                    'id_cliente' => $usuario[0]->id_usuario,
                    'tipo_contrato' => $this->input->post('tipo'),
                    'data_hora_contratacao' => date("d-m-Y, H:i:s"),
                    'inicio' => date('Y-m-d',strtotime($this->input->post('inicio'))),
                    'termino' => date('Y-m-d',strtotime($this->input->post('termino'))),
                    'status' => 'Pendente',
                    'tipo_quem_incluiu' => 1,
                    'email' => $this->input->post('email'),
                );

                $id_prestador_cliente = $this->superModel->insert('prestador_cliente', $pre_vinculo);
                $id_prestador_cliente_encoded = base64_encode($id_prestador_cliente);
                $subcategorias = $this->Menus->getSubmenu($this->id_manutencao);
                $data = array(
                    'row' => true,
                    'select' => 'valor',
                    'condicoes' => array(
                        array(
                            'campo' => 'campo',
                            'valor' => 'dias_confirmacao_cadastro'
                        )
                    )
                );
                $diasConfirmacao = (int) $this->superModel->select('configuracoes', $data)->valor;
                $codigo_confirmacao = tokenGenerate(32);

                foreach ($subcategorias as $s) {
                    if ($this->input->post($s->id_subcategoria)) {
                        $servico = array(
                            'id_prestador_cliente' => $id_prestador_cliente,
                            'id_servico' => $this->input->post($s->id_subcategoria),
                            'nome' => $s->subcategoria
                        );
                        $id_prestador_cliente_servico = $this->superModel->insert('prestador_cliente_servico', $servico);
                    }
                }

                $link = base_url('exclusivos/login/' . $id_prestador_cliente_encoded . '/' . $codigo_confirmacao);
                $this->session->unset_userdata('idVinculo');
                $bol = $this->enviaEmail($usuario[0], $email, $link);

                if ($bol) {
                    $this->session->set_userdata('mensagem', 'O Link foi Enviado para o Email!');
                } else {
                    $this->session->set_userdata('mensagem', 'O Link não Pode ser Enviado para o Email!');
                }

                redirect('restrita/exclusivos/prestadores');
            } else {
                /**
                 * @todo .validation_errors()
                 *              
                 */
                $this->session->set_flashdata('msg2', 'Dados informados incorretos!<br>');
                redirect('restrita/exclusivos/prestadores');
            }
            // $this->layout->view('restrita/clientesExclusivos', $this->data);
        }
    }

    public function visualizar($id_prestador_cliente_decoded = '') {
        if (empty($id_prestador_cliente_decoded))
            $id_prestador_cliente_decoded = base64_decode($this->session->userdata('v'));
            
        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_prestador_cliente',
                    'valor' => $id_prestador_cliente_decoded
                ),
            )
        );
        $prestador_cliente = $this->superModel->select('prestador_cliente', $condicoes);
        $sql = "SELECT a.nome" . " FROM tbl_prestador_cliente_servico a" . " WHERE a.id_prestador_cliente=" .$id_prestador_cliente_decoded;
        $servicos = $this->superModel->query($sql);
        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $prestador_cliente[0]->id_prestador
                ),
            )
        );
        $prestador = $this->superModel->select('usuario', $condicoes);
        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $prestador_cliente[0]->id_cliente
                ),
            )
        );
        $clientes = $this->superModel->select('usuario', $condicoes);
        $this->data['prestador_cliente'] = $prestador_cliente[0];
        $this->data['prestador'] = $prestador[0];
        $this->data['clientes'] = $clientes[0];
        $this->data['servicos'] = $servicos;
        $this->data['id_prestador_cliente_decoded'] = $id_prestador_cliente_decoded;
        $this->layout->view('restrita/vinculo', $this->data);
    }

    public function aceitar($idVinculo) {
        if (is_numeric($idVinculo))
            $id_prestador_cliente_decoded = $idVinculo;
        else
            $id_prestador_cliente_decoded = base64_decode($idVinculo);

        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_prestador_cliente',
                    'valor' => $id_prestador_cliente_decoded
                ),
            )
        );
        $vinculo = $this->superModel->select('prestador_cliente', $condicoes);

        $condicoes2 = array(
            array(
                'campo' => 'id_usuario',
                'valor' => $this->session->userdata['id'],
            ),
        );
        $data = array(
            'exclusividade' => 1
        );

        $this->superModel->update('usuario', $data, $condicoes2);

        
        $condicoes = array(
            array(
                'campo' => 'id_prestador_cliente',
                'valor' => $id_prestador_cliente_decoded
            ),
        );

        $set_vinculo = array(
            'status' => 'Ativo'
        );

        if ($vinculo[0]->id_cliente == 0){
            $set_vinculo['id_cliente'] = $this->session->userdata['id'];
        } else if ($vinculo[0]->id_prestador == 0) {
            $set_vinculo['id_prestador'] = $this->session->userdata['id'];
        }
        
        
        $this->superModel->update('prestador_cliente', $set_vinculo, $condicoes);

        $this->session->unset_userdata("v");
        redirect('restrita/home');
    }

    public function recusar($idVinculo) {
        $id_prestador_cliente_decoded = base64_decode($idVinculo);
        $condicoes = array(
            array(
                'campo' => 'id_prestador_cliente',
                'valor' => $id_prestador_cliente_decoded
            ),
        );
        $vinculo = array(
            'status' => 'Recusado'
        );
        $this->superModel->update('prestador_cliente', $vinculo, $condicoes);
        $this->session->unset_userdata("v");

        redirect('restrita/home');
    }


     public function dados_transformar($idCliente) {
        
        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_prestador_cliente_sem_contrato',
                    'valor' => $idCliente
                ),
            )
        );
        $dados = $this->superModel->select('prestador_cliente_sem_contrato', $condicoes);
        echo json_encode($dados[0]);
    }



    public function dados($idCliente) {

        $sql = "SELECT a.*"
                . " FROM tbl_usuario a "
                . " WHERE a.id_usuario = ".$idCliente;;

         $sql2 = "SELECT b.*, c.nome AS cidade, e.nome AS estado, e.uf
                    FROM tbl_usuario a 
                   INNER JOIN tbl_usuario_endereco b ON (b.id_usuario = a.id_usuario)
                   LEFT JOIN tbl_cidade c ON (c.id = b.id_cidade)
                   LEFT JOIN tbl_estado e ON (e.id = c.estado)
                   WHERE a.id_usuario = ".$idCliente;

        $dados = $this->superModel->query($sql);
        $end = $this->superModel->query($sql2);

        /*  $condicoes = array(
          'condicoes' => array(
          array(
          'campo' => 'id_usuario',
          'valor' => $idCliente
          ),
          )
          );

          $dados = $this->superModel->select('usuario', $condicoes); */

        $dados = array(
            'nome' => $dados[0]->nome,
            'cpf' => $dados[0]->cpf,
            'tipo' => 'Usuario Empresa',
            'telefone' => $dados[0]->telefone,
            'celular' => $dados[0]->telefone2,
            'email' => $dados[0]->email,
            'cidade' => $end[0]->cidade . " - ".$end[0]->uf,
            'endereco' => $end[0]->endereco,
            'num' => $end[0]->numero,
            'compl' => $end[0]->complemento,
            'bairro' => $end[0]->bairro
        );
        $jsonDados = json_encode($dados);
        echo $jsonDados;
    }
     
     public function status() {
        
        $dados = array(
            'inicio' => date('Y-m-d',strtotime($this->input->post('inicio2'))),
            'termino' => date('Y-m-d',strtotime($this->input->post('termino2'))),
        );

        $condicoes = array(
            array(
                'campo' => 'id_prestador_cliente',
                'valor' => $this->input->post('id_prestador_cliente2')
            )
        );

        $this->superModel->update('prestador_cliente', $dados, $condicoes);
        
        //redirect($_SERVER['HTTP_REFERER']);
        redirect('restrita/exclusivos/clientes');
    }

     public function dados_vinc($id) {
        
        $sql = "SELECT a.*"
                . " FROM tbl_prestador_cliente a"
                . " WHERE a.id_prestador_cliente = ".$id;

        $d = $this->superModel->query($sql);
        
        $dados = array(
            'inicio' => $d[0]->inicio == "0000-00-00 00:00:00" ? "" : date("d-m-Y", strtotime($d[0]->inicio)),
            'termino' => $d[0]->termino == "0000-00-00 00:00:00" ? "" : date("d-m-Y", strtotime($d[0]->termino)),
            'id_prestador_cliente' => $d[0]->id_prestador_cliente
        );
       
        $jsonDados = json_encode($dados);
        echo $jsonDados;
    }

    public function dados_prestador($idPrestador) {


        $sql = "SELECT a.*"
                . " FROM tbl_usuario a "
                . " WHERE a.id_usuario = ".$idPrestador;

        $sql2 = "SELECT b.*, c.nome AS cidade, e.nome AS estado, e.uf
                   FROM tbl_usuario a 
                  INNER JOIN tbl_usuario_endereco b ON (b.id_usuario = a.id_usuario)
                   LEFT JOIN tbl_cidade c ON (c.id = b.id_cidade)
                   LEFT JOIN tbl_estado e ON (e.id = c.estado)
                  WHERE a.id_usuario = ".$idPrestador;

        $dados = $this->superModel->query($sql);
        $end = $this->superModel->query($sql2);

        $dados = array(
            'nome' => $dados[0]->nome,
            'cpf' => $dados[0]->cpf,
            'tipo' => 'Prestador',
            'telefone' => $dados[0]->telefone,
            'celular' => $dados[0]->telefone2,
            'email' => $dados[0]->email,
            'cidade' => $end[0]->cidade . " - ".$end[0]->uf,
            'endereco' => $end[0]->endereco,
            'num' => $end[0]->numero,
            'compl' => $end[0]->complemento,
            'bairro' => $end[0]->bairro
        );
        $jsonDados = json_encode($dados);
        echo $jsonDados;
    }

    public function dados_sem_contrato($idCliente) {
        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_prestador_cliente_sem_contrato',
                    'valor' => $idCliente
                ),
            )
        );
        $dados = $this->superModel->select('prestador_cliente_sem_contrato', $condicoes);
        echo json_encode($dados[0]);
    }

    public function observacoes($idCliente) {
        $this->data['script_clientes'] = 1;
        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_prestador_cliente',
                    'valor' => $idCliente
                ),
            )
        );
        $observacoes = $this->superModel->select('prestador_cliente', $condicoes);
        $o = $observacoes[0]->observacoes;
        $jsonObservacoes = json_encode($o);
        echo $jsonObservacoes;
    }

    public function enviaEmail($usuarioPrestador, $emailCliente, $link) {
        $this->load->library('email');
        $mensagem = '<!DOCTYPE html>
                <html lang="pt-br">
                <head>
                    <title>Sossegue - Confirmação de cadastro</title>
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
                                            <td bgcolor="#ffffff" width="100" align="left"><a href="' . base_url() . '" target="_blank">
                                                <img alt="Logo Sossegue" src="' . base_url("assets/img/logo_mod-a.png") . '" width="130"
                                                    style="display: block;" border="0"></a>
                                            </td>
                                            <td bgcolor="#ffffff" width="400" align="right" class="mobile-hide">
                                                <table border="0" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td align="right" style="padding: 0 0 5px 0; font-size: 14px; font-family: Arial,
                                                            sans-serif; color: #666666; text-decoration: none;"><span style="color: #666666;
                                                            text-decoration: none;"><a href="' . base_url() . '" target="_blank">SOSSEGUE</a>
                                                            <br />Sua vida mais tranquila</span>
                                                        </td>
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
                                                    <td align="center" style="padding: 20px 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding-copy">' . $usuarioPrestador->nome . ' deseja ter um vínculo exclusivo com sua conta na Sossegue. Cadastre-se ou faça login para continuar. </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="mobile-button-container">
                                                <tr>
                                                    <td align="center" style="padding: 25px 0 0 0;" class="padding-copy">
                                                        <table border="0" cellspacing="0" cellpadding="0" class="responsive-table">
                                                            <tr>
                                                                <td align="center"><a href="' . $link . '" target="_blank" style="font-size: 16px; font-family: Helvetica, Arial, sans-serif; font-weight: normal; color: #ffffff; text-decoration: none; background-color: #5D9CEC; border-top: 15px solid #5D9CEC; border-bottom: 15px solid #5D9CEC; border-left: 25px solid #5D9CEC; border-right: 25px solid #5D9CEC; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; display: inline-block;" class="mobile-button">Cadastro/Login&rarr;</a></td>
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
                </td>
            </tr>
        </table>
    </body>
    </html>';
        $configEmail = $this->superModel->getRow('config_email');
        $emailEnviado = $this->email
                        ->from($configEmail->username, 'Sossegue')
                        ->to($emailCliente)
                        ->subject("Sossegue - Link de Cadastro")
                        ->message($mensagem)->send();
        if (!$emailEnviado) {
            /* $this->superModel->insert('log', array(
              'texto' => $this->email->print_debugger(),
              'pagina' => $this->uri->uri_string(),
              'post' => $this->input->post() ? json_encode($this->input->post()) : '',
              'data' => now(),
              'ip' => $this->input->ip_address()
              )); */
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function salva() {

        if ($this->form_validation->run('restrita/exclusivos/salva')) {

            if($this->input->post('tipo') == 'PF'){ 
                    
                    $clausulas = array(
                        'select' => 'id_prestador_cliente_sem_contrato',
                        'row' => true,
                        'condicoes' => array(
                            array(
                                'campo' => 'id_prestador',
                                'valor' => $this->session->userdata["id"],

                            ),
                            array(
                                'campo' => 'nome',
                                'valor' => $this->input->post('nome'),
                     
                            ),
                             array(
                                'campo' => 'sobrenome',
                                'valor' => $this->input->post('sobrenome'),
                            ),
                             array(
                                'campo' => 'cpf_cnpj',
                                'valor' => $this->input->post('cpf_cnpj'),

                            ),
                             array(
                                'campo' => 'telefone',
                                'valor' => $this->input->post('telefone'),
                            ),
                             array(
                                'campo' => 'celular',
                                'valor' => $this->input->post('celular'),
                            )
                    ) );
             
              }else {
                            $clausulas = array(
                            'select' => 'id_prestador_cliente_sem_contrato',
                            'row' => true,
                            'condicoes' => array(
                               array(
                                'campo' => 'id_prestador',
                                'valor' => $this->session->userdata["id"],

                            ),
                                array(
                                    'campo' => 'nome_fantasia',
                                    'valor' => $this->input->post('nome_fantasia')
                                )
                               
                        ) );
            }


            if (!$this->superModel->select('prestador_cliente_sem_contrato', $clausulas)) {

                $condicoes = array(
                    'condicoes' => array(
                        array(
                            'campo' => 'id_usuario',
                            'valor' => $this->session->userdata['id']
                        ),
                    )
                );
                $usuario = $this->superModel->select('usuario', $condicoes);

                $data_cliente = array(
                    'id_prestador' => $usuario[0]->id_usuario,
                    'tipo_cliente' => $this->input->post('tipo'),
                    'nome' => $this->input->post('nome'),
                    'nome_fantasia' => $this->input->post('nome_fantasia'),
                    'sobrenome' => $this->input->post('sobrenome'),
                    'email' => $this->input->post('email'),
                    'telefone' => $this->input->post('telefone'),
                    'celular' => $this->input->post('celular'),
                    'endereco' => $this->input->post('endereco'),
                    'num' => $this->input->post('num'),
                    'complemento' => $this->input->post('complemento'),
                    'bairro' => $this->input->post('bairro2'),
                    'id_cidade' => $this->input->post('cidade'),
                    'id_estado' => $this->input->post('estado'),
                    'cpf_cnpj' => $this->input->post('cpf_cnpj'),
                    'cep' => $this->input->post('cep'),
                    'status' => 'Ativo',
                    'nro' => $this->db->count_all_results('prestador_cliente_sem_contrato') +1
                );
                $id_prestador_cliente_sem_contrato = $this->superModel->insert('prestador_cliente_sem_contrato', $data_cliente);

                redirect('restrita/exclusivos/clientes');
            } else if ($this->superModel->select('prestador_cliente_sem_contrato', $clausulas)) {

            $this->session->set_flashdata('msg2', 'Dados já existem! Deseja fazer pedido para respectivo cliente?');
                $this->session->set_userdata('nome', $this->input->post('nome'));
                redirect('restrita/exclusivos/clientes');
            }
        } else {

            $this->session->set_flashdata('msg1', 'Dados não puderam ser salvos no Formulário!');

            redirect('restrita/exclusivos/clientes');
        }
    }

    public function alterar() {

        if ($this->form_validation->run('restrita/exclusivos/alterar')) {

            $data_cliente = array(
                'tipo_cliente' => $this->input->post('tipo'),
                'nome' => $this->input->post('nome'),
                'nome_fantasia' => $this->input->post('nome_fantasia'),
                'sobrenome' => $this->input->post('sobrenome'),
                'email' => $this->input->post('email'),
                'telefone' => $this->input->post('telefone'),
                'celular' => $this->input->post('celular'),
                'endereco' => $this->input->post('endereco'),
                'num' => $this->input->post('num'),
                'complemento' => $this->input->post('complemento'),
                'bairro' => $this->input->post('bairro2'),
                'id_cidade' => $this->input->post('cidade'),
                'id_estado' => $this->input->post('estado'),
                'cpf_cnpj' => $this->input->post('cpf_cnpj'),
                'cep' => $this->input->post('cep'),
                'status' => 'Ativo'
            );
            $condicoes = array(
                array(
                    'campo' => 'id_prestador_cliente_sem_contrato',
                    'valor' => $this->input->post('id_prestador_cliente_sem_contrato')
                )
            );
            $this->superModel->update('prestador_cliente_sem_contrato', $data_cliente, $condicoes);

            redirect($_SERVER['HTTP_REFERER']);
        } else {

            $this->session->set_flashdata('msg1', 'Dados não puderam ser salvos no Formulário!');

            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function grava() {

        if ($this->form_validation->run('restrita/exclusivos/grava')) {
//mudar
        $celular = ($this->input->post('celular')==null)? $this->input->post('celular'): 0;
        $telefone =  ($this->input->post('telefone')==null)? $this->input->post('telefone'): 0;
        $cpf_cnpj =  ($this->input->post('cpf_cnpj')==null)? $this->input->post('cpf_cnpj'): 0;
        
        if($this->input->post('tipo') == 'PF'){ 
                    
                  $clausulas = array(
                        'select' => 'id_prestador_cliente_sem_contrato',
                        'row' => true,
                        'condicoes' => array(
                             array(
                                'campo' => 'id_prestador',
                                'valor' => $this->session->userdata["id"],

                            ),
                            array(
                                'campo' => 'nome',
                                'valor' => $this->input->post('nome'),

                            ),
                             array(
                                'campo' => 'sobrenome',
                                'valor' => $this->input->post('sobrenome'),

                            ),
                             array(
                                'campo' => 'cpf_cnpj',
                                'valor' => $cpf_cnpj,
                            ),
                             array(
                                'campo' => 'telefone',
                                'valor' => $telefone,
                            ),
                             array(
                                'campo' => 'celular',
                                'valor' => $celular,
                            )
                    ) );
                /*$sql = "SELECT a.*" . " FROM tbl_prestador_cliente_sem_contrato a" . "a.nome =".$this->input->post('nome')." AND a.sobrenome =".$this->input->post('sobrenome')." AND a.cpf_cnpj =".$cpf_cnpj." AND a.telefone =".$telefone." AND a.celular =".$celular;
                $cliente = $this->superModel->query($sql);*/
             
              }else {
                            $clausulas = array(
                            'select' => 'id_prestador_cliente_sem_contrato',
                            'row' => true,
                            'condicoes' => array(
                                array(
                                    'campo' => 'nome_fantasia',
                                    'valor' => $this->input->post('nome_fantasia')
                                ),
                                 array(
                                'campo' => 'id_prestador',
                                'valor' => $this->session->userdata["id"],

                            )
                               
                        ) );


            }


            if (!$this->superModel->select('prestador_cliente_sem_contrato', $clausulas)) {

                $condicoes = array(
                    'condicoes' => array(
                        array(
                            'campo' => 'id_usuario',
                            'valor' => $this->session->userdata['id']
                        ),
                    )
                );
                $usuario = $this->superModel->select('usuario', $condicoes);
               

               $data_cliente = array(
                    'id_prestador' => $usuario[0]->id_usuario,
                    'tipo_cliente' => $this->input->post('tipo'),
                    'nome' => $this->input->post('nome'),
                    'nome_fantasia' => $this->input->post('nome_fantasia'),
                    'sobrenome' => $this->input->post('sobrenome'),
                    'email' => $this->input->post('email'),
                    'telefone' => $this->input->post('telefone'),
                    'celular' => $this->input->post('celular'),
                    'endereco' => $this->input->post('endereco'),
                    'bairro' => $this->input->post('bairro2'),
                    'id_cidade' => $this->input->post('cidade'),
                    'id_estado' => $this->input->post('estado'),
                    'cpf_cnpj' => $this->input->post('cpf_cnpj'),
                    'cep' => $this->input->post('cep'),
                    'status' => 'Ativo'
                );
                $id_prestador_cliente_sem_contrato = $this->superModel->insert('prestador_cliente_sem_contrato', $data_cliente);
            
                $this->session->set_userdata('nome_cliente', $this->input->post('nome')."  ".$this->input->post('sobrenome')." ".$this->input->post('nome_fantasia'));
 		        $this->session->set_userdata('idPrestadorCliente1',$id_prestador_cliente_sem_contrato);
                

                redirect('restrita/exclusivos/pedidos');
            } else if ($this->superModel->select('prestador_cliente_sem_contrato', $clausulas)) {

                $this->session->set_flashdata('msg2', 'Dados já existem! Deseja fazer pedido para respectivo cliente?');
            
                $id_prestador_cliente = $this->superModel->select('prestador_cliente_sem_contrato', $clausulas);

                $cliente = $this->superModel->select('prestador_cliente_sem_contrato', $clausulas);
                $id_cliente = $cliente->id_prestador_cliente_sem_contrato;
               // $this->session->set_userdata('nome_cliente', $this->input->post('nome')."  ".$this->input->post('sobrenome')." ".$this->input->post('nome_fantasia'));
                $this->session->set_userdata('idPrestadorCliente1', $id_prestador_cliente->id_prestador_cliente_sem_contrato);
                
                redirect('restrita/exclusivos/atividade');
            }
        } else {

            $this->session->set_flashdata('msg1', 'Dados não puderam ser salvos no Formulário!');

            redirect('restrita/exclusivos/atividade');
        }
    }

    public function servicos($idVinculo) {
        $sql = "SELECT a.*" . " FROM tbl_prestador_cliente_servico a " . " WHERE a.id_prestador_cliente=" . $idVinculo;
        $result = array(
            'contidos' => $this->superModel->query($sql),
            'totais' => $subcategorias = $this->Menus->getSubmenu($this->id_manutencao)
        );
        $jsonDados = json_encode($result);
        echo $jsonDados;
    }

    public function inclui($idVinculo) {
        $subcategorias = $this->Menus->getSubmenu($this->id_manutencao);
        foreach ($subcategorias as $s) {
            if ($this->input->post($s->id_subcategoria)) {
                $servico = array(
                    'id_prestador_cliente' => $idVinculo,
                    'id_servico' => $this->input->post($s->id_subcategoria),
                    'nome' => $s->subcategoria
                );
                $id_prestador_cliente_servico = $this->superModel->insert('prestador_cliente_servico', $servico);
            }
        }

        redirect('restrita/exclusivos/clientes');
    }

    public function anexar_contrato() {

        $contrato = $this->input->post('contrato');

        if (!$_FILES['contrato']['error']) {

            $data = array(
                'dir' => './uploads/contratos/',
                'fileName' => $this->input->post('id_prestador_cliente') . '_' . date("U") . '_' . $_FILES['contrato']['name'],
                'inputName' => 'contrato',
            );

            $contrato = $this->realizaUploadDocumento($data);
            if ($contrato) {
                $contrato = array(
                    'id_prestador_cliente' => $this->input->post('id_prestador_cliente'),
                    'data_hora_cadastro' => date("d-m-Y H:i:s"),
                    'local_gravacao' => $contrato,
                    'nome_original' => $_FILES['contrato']['name']
                );
                $this->superModel->insert('prestador_cliente_contrato', $contrato);
            }


            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function dadosPedidoOrcamento($id_pedido_de_orcamento){
        $sql = "SELECT DATE_FORMAT(a.data_hora_visita,'%d/%m/%Y') data_hora_visita,
                       DATE_FORMAT(a.data_hora_termino,'%d/%m/%Y') data_hora_termino,
                       DATE_FORMAT(a.data_hora_visita,'%H:%i') hora_visita,
                       DATE_FORMAT(a.data_hora_termino,'%H:%i') hora_termino,
                       a.dm_atividade,
                       a.id_pedido_de_orcamento
                  FROM tbl_pedido_de_orcamento a
                 WHERE id_pedido_de_orcamento = $id_pedido_de_orcamento";
        $orca = $this->superModel->query($sql);       

        foreach ($orca as $key => $value) {
            $orca[$key]->colaboradores = $this->buscarColaboradorPorPedido($value->id_pedido_de_orcamento, "0");
        }
        
        if(empty($orca))
            echo json_encode(["codigo" => "999", "mensagem" => "Não foi encontrado nenhum resultado"]);
        else
            echo json_encode($orca[0]);
    }

    public function agendar_visita() {
        $data_hora_visita = $this->input->post('data_hora_visita') . ' ' . $this->input->post('hora_visita');
        $data_hora_termino = $this->input->post('data_hora_termino') . ' ' . $this->input->post('hora_termino');
        $id_pedido_de_orcamento = $this->input->post('id_pedido_de_orcamento');
        $dm_atividade = $this->input->post('dm_atividade');

        $id_usuario_alteracao = $this->session->userdata('id');

        $colaboradores = $this->input->post('id_colaborador');
        
        $query = "UPDATE tbl_pedido_de_orcamento 
                    SET data_hora_visita = str_to_date('$data_hora_visita','%d/%m/%Y %H:%i'), 
                        data_hora_termino = str_to_date('$data_hora_termino','%d/%m/%Y %H:%i'),
                        id_usuario_alteracao = $id_usuario_alteracao,
                        dm_atividade = '$dm_atividade',
                        status_orc = 'Agendada Atividade'
                WHERE id_pedido_de_orcamento = $id_pedido_de_orcamento";

        $this->superModel->query($query);
    
        $condicoes = array(
            'campo' => 'id_pedido_de_orcamento',
            'valor' => $id_pedido_de_orcamento
        );
        $this->superModel->delete('pedido_colaborador',$condicoes);

        if (!empty($colaboradores)){
            foreach ($colaboradores as $key => $id_colab) {
                $dados["id_colaborador"] = $id_colab;
                $dados["id_pedido_de_orcamento"] = $id_pedido_de_orcamento;
                $this->superModel->insert('pedido_colaborador',$dados);
            }
        }

        echo json_encode(array("codigo" => "999", "mensagem" => "Visita agendada com sucesso."));
    }

    public function delele_colab(){
        if ($this->input->post("id_pedido_colaborador")){
            $condicoes = array(
                'campo' => 'id_pedido_colaborador',
                'valor' => $this->input->post("id_pedido_colaborador")
            );
            $this->superModel->delete('pedido_colaborador',$condicoes);
        }
    }
 

    public function mudar_status_orc(){
        if ($this->input->post("id_pedido_de_orcamento")){
            $data = array(
                'status_orc' => $this->input->post('status_orc')
            );

            $condicoes = array(
                array(
                    'campo' => 'id_pedido_de_orcamento',
                    'valor' => $this->input->post('id_pedido_de_orcamento')
                )
            );
            $this->superModel->update('pedido_de_orcamento', $data, $condicoes);
        }

        redirect($_SERVER['HTTP_REFERER']);
    }

    public function combo_cidade($id_estado, $id_cidade) {
        $combo = '<option class="option_cad">CIDADE</option>';
        $combo .= $this->_combo_cidade($id_estado, $id_cidade);
        echo $combo;
    }
    
    public function estados() {
        
        echo json_encode( $this->superModel->select('estado'));

    }
    
    private function _combo_cidade($id_estado, $id_cidade) {
        $option = "";
        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'estado',
                    'valor' => $id_estado
                )
            )
        );

        $cidades = $this->superModel->select('cidade', $condicoes);

        if (count($cidades) > 0) {
            foreach ($cidades as $cidade) {
                $option .= '<option value ="' . $cidade->id . '" ' . ($cidade->id === $id_cidade ? 'selected="selected"' : '') . '>' . $cidade->nome . ' </option>';
            }
        }

        return $option;
    }
        
    public function alterarStatus() {
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
        echo json_encode(array("codigo" => "999", "mensagem" => "Status alterado com sucesso."));
    }

    public function salvaColaborador(){

        if ($this->form_validation->run('restrita/exclusivos/salvarColaborador')) {

            if(empty($this->input->post('id_colaborador'))){
                $data = array(
                    'id_colaborador' => '',
                    'id_usuario' => $this->session->userdata['id'],
                    'nome' => $this->input->post('nome'),
                    'sobrenome' => $this->input->post('sobrenome'),
                    'email' => $this->input->post('email'),
                    'telefone' => $this->input->post('telefone'),
                    'celular' => $this->input->post('celular'),
                    'cpf' => $this->input->post('cpf')
                );

                $id_colaborador = $this->superModel->insert('colaborador', $data);

                $subcategorias = $this->Menus->getSubmenu($this->id_manutencao);

                foreach ($subcategorias as $s) {
                    if ($this->input->post($s->id_subcategoria)) {
                        $servico = array(
                            'id_colaborador_servicos' => '',
                            'id_colaborador' => $id_colaborador,
                            'id_servico' => $this->input->post($s->id_subcategoria),
                            'nome' => $s->subcategoria
                        );

                        $this->superModel->insert('colaborador_servicos', $servico);
                    }
                }

                $this->session->set_flashdata('mensagem', 'Colaborador inserido com Sucesso!');
            } else {

                $condicoes = array(
                    array(
                        'campo' => 'id_colaborador',
                        'valor' => $this->input->post('id_colaborador')
                    )
                );

                $data = array(
                    'nome' => $this->input->post('nome'),
                    'sobrenome' => $this->input->post('sobrenome'),
                    'email' => $this->input->post('email'),
                    'telefone' => $this->input->post('telefone'),
                    'celular' => $this->input->post('celular'),
                    'cpf' => $this->input->post('cpf')
                );

                $this->superModel->update('colaborador', $data, $condicoes);

                $this->session->set_flashdata('mensagem', "Colaborador alterado com Sucesso!");
            }
            redirect($_SERVER['HTTP_REFERER']);
        } else {

            $this->session->set_flashdata('mensagem', 'Dados não puderam ser salvos no Formulário!');

            redirect($_SERVER['HTTP_REFERER']);
        }
    }
 
    public function lista_servicos_colab($idVinculo) {
        $lista_servicos = array();
        $sql = "SELECT sc.id_subcategoria, sc.subcategoria, a.id_colaborador"
                . " FROM tbl_subcategoria sc "
                . " LEFT JOIN tbl_colaborador_servicos a on sc.id_subcategoria= a.id_servico AND a.id_colaborador = " . $idVinculo 
                . " WHERE sc.status='a'"
                . " ";

        $servicos = $this->superModel->query($sql);

        foreach ($servicos as $key => $s) {
            $lista_servicos[$key]['id_subcategoria'] = $s->id_subcategoria;
            $lista_servicos[$key]['subcategoria'] = $s->subcategoria;
            $lista_servicos[$key]['selecionado'] = $s->id_colaborador == "" ? "n" : "s";
        }
        echo json_encode($lista_servicos);
    }

    public function alterarServicoColaborador() {
        $listaServicos = $this->input->post("servicos");

        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_colaborador',
                    'valor' => $this->input->post("id_colaborador")
                ),
            )
        );

        $servicoCadastrado = $this->superModel->select('colaborador_servicos', $condicoes);

        foreach ($servicoCadastrado as $servico) {
            if (!isset($listaServicos[$servico->id_servico])){
                $condicoes = array(
                    'campo' => 'id_colaborador_servicos',
                    'valor' => $servico->id_colaborador_servicos
                );
                
                $this->superModel->delete('colaborador_servicos', $condicoes);
            }
        }

        foreach ($listaServicos as $key => $value) {
            $achou = false;
            foreach ($servicoCadastrado as $servico) {
                if($key == $servico->id_servico)
                    $achou = true;
            }

            if (!$achou){
                $data = array(
                    'id_colaborador' => $this->input->post("id_colaborador"),
                    'id_servico' => $key,
                    'nome' => Key($value)
                );
                
                $this->superModel->insert('colaborador_servicos', $data);
            }
        }

        redirect($_SERVER['HTTP_REFERER']);
    }

    public function buscaColaborador($return_json = "0") {
        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata['id']
                ),
            )
        );
        $dados = $this->superModel->select('colaborador', $condicoes);
        if ($return_json == "0")
            return $dados;
        else {
            if(empty($dados))
                echo json_encode(["codigo" => "999", "mensagem" => "Não foi encontrado nenhum resultado"]);
            else
                echo json_encode($dados);
        }
    }

    public function dadosColaborador($id_colaborador) {
        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_colaborador',
                    'valor' => $id_colaborador
                ),
            )
        );
        $dados = $this->superModel->select('colaborador', $condicoes);
        if (empty($dados))
            echo json_encode(['msg' => 'Não foi encontrado o colaborador']);
        else
            echo json_encode($dados[0]);
    }

    public function colaborador(){
        $subcategorias = $this->Menus->getSubmenu($this->id_manutencao);

        $sql = "
            SELECT
                a.id_colaborador,
                a.nome as nome_colaborador,
                a.sobrenome as sobrenome_colaborador,
                a.cpf as cpf_colaborador,
                a.email as email_colaborador,
                a.telefone as telefone_colaborador,
                a.celular as celular_colaborador
            FROM
                tbl_colaborador a
            LEFT JOIN tbl_usuario b 
                    ON (a.id_usuario = b.id_usuario) 
           WHERE a.id_usuario = ". $this->session->userdata['id'] ;

        $_colaboradores = $this->superModel->query($sql);

        foreach ($_colaboradores as $key => $colab) {
            $sql = " SELECT a.nome 
                       FROM tbl_colaborador_servicos a 
                      WHERE a.id_colaborador = " . $colab->id_colaborador;

            $_colaboradores[$key]->servico = $this->superModel->query($sql);
        }

        $this->data['colaboradores'] = $_colaboradores;
        $this->data['subcategorias'] = $subcategorias;

        insertTag('js', 'jquery.maskedinput.min.js?v=1.0', $this->data); //16-05-2018 Adiciona JS
        insertTag('js', 'combobox.js?v=1.0', $this->data);
        insertTag('js', 'insere_mascara.js?v=1.0', $this->data);
        insertTag('js', 'custom.js?v=1.0', $this->data);
        insertTag('js', 'restrita/colaborador_exclusivo.js', $this->data);

       
        $this->layout->view('restrita/colaborador', $this->data);
    }

    public function agendaByData($dia){
        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata['id']
                ),
            )
        );
        $usuario = $this->superModel->select('usuario', $condicoes);

        $sql = "
        /* servicos_prestador */
        SELECT * FROM (
            SELECT 
                a.id_pedido_de_orcamento,
                DATE_FORMAT(a.data_hora_visita,'%d/%m/%Y') data_hora_visita,
                DATE_FORMAT(a.data_hora_termino,'%d/%m/%Y') data_hora_termino,
                DATE_FORMAT(a.data_hora_visita,'%H:%i') hora_visita,
                DATE_FORMAT(a.data_hora_termino,'%H:%i') hora_termino,
                a.nome_cliente_prestador AS nome,  
                e.bairro,  
                IF(dm_atividade ='o', 'Orçamento',IF(dm_atividade = 'v', 'Visita Técnica', 'Realização do Serviço')) as dm_atividade,
                a.status_orc,
                pc.id_cliente AS id_usuario,
                1 tp_cli
            FROM
                tbl_pedido_de_orcamento a  
                LEFT JOIN tbl_prestador_cliente pc ON pc.id_prestador_cliente = a.id_cliente_prestador 
                LEFT JOIN tbl_usuario u ON u.id_usuario = pc.id_cliente
                LEFT JOIN tbl_usuario_endereco e ON e.id_usuario = pc.id_cliente
            WHERE pc.id_prestador = ". $usuario[0]->id_usuario ." 
                AND a.id_usuario_sol = ". $usuario[0]->id_usuario ." 
            UNION ALL
            /* servicos_cliente */
            SELECT 
                a.id_pedido_de_orcamento,
                DATE_FORMAT(a.data_hora_visita,'%d/%m/%Y') data_hora_visita,
                DATE_FORMAT(a.data_hora_termino,'%d/%m/%Y') data_hora_termino,
                DATE_FORMAT(a.data_hora_visita,'%H:%i') hora_visita,
                DATE_FORMAT(a.data_hora_termino,'%H:%i') hora_termino,
                un.nome,
                e.bairro,
                IF(dm_atividade ='o', 'Orçamento',IF(dm_atividade = 'v', 'Visita Técnica', 'Realização do Serviço')) as dm_atividade,
                a.status_orc,
                un.id_usuario,
                1 tp_cli
            FROM
                tbl_pedido_de_orcamento a 
                LEFT JOIN tbl_usuario un ON  un.id_usuario = a.id_empresa
                LEFT JOIN tbl_prestador_cliente pc ON pc.id_prestador_cliente = a.id_cliente_prestador
                LEFT JOIN tbl_usuario_endereco e ON e.id_usuario = un.id_usuario
            WHERE pc.id_prestador = ". $usuario[0]->id_usuario ." 
                AND un.id_tipo_usuario <> 1 
                AND a.status <> 'Pendentes de Aprovação' 
            UNION ALL
            /* cliente sem contrato */
            SELECT 
                a.id_pedido_de_orcamento,
                DATE_FORMAT(a.data_hora_visita,'%d/%m/%Y') data_hora_visita,
                DATE_FORMAT(a.data_hora_termino,'%d/%m/%Y') data_hora_termino,
                DATE_FORMAT(a.data_hora_visita,'%H:%i') hora_visita,
                DATE_FORMAT(a.data_hora_termino,'%H:%i') hora_termino,
                IF(ps.tipo_cliente = 'PF',CONCAT(ps.nome, ' ',ps.sobrenome), ps.nome_fantasia) nome,
                ps.bairro,
                IF(dm_atividade ='o', 'Orçamento',IF(dm_atividade = 'v', 'Visita Técnica', 'Realização do Serviço')) as dm_atividade,
                a.status_orc,
                ps.id_prestador_cliente_sem_contrato AS id_usuario,
                0 tp_cli
            FROM
                tbl_pedido_de_orcamento a 
                LEFT JOIN tbl_prestador_cliente_sem_contrato ps ON ps.id_prestador_cliente_sem_contrato = a.id_cliente_prestador 
            WHERE ps.id_prestador = ". $usuario[0]->id_usuario ."
                AND a.id_usuario_sol = ". $usuario[0]->id_usuario. " ) A
                
            WHERE '$dia' BETWEEN A.data_hora_visita AND A.data_hora_termino; ";

        $vinculos = $this->superModel->query($sql);

        foreach ($vinculos as $key => $value) {
            $vinculos[$key]->colaboradores = $this->buscarColaboradorPorPedido($value->id_pedido_de_orcamento, "0");
        }

        $this->data['usuario'] = $usuario[0];
        return $vinculos;
    }

    public function agenda($dia = ''){
//date('d/m/Y', strtotime('2019-03-20'))
        $this->data['script_listar_os'] = 1;

        if(empty($dia))
            $dia = new DateTime('now');
        else 
            $dia = new DateTime($dia);
 
        $vinculos = $this->agendaByData(date('d/m/Y', strtotime($dia->format('Y-m-d'))));
        
        $diaAnterior = new DateTime($dia->format('Y-m-d'));
        $diaAnterior->add(date_interval_create_from_date_string('-1 days'));
                

        $diaPosterior = new DateTime($dia->format('Y-m-d'));
        $diaPosterior->add(new DateInterval('P1D'));
        //date_add($diaPosterior, date_interval_create_from_date_string('1 days'));

        $this->data['dia'] = $dia->format('Y-m-d');
        $this->data['diaAnterior'] = $diaAnterior->format('Y-m-d');
        $this->data['diaPosterior'] = $diaPosterior->format('Y-m-d');
        $this->data['vinculos'] = $vinculos;

        insertTag('js', 'jquery.maskedinput.min.js?v=1.0', $this->data); //16-05-2018 Adiciona JS
        insertTag('js', 'combobox.js?v=1.0', $this->data);
        insertTag('js', 'insere_mascara.js?v=1.0', $this->data);
        insertTag('js', 'custom.js?v=1.0', $this->data);
        insertTag('js', 'restrita/agenda_cliente.js', $this->data);

       
        $this->layout->view('restrita/agendaCliente', $this->data);
    }

    public function buscarColaboradorPorPedido($id_pedido_de_orcamento, $json = "1"){
        $sql = "
        SELECT c.id_pedido_colaborador,
               c.id_colaborador,
               CONCAT(l.nome, ' ', l.sobrenome) nome
          FROM tbl_pedido_colaborador c
         INNER JOIN tbl_colaborador l ON (l.id_colaborador = c.id_colaborador)
         WHERE c.id_pedido_de_orcamento = $id_pedido_de_orcamento";
        
        $retorno = $this->superModel->query($sql);

        if ($json == "1"){
            if (empty($retorno))
                echo json_encode(['code' => '999', 'msg' => 'Não foi encontrado o colaborador']);
            else
                echo json_encode($retorno);
        } else {
            return $retorno;
        }
    }

    public function emailColaborador(){
        $colaboradores = $this->input->post("id_colaborador");
        foreach ($colaboradores as $colaborador) {
            $this->impressao($this->input->post("tipo_impressao"),
                            $this->input->post("filter_date"),
                            $colaborador);    
        }
        
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function impressao($tipo_impressao, $dia, $id_colaborador = ""){
        
        $dia = date('d/m/Y', strtotime($dia));

        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata['id']
                ),
            )
        );
        $usuario = $this->superModel->select('usuario', $condicoes);

        $sql = "
        /* servicos_prestador */
        SELECT  A.* ,
                r.nome AS nome_cliente,
                r.email,
                r.id_colaborador
            FROM (
            SELECT 
                a.id_pedido_de_orcamento,
                DATE_FORMAT(a.data_hora_visita,'%d/%m/%Y') data_hora_visita,
                DATE_FORMAT(a.data_hora_termino,'%d/%m/%Y') data_hora_termino,
                DATE_FORMAT(a.data_hora_visita,'%H:%i') hora_visita,
                DATE_FORMAT(a.data_hora_termino,'%H:%i') hora_termino,
                a.nome_cliente_prestador AS nome,  
                CONCAT(e.endereco, ', ', e.numero, ' - ', e.complemento, ', ', e.bairro) bairro,  
                IF(dm_atividade ='o', 'Orçamento',IF(dm_atividade = 'v', 'Visita Técnica', 'Realização do Serviço')) as dm_atividade,
                a.status_orc,
                pc.id_cliente AS id_usuario
            FROM
                tbl_pedido_de_orcamento a  
                LEFT JOIN tbl_prestador_cliente pc ON pc.id_prestador_cliente = a.id_cliente_prestador 
                LEFT JOIN tbl_usuario u ON u.id_usuario = pc.id_cliente
                LEFT JOIN tbl_usuario_endereco e ON e.id_usuario = pc.id_cliente
            WHERE pc.id_prestador = ". $usuario[0]->id_usuario ." 
                AND a.id_usuario_sol = ". $usuario[0]->id_usuario ." 
            UNION ALL
            /* servicos_cliente */
            SELECT 
                a.id_pedido_de_orcamento,
                DATE_FORMAT(a.data_hora_visita,'%d/%m/%Y') data_hora_visita,
                DATE_FORMAT(a.data_hora_termino,'%d/%m/%Y') data_hora_termino,
                DATE_FORMAT(a.data_hora_visita,'%H:%i') hora_visita,
                DATE_FORMAT(a.data_hora_termino,'%H:%i') hora_termino,
                un.nome,
                CONCAT(e.endereco, ', ', e.numero, ' - ', e.complemento, ', ', e.bairro) bairro,
                IF(dm_atividade ='o', 'Orçamento',IF(dm_atividade = 'v', 'Visita Técnica', 'Realização do Serviço')) as dm_atividade,
                a.status_orc,
                un.id_usuario
            FROM
                tbl_pedido_de_orcamento a 
                LEFT JOIN tbl_usuario un ON  un.id_usuario = a.id_empresa
                LEFT JOIN tbl_prestador_cliente pc ON pc.id_prestador_cliente = a.id_cliente_prestador
                LEFT JOIN tbl_usuario_endereco e ON e.id_usuario = un.id_usuario
            WHERE pc.id_prestador = ". $usuario[0]->id_usuario ." 
                AND un.id_tipo_usuario <> 1 
                AND a.status <> 'Pendentes de Aprovação' 
            UNION ALL
            /* cliente sem contrato */
            SELECT 
                a.id_pedido_de_orcamento,
                DATE_FORMAT(a.data_hora_visita,'%d/%m/%Y') data_hora_visita,
                DATE_FORMAT(a.data_hora_termino,'%d/%m/%Y') data_hora_termino,
                DATE_FORMAT(a.data_hora_visita,'%H:%i') hora_visita,
                DATE_FORMAT(a.data_hora_termino,'%H:%i') hora_termino,
                IF(ps.tipo_cliente = 'PF',CONCAT(ps.nome, ' ',ps.sobrenome), ps.nome_fantasia) nome,
                CONCAT(ps.endereco, ', ', ps.num, ' - ', ps.complemento, ', ', ps.bairro) bairro,
                IF(dm_atividade ='o', 'Orçamento',IF(dm_atividade = 'v', 'Visita Técnica', 'Realização do Serviço')) as dm_atividade,
                a.status_orc,
                ps.id_prestador_cliente_sem_contrato AS id_usuario
            FROM
                tbl_pedido_de_orcamento a 
                LEFT JOIN tbl_prestador_cliente_sem_contrato ps ON ps.id_prestador_cliente_sem_contrato = a.id_cliente_prestador 
            WHERE ps.id_prestador = ". $usuario[0]->id_usuario ."
                AND a.id_usuario_sol = ". $usuario[0]->id_usuario. " ) A
            INNER JOIN tbl_pedido_colaborador c ON (c.id_pedido_de_orcamento = A.id_pedido_de_orcamento)
            INNER JOIN tbl_colaborador r ON (r.id_colaborador = c.id_colaborador)
            WHERE '$dia' BETWEEN A.data_hora_visita AND A.data_hora_termino ";
        
        if(!empty($id_colaborador) && $id_colaborador <> "undefined")
                $sql .= " AND r.id_colaborador = $id_colaborador ";    

        $vinculos = $this->superModel->query($sql);

        foreach ($vinculos as $key => $value) {
            $vinculos[$key]->empregados = $this->buscarColaboradorPorPedido($value->id_pedido_de_orcamento, "0");
        }

        if($tipo_impressao == 'imprimir'){
            $html_content = "";
            foreach ($vinculos as $key => $colaborador) { 
                $html_content .= $this->getHtmlToPDFEmail($colaborador, "pdf");
            }
            
            $nome = $usuario[0]->nome;
            $this->pdf->loadHtml($html_content);
            $this->pdf->render();
            $this->pdf->stream("$nome\_$dia.pdf", array("Attachment"=>0));
        }

        if($tipo_impressao == 'email'){
            $this->load->library('email');
            foreach ($vinculos as $colaborador) {
                $mensagem = $this->getHtmlToPDFEmail($colaborador);
                $configEmail = $this->superModel->getRow('config_email');
                $enviados[] = $this->email
                ->from($configEmail->username, 'Sossegue')
                ->to($colaborador->email)
                ->subject("Sossegue - Seus Agendamentos")
                ->message($mensagem)->send();
            }

            $allTrue = 0;
            $allFalse = 0;
            foreach($enviados as $check){
                if ($check)
                    $allTrue++;
                if(!$check)
                    $allFalse++;
            }

            if(count($enviados) == $allTrue)
                $this->session->set_flashdata('msgEmail', "Todos e-mail enviado com sucesso.");
            else if(count($enviados) == $allTrue)
                $this->session->set_flashdata('msgEmail', "Todos e-mail estão errados. Não foi possível serem enviados.");
            else
                $this->session->set_flashdata('msgEmail',"Existem e-mail que não foi possível ser transmitido.");
        }
    }

    public function listarPedidos($id) {

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
                ), array(
                    'sinal' => '!=',
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

                      
        $texto = "";

        foreach ($pedidos as $key => $pedido) {
            $texto .= "
                <table width='100%' border='1' cellspacing='0' cellpadding='0' class='mobile-button-container'>
                    <thead>
                        <tr>
                            <th>Subcategoria</th>    
                            <th>Categoria</th>
                            <th>Serviço</th>
                            <th>Qntd</th>
                            <th>Descrição</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr width='100%'>
                        <td bgcolor='#ffffff' align='center' style='padding: 2px 5px 4px 5px;' width='14%; word-wrap:break-word;' class='section-padding' >
                            {$pedido->Servico->categoria->subcategoria->descricao}
                        </td>
                        <td bgcolor='#ffffff' align='center' style='padding: 2px 5px 4px 5px;' width='15%; word-wrap:break-word;' class='section-padding' >
                           {$pedido->Servico->categoria->descricao} <br/>
                        </td>
                        <td bgcolor='#ffffff' align='center' style='padding: 2px 5px 4px 5px;' width='15%; word-wrap:break-word;' class='section-padding' >
                           {$pedido->Servico->descricao} <br/>
                        </td>
                        <td bgcolor='#ffffff' align='center' style='padding: 2px 5px 4px 5px;' width='6%; word-wrap:break-word;' class='section-padding' >
                           {$pedido->qntd} <br/>
                        </td>
                        <td bgcolor='#ffffff' align='center' style='padding: 2px 5px 4px 5px;' width='50%;  word-wrap:break-word;' class='section-padding' >
                          <p> ";
                        foreach ($pedido->filtros as $key => $filtro) 
                            $texto .= $filtro->pergunta." - ".(empty($filtro->filtro) ? $filtro->valor : $filtro->filtro) . "<br/>";
            $texto .= "  
                        </p></td>
                    </tr>
                </tbody>
            </table>";
        }

        return $texto;
    }

    public function getHtmlToPDFEmail($colaborador, $tipo = ""){
        $html_content = "
            <table width='100%' border='1' cellspacing='0' cellpadding='0' class='responsive-table'>
                <thead>
                    <tr>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td> <b>Nome Colaborador:</b>";
            foreach ($colaborador->empregados as $k => $empregado)
                $html_content .= ( $k !== count( $colaborador->empregados ) -1 ) ? "$empregado->nome e " : "$empregado->nome ";
            $html_content .= " <br/> <br/>
                            <b>Data de Inicio: </b> $colaborador->data_hora_visita <br/> <br/>
                            <b>Horário de Inicio: </b> $colaborador->hora_visita <br/> <br/>
                            <b>Data de Fim: </b> $colaborador->data_hora_termino <br/> <br/>
                            <b>Horário de Fim: </b> $colaborador->hora_termino <br/> <br/>
                            <b>Nome do Cliente: </b> $colaborador->nome <br/> <br/>
                            <b>Local: </b> $colaborador->bairro <br/> <br/>
                            <b>Atividade: </b> $colaborador->dm_atividade <br/> <br/>
                            <b>Descrições dos Serviços:</b><br/> <br/>
                        </td>
                    </tr>
                </tbody>                
                {$this->listarPedidos($colaborador->id_pedido_de_orcamento)}
                </table>
                <br/>";
            
            if ($tipo != "pdf"){
                return '<!DOCTYPE html>
                        <html lang="pt-br">
                        <head>
                            <title>Sossegue - Confirmação de cadastro</title>
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
                                    padding: 0px 0 0px 0 !important;
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
                                                        <td bgcolor="#ffffff" width="100" align="left"><a href="' . base_url() . '" target="_blank">
                                                            <img alt="Logo Sossegue" src="' . base_url("assets/img/logo_mod-a.png") . '" width="130"
                                                                style="display: block;" border="0"></a>
                                                        </td>
                                                        <td bgcolor="#ffffff" width="400" align="right" class="mobile-hide">
                                                            <table border="0" cellpadding="0" cellspacing="0">
                                                                <tr>
                                                                    <td align="right" style="padding: 0 0 5px 0; font-size: 14px; font-family: Arial,
                                                                        sans-serif; color: #666666; text-decoration: none;"><span style="color: #666666;
                                                                        text-decoration: none;"><a href="' . base_url() . '" target="_blank">SOSSEGUE</a>
                                                                        <br />Sua vida mais tranquila</span>
                                                                    </td>
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
                                                                <td align="center" style="padding: 20px 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding-copy">
                                                                    Seu Agendamento de serviços para serem prestados.
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="mobile-button-container">
                                                            <tr>
                                                                <td align="center" style="padding: 0 0 0 0;" class="padding-copy">
                                                                    '.$html_content.'
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
            }else{
                return $html_content;
            }
    }
}?>