<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Empresariais extends MY_Restrita {

    public function index() {
        insertTag('js', 'jquery.maskedinput.min.js?v=1.0', $this->data); //16-05-2018 Adiciona JS
        insertTag('js', 'insere_mascara.js?v=1.0', $this->data); //16-05-2018 Adiciona JS
        insertTag('js', 'restrita_empresariais.js?v=1.0', $this->data); //16-05-2018 Adiciona JS

        $condicoes = array(
            'select' => "tbl_usuario_endereco.*,tbl_cidade.nome as nome_cidade, tbl_estado.id as id_estado, tbl_estado.nome as nome_estado",
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata['id']
                )
            ),
            'join' => array(
                array("tabela" => 'cidade',
                    'on' => 'cidade.id = usuario_endereco.id_cidade',
                    'option' => 'left'
                ),
                array("tabela" => 'estado',
                    'on' => 'cidade.estado = estado.id',
                    'option' => 'left'
                )
            )
        );

        $userEndereco = $this->superModel->select('usuario_endereco', $condicoes);

        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata['id']
                )
            )
        );

        $this->data['estadosTotais'] = $this->superModel->select('estado');
        $this->data['cidadeTotais'] = $this->_combo_cidade($userEndereco[0]->id_estado, $userEndereco[0]->id_cidade);
        $this->data['dados'] = $this->superModel->select('usuario', $condicoes);
        $this->data['endereco'] = $userEndereco;

        $query = "SELECT  a.nome, a.id_setor "
                . " FROM tbl_setor a "
                . " LEFT JOIN tbl_setor_empresa b ON (b.id_setor = a.id_setor) "
                . " WHERE b.id_empresa =" . $this->session->userdata('id')
                . " AND a.status != 'Removido'";
        $setoresNomes = $this->superModel->query($query);
        $this->data['setores'] = $setoresNomes;

        $this->layout->view('restrita/dadosEmpresariais', $this->data);
    }

    public function combo_cidade($id_estado, $id_cidade) {
        $combo = '<option class="option_cad">CIDADE</option>';
        $combo .= $this->_combo_cidade($id_estado, $id_cidade);
        echo $combo;
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

    public function atualizaDados() {
        
         insertTag('js', 'restrita_empresariais.js?v=1.0', $this->data); //16-05-2018 Adiciona JS
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

        $condicao = array(
            array(
                'campo' => 'id_usuario',
                'valor' => $this->session->userdata('id')
            )
        );

        $usuario = array(
            'nome' => $this->input->post('nome'),
            'razao' => $this->input->post('razao'),
            'cpf' => $this->input->post('cpf'),
            'telefone' => $this->input->post('telefone1'),
            'telefone2' => $this->input->post('telefone2'),
            'status' => 'a',
            'codigo_confirmacao' => '',
            'confirmado_expiracao' => date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . " + $diasConfirmacao days"))
        );


        $this->superModel->update('usuario', $usuario, $condicao);

        $idUsuario = $this->session->userdata('id');

        $this->db->where('id_usuario', $idUsuario);
        $this->db->update('usuario', array('ultimo_acesso' => date('Y-m-d H:i:s')));
        redirect('restrita/empresariais#dados');
    }

    public function atualizaEndereco() {

        $condicao = array(
            array(
                'campo' => 'id_usuario',
                'valor' => $this->session->userdata('id')
            )
        );

        $endereco = array(
            'id_usuario' => $this->session->userdata('id'),
            'id_cidade' => $this->input->post('cidade'),
            'bairro' => $this->input->post('bairro'),
            'endereco' => $this->input->post('endereco'),
            'numero' => $this->input->post('numero'),
            'complemento' => $this->input->post('complemento')
        );
        
        $this->data['estado'] = $this->input->post('estado');

        $this->superModel->update('usuario_endereco', $endereco, $condicao);

        $idUsuario = $this->session->userdata('id');

        $this->db->where('id_usuario', $idUsuario);
        $this->db->update('usuario', array('ultimo_acesso' => date('Y-m-d H:i:s')));

        redirect('restrita/empresariais#enderecoTag');
    }

    public function atualizaSenha() {

        $condicao = array(
            array(
                'campo' => 'id_usuario',
                'valor' => $this->session->userdata('id')
            )
        );

        $usuario = array(
            'senha' => Bcrypt::hash($this->input->post('senha')),
        );


        $this->superModel->update('usuario', $usuario, $condicao);

        $idUsuario = $this->session->userdata('id');

        $this->db->where('id_usuario', $idUsuario);
        $this->db->update('usuario', array('ultimo_acesso' => date('Y-m-d H:i:s')));

        redirect('restrita/empresariais#senha');
    }

    public function setor() {
        $setor = array(
            'nome' => $this->input->post('setor'),
            'status' => "Ativo"
        );
        $idSetor = $this->superModel->insert('setor', $setor);

        $setorEmpresa = array(
            'id_setor' => $idSetor,
            'id_empresa' => $this->session->userdata['id']
        );

        $this->superModel->insert('setor_empresa', $setorEmpresa);

        redirect('restrita/empresariais#setores'); //16-05-2018 Seleciona aba que ira aparecer
    }

    public function excluir($idSetor) {
        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata['id']
                )
            )
        );

        $user = $this->superModel->select('usuario', $condicoes);

        if ($user[0]->id_tipo_usuario == 5) {

            $idUsuario = $user[0]->id_usuario;
        } else if ($user[0]->id_tipo_usuario == 4) {

            $condicoes = array(
                'condicoes' => array(
                    array(
                        'campo' => 'id_usuario',
                        'valor' => $user[0]->id_usuario
                    )
                )
            );
            $userR = $this->superModel->select('requerente', $condicoes);

            $idUsuario = $userR[0]->id_usuario_empresa;
        } else if ($user[0]->id_tipo_usuario == 5) {


            $condicoes = array(
                'condicoes' => array(
                    array(
                        'campo' => 'id_usuario',
                        'valor' => $user[0]->id_usuario
                    )
                )
            );
            $userA = $this->superModel->select('aprovador', $condicoes);

            $idUsuario = $userA[0]->id_usuario_empresa;
        }


        $sql = "SELECT  b.id_setor_empresa"
                . " FROM tbl_setor a "
                . " LEFT JOIN tbl_setor_empresa b ON (a.id_setor = b.id_setor) "
                . " LEFT JOIN tbl_usuario c ON (c.id_usuario = b.id_empresa) "
                . " WHERE c.id_usuario =" . $idUsuario
                . " AND a.id_setor =" . $idSetor;

        $idSetorEmpresa = $this->superModel->query($sql);

        $this->superModel->query("DELETE FROM tbl_setor_empresa WHERE id_setor_empresa = " . $idSetorEmpresa[0]->id_setor_empresa);

        redirect('restrita/empresariais#setores');
    }



}

// javascript: https://pt.stackoverflow.com/questions/57904/alterar-class-do-elemento-clicado


