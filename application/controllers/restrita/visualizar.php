<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Visualizar extends MY_Restrita {

    public function index() {
        insertTag('js', 'jquery.maskedinput.min.js?v=1.0', $this->data); //16-05-2018 Adiciona JS
        insertTag('js', 'insere_mascara.js?v=1.0', $this->data); //16-05-2018 Adiciona JS
        insertTag('js', 'restrita_cadastrais.js?v=1.0', $this->data); //16-05-2018 Adiciona JS
        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata('id')
                )
            )
        );

        $idUsuario = $this->superModel->select('usuario', $condicoes);

        $this->data['dados'] = $idUsuario;

        if ($idUsuario[0]->id_tipo_usuario == 6) {

            $clausulasA = array(
                'condicoes' => array(
                    array(
                        'campo' => 'id_usuario',
                        'valor' => $idUsuario[0]->id_usuario
                    )
                )
            );

            $idAprovador = $this->superModel->select('aprovador', $clausulasA);

            $sql = "SELECT a.nome"
                    . " FROM tbl_setor a "
                    . " LEFT JOIN tbl_setor_empresa b ON (b.id_setor = a.id_setor) "
                    . " WHERE b.id_empresa=" . $idAprovador[0]->id_usuario_empresa;

            $setores = $this->superModel->query($sql);
            $this->data['setores'] = $setores;


            $this->data['user'] = $this->superModel->select('aprovador', $condicoes);
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

            $sql = "SELECT a.nome"
                    . " FROM tbl_setor a "
                    . " LEFT JOIN tbl_setor_empresa b ON (b.id_setor = a.id_setor) "
                    . " WHERE b.id_empresa=" . $idRequerente[0]->id_usuario_empresa;
            $setores = $this->superModel->query($sql);
            $this->data['setores'] = $setores;


            $this->data['user'] = $this->superModel->select('requerente', $condicoes);
        }

        $this->layout->view('restrita/visualizarCadastro', $this->data);
    }

    public function salvarDados() {
        $this->session->unset_userdata('form_validation');

        $condicoes = array(
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata('id')
                )
            )
        );

        $idUsuario = $this->superModel->select('usuario', $condicoes);
        if ($this->form_validation->run('restrita/visualizar/salvarDados' . $idUsuario[0]->id_tipo_usuario)) {

            $condicao = array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $idUsuario[0]->id_usuario
                ),
            );

            $usuario = array(
                #'cpf' => $this->input->post('cpf'),
                'nome' => $this->input->post('nome'),
                'email' => $this->input->post('email'),
                'telefone' => $this->input->post('telefone')
            );


            $this->superModel->update('usuario', $usuario, $condicao);

            if ($idUsuario[0]->id_tipo_usuario == 4) {
                $usuarioReq = array(
                    'setor' => $this->input->post('setor')
                );

                $this->superModel->update('requerente', $usuarioReq, $condicao);
            } else if ($idUsuario[0]->id_tipo_usuario == 6) {
                $usuarioAprov = array(
                    'setor' => $this->input->post('setor')
                );

                $this->superModel->update('aprovador', $usuarioAprov, $condicao);
            }
        } else {
            $this->session->set_userdata('form_validation', validation_errors());
        }

         redirect('restrita/visualizar#dados');
    }

    public function salvarSenha() {
        if ($this->form_validation->run('restrita/visualizar/salvarSenha')) {

            $condicoes = array(
                'condicoes' => array(
                    array(
                        'campo' => 'id_usuario',
                        'valor' => $this->session->userdata('id')
                    )
                )
            );

            $idUsuario = $this->superModel->select('usuario', $condicoes);


            if ($idUsuario[0]->id_tipo_usuario == 4) {


                $usuarioReq = array(
                    'setor' => $this->input->post('setor')
                );

                $this->superModel->update('requerente', $usuarioReq, $condicao);
            } else if ($idUsuario[0]->id_tipo_usuario == 6) {
                $usuarioAprov = array(
                    'setor' => $this->input->post('setor')
                );

                $this->superModel->update('aprovador', $usuarioAprov, $condicao);
            }

            $condicoes = array(
                'condicoes' => array(
                    array(
                        'campo' => 'id_usuario',
                        'valor' => $this->session->userdata('id')
                    )
                )
            );
            $idUsuario = $this->superModel->select('usuario', $condicoes);

            if ($idUsuario[0]->id_tipo_usuario == 6) {

                $clausulasA = array(
                    'condicoes' => array(
                        array(
                            'campo' => 'id_usuario',
                            'valor' => $idUsuario[0]->id_usuario
                        )
                    )
                );

                $idAprovador = $this->superModel->select('aprovador', $clausulasA);

                $sql = "SELECT a.nome"
                        . " FROM tbl_setor a "
                        . " LEFT JOIN tbl_setor_empresa b ON (b.id_setor = a.id_setor) "
                        . " WHERE b.id_empresa=" . $idAprovador[0]->id_usuario_empresa;

                $setores = $this->superModel->query($sql);
                $this->data['setores'] = $setores;


                $this->data['user'] = $this->superModel->select('aprovador', $condicoes);
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

                $sql = "SELECT a.nome"
                        . " FROM tbl_setor a "
                        . " LEFT JOIN tbl_setor_empresa b ON (b.id_setor = a.id_setor) "
                        . " WHERE b.id_empresa=" . $idRequerente[0]->id_usuario_empresa;
                $setores = $this->superModel->query($sql);
                $this->data['setores'] = $setores;


                $this->data['user'] = $this->superModel->select('requerente', $condicoes);
            }

            $this->layout->view('restrita/visualizarCadastro', $this->data);
        }
        redirect('restrita/visualizar#dados');
    }

}
