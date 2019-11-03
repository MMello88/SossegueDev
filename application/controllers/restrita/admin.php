<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MY_Restrita {

    public function __construct() {
        parent::__construct();
    }


    public function listar() {
        
   $this->data['script_listar_os'] = 1;

       /*$clausulas = array(
       
       'select' =>'pedido_de_orcamento.id_pedido_de_orcamento AS id_pedido_de_orcamento, 
                        pedido_de_orcamento.nro_pedido_de_orcamento AS nro_pedido_de_orcamento, 
                        pedido_de_orcamento.nome_aprov AS nome_aprov, 
                        pedido_de_orcamento.hora_pedido_de_orcamento AS hora, 
                        pedido_de_orcamento.prioridade AS prioridade, 
                        pedido_de_orcamento.status_orc AS status_orc, 
                        usuario.nome As nome,
                        usuario.email As email,
                        cidade.nome As cidade,
                        usuario.telefone As telefone,
                        pedido_de_orcamento.data_pedido_de_orcamento AS data,',
                
                'join' => array(
                    array(
                        'tabela' => 'usuario', 
                        'option' => 'left',
                        'on'     => 'pedido_de_orcamento.id_usuario_empresa = usuario.id_usuario'
                    ),
                    array(
                        'tabela' => 'aprovador', 
                        'option' => 'left',
                        'on'     => 'aprovador.id_usuario_empresa = usuario.id_usuario'
                    ),
                    array(
                        'tabela' => 'requerente', 
                        'option' => 'left',
                        'on'     => 'requerente.id_usuario_empresa = usuario.id_usuario'
                    ),
                     array(
                        'tabela' => 'usuario_endereco', 
                        'option' => 'left',
                        'on'     => 'usuario_endereco.id_usuario = usuario.id_usuario'
                    ),
                     array(
                        'tabela' => 'cidade', 
                        'option' => 'left',
                        'on'     => 'cidade.id = usuario_endereco.id_cidade'
                    )
                ),
                    'condicao' => array(
                        array( 
                            'sinal' =>  '<>',
                            'campo' => 'status',
                        
                            'valor' => 'Removida'
                        ),

                          array(
                            'campo' => 'status',
                            'sinal' =>  '<>',
                            'valor' => 'Pendentes de Aprovação'
                        ),
                        
                         array(
                            'campo' => 'status',
                            'sinal' =>  '<>',
                            'valor' => 'Não Aprovada'
                        )

                    )

            );
      */
        

        
       $sql = "SELECT  a.id_pedido_de_orcamento, a.nome_aprov ,a.nro_pedido_de_orcamento, a.data_pedido_de_orcamento as data,a.hora_pedido_de_orcamento as hora, a.prioridade, a.status_orc,  r.nome as nome_cid1, o.nome as nome_aprov, o.email as email_aprov, o.telefone as telefone_aprov, b.nome as nome_empresa, b.email as email_empresa, b.telefone as telefone_empresa"
                 . " FROM tbl_pedido_de_orcamento a "
                 . " INNER JOIN tbl_usuario b ON (a.id_empresa = b.id_usuario) "
                 . " INNER JOIN tbl_usuario u ON (u.nome=a.nome_aprov) "    
                 . " INNER JOIN tbl_usuario o ON (o.id_usuario = u.id_usuario) "
                 . " INNER JOIN tbl_usuario_endereco c ON (c.id_usuario = b.id_usuario) "
                 . " INNER JOIN tbl_cidade r ON (c.id_cidade = r.id) "
                 . " WHERE a.status <> 'Pendentes de Aprovação' "
                 . " AND a.status <> 'Removido' "
                 . " AND a.status <> 'Não Aprovado' ";

        $p= $this->superModel->query( $sql );

        $this->data['listar'] = $p; 


       // $this->data['listar'] = $this->superModel->select('pedido_de_orcamento', $clausulas); 

        

        $this->layout->view('restrita/adminPedidos/pedidosOrcamentoAdmin', $this->data);

    }
//      public function listarPedidos($id){
//   
//
//        $this->data['script_listar_os'] = 1;
//       
//    
//            $condicao = array (
//                'condicoes' => array (
//                    array (
//                        'campo' => 'id_pedido_de_orcamento',
//                        'valor' => $id
//                    )
//                )
//            );
//
//             $os = $this->superModel->select ( 'pedido_de_orcamento', $condicao);
//
//
//        $data = array (
//            'select' => 'id_pedido, id_pedido_de_orcamento, id_servico, qntd, '
//                        .' CASE status WHEN \'a\' THEN \'Andamento\' '
//                        .' WHEN \'f\' THEN \'Finalizado\' '
//                        .' WHEN \'e\' THEN \'Excluído\' '
//                        .' ELSE \'\' END status',
//            'condicoes' => array (
//                array (
//                    'campo' => 'id_pedido_de_orcamento',
//                    'valor' => $id
//                )
//            ) 
//        );
//
//        $pedidos = $this->superModel->select ( 'pedido', $data );
//
//        foreach ($pedidos as $key => $pedido){   
//            $sql = "SELECT c.descricao AS pergunta, b.descricao AS filtro, a.valor "
//                 . " FROM tbl_pedido_filtro a "
//                 . " LEFT JOIN tbl_filtro b ON (b.id_filtro = a.id_filtro) "
//                 . " LEFT JOIN tbl_pergunta c ON (c.id_pergunta = b.id_pergunta) "
//                 . " WHERE a.id_pedido = $pedido->id_pedido";
//           
//            $filtro = $this->superModel->query( $sql );
//
//            $pedidos[$key]->filtros = $filtro;
//
//            $data = array (
//                'select' => 'id_categoria_servico, descricao',
//                'condicoes' => array (
//                    array (
//                        'campo' => 'id_servico',
//                        'valor' => $pedido->id_servico
//                    )
//                )
//            );
//
//            $servico = $this->superModel->select( 'servico', $data );
//            $servico = $servico[0];
//
//            $pedidos[$key]->Servico = $servico;
//
//            $data = array (
//                'select' => 'id_categoria_servico, id_subcategoria, descricao, imagem',
//                'condicoes' => array (
//                    array (
//                        'campo' => 'id_categoria_servico',
//                        'valor' => $servico->id_categoria_servico
//                    ) 
//                )
//            );
//
//            $categoria_servico = $this->superModel->select ( 'categoria_servico', $data );
//            $categoria_servico = $categoria_servico[0];
//
//            $pedidos[$key]->Servico->categoria = $categoria_servico;
//
//            $data = array (
//                'select' => 'subcategoria as descricao',
//                'condicoes' => array (
//                    array (
//                        'campo' => 'id_subcategoria',
//                        'valor' => $categoria_servico->id_subcategoria
//                    ) 
//                )
//            );
//
//            $subcategoria = $this->superModel->select ( 'subcategoria', $data );
//            $subcategoria = $subcategoria[0];
//
//            $pedidos[$key]->Servico->categoria->subcategoria = $subcategoria;
//        }
//        
//         
//
//        $dados = array(
//                 'pedidos' => $pedidos,
//                 'os' =>  $os[0]
//        );  
//
//
//        $jsonPedidos = json_encode($dados);
//        echo $jsonPedidos;           
//            
//    }
    
    public function remover(){
    
  if($this->input->post('id_pedido_de_orcamento')) {
   
    $this->superModel->query("DELETE FROM tbl_pedido_de_orcamento WHERE id_pedido_de_orcamento = ".$this->input->post("id_pedido_de_orcamento"));


        //$this->layout->view('restrita/adminPedidos/pedidosOrcamentoAdmin', $this->data);
            redirect('restrita/admin/listar');

    }else {

        redirect('restrita/admin/listar');
    }

   
        

    }

 private function getPedidos($id){
        
        
        $data = array (
            'select' => 'id_pedido_orcamento, id_usuario_empresa',
            'condicoes' => array (
                array (
                    'campo' => 'id_pedido_orcamento',
                    'valor' => $id
                )
            ) 
        );

         $os = $this->superModel->select('pedido_orcamento', $data);

        $data2 = array (
            'select' => 'id_pedido, id_usuario, id_servico, qntd',
            'condicoes' => array (
                array (
                    'campo' => 'id_usuario',
                    'valor' => $os[0]->id_usuario_empresa,
                ),
                array (
                    'sinal' => '=',
                    'campo' => 'status',
                    'valor' => 'a'
                )
            ) 
        );

        $pedidos = $this->superModel->select ('pedido', $data2);

        foreach ($pedidos as $key => $pedido){   
            $sql = "SELECT c.descricao AS pergunta, b.descricao AS filtro, a.valor "
                 . " FROM tbl_pedido_filtro a "
                 . " LEFT JOIN tbl_filtro b ON (b.id_filtro = a.id_filtro) "
                 . " LEFT JOIN tbl_pergunta c ON (c.id_pergunta = b.id_pergunta) "
                 . " WHERE a.id_pedido = $pedido->id_pedido";
            $filtro = $this->superModel->query( $sql );

            $pedidos[$key]->filtros = $filtro;

            $data = array (
                'select' => 'id_categoria_servico, descricao',
                'condicoes' => array (
                    array (
                        'campo' => 'id_servico',
                        'valor' => $pedido->id_servico
                    )
                )
            );

            $servico = $this->superModel->select( 'servico', $data );
            $servico = $servico[0];

            $pedidos[$key]->Servico = $servico;

            $data = array (
                'select' => 'id_categoria_servico, id_subcategoria, descricao, imagem',
                'condicoes' => array (
                    array (
                        'campo' => 'id_categoria_servico',
                        'valor' => $servico->id_categoria_servico
                    ) 
                )
            );

            $categoria_servico = $this->superModel->select ( 'categoria_servico', $data );
            $categoria_servico = $categoria_servico[0];

            $pedidos[$key]->Servico->categoria = $categoria_servico;

            $data = array (
                'select' => 'subcategoria as descricao',
                'condicoes' => array (
                    array (
                        'campo' => 'id_subcategoria',
                        'valor' => $categoria_servico->id_subcategoria
                    ) 
                )
            );


            $subcategoria = $this->superModel->select ( 'subcategoria', $data );
            $subcategoria = $subcategoria[0];

            $pedidos[$key]->Servico->categoria->subcategoria = $subcategoria;
        }



        return $pedidos;

    }







}
 