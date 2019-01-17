<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Anunciar extends MY_Restrita {
	public function index() {
        if($this->form_validation->run('restrita/anunciar')) {
            $idUsuario = $this->session->userdata('id');

            if(!$idUsuario) {
                redirect('login');
            }

            $data = array(
                'row' => TRUE,
                'select' => 'id_tipo_usuario',
                'condicoes' => array(
                    array(
                        'campo' => 'tipo_usuario',
                        'valor' => 'Profissional'
                    )
                )
            );

            $idTipoUsuario = $this->superModel->select('tipo_usuario', $data)->id_tipo_usuario;

            $data = array(
                'id_tipo_usuario' => $idTipoUsuario
            );

            $condicoes = array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $idUsuario
                )
            );

            $this->superModel->update('usuario', $data, $condicoes);

            $url = $this->superModel->getUrlAmigavel($this->session->userdata('nome'), 'tbl_profissional', 'url');

            $profissional = array(
                'id_usuario' => $idUsuario,
                'id_subcategoria' => $this->input->post('subcategoria'),
                'url' => $url,
                'status' => 'a'
            );

            $idProfissional = $this->superModel->insert('profissional', $profissional);
            list($idPlano, $periodo) = explode('_', $this->input->post('plano'));

            $clausulas = array(
                'row' => true,
                'condicoes' => array(
                    array(
                    'campo' => 'status',
                    'valor' => 'a'
                    ),
                    array(
                        'campo' => 'id_plano',
                        'valor' => $idPlano
                    )
                )
            );

            $plano = $this->superModel->select('planos', $clausulas);
            $now = date('Y-m-d H:i:s');



             switch($periodo) {
                case 'trimestral':
                    $preco = $plano->trimestral;
                    $months = ' + 3 months';
                    $total = $preco * 3;
                    break;
                case 'semestral':
                    $preco = $plano->semestral;
                    $months = ' + 6 months';
                    $total = $preco * 6;
                    break;
                case 'anual':
                    $preco = $plano->anual;
                    $months = ' + 12 months';
                    $total = $preco * 12;
                    break;
                default:
                    $preco = $plano->preco;
                    $months = ' + 1 month';
                    $total = $preco;
                    break;               
                } 
          
            $data = array(
                'select' => 'confirmado',
                'row' => true,
                'condicoes' => array(
                    array(
                        'campo' => 'id_usuario',
                        'valor' => $idUsuario
                    )
                )
            );

            $verificaUsuario = $this->superModel->select('usuario', $data);

            $fatura = array(
                'id_profissional' => $idProfissional,
                'id_planos' => $idPlano,
                'inicio' => $now,
                'fim' => date('Y-m-d H:i:s', strtotime($now . $months)),
                'total' => $total,
                'situacao' => $total > 0 ? 'p' : 'a',
                'status' => $verificaUsuario->confirmado === 's' ? 'a' : 'i'
            );

            $idFatura = $this->superModel->insert('profissional_fatura', $fatura);

            if($fatura['total'] > 0) {
                $data = array(
                    'row' => TRUE,
                    'select' => 'nome, email, celular, cpf',
                    'condicoes' => array(
                        array(
                            'campo' => 'id_usuario',
                            'valor' => $idUsuario
                        )
                    )
                );

                $usuario = $this->superModel->select('usuario', $data);

                $produto = array(
                    'comprador' => array(
                        'nome' => $usuario->nome,
                        'email' => $usuario->email,
                        'ddd' => substr(preg_replace('/\(|\)/', '', substr($usuario->celular, 0, 3)), 0, 2),
                        'telefone' => str_replace('-', '', substr($usuario->celular, 5, 10)),
                        'cpf' => $usuario->cpf
                    ),
                    'nome' => 'Plano ' . $plano->plano . ' - ' . ucwords($periodo),
                    'qtde' => 1,
                    'idFatura' => $idFatura,
                    'total' => $fatura['total'],
                );

                $this->realizaPagamento($produto);
            } else {
                $this->session->set_flashdata('msg', $this->lang->line('cadastro_inserido'));
                redirect('restrita/home');
            }
        } else {
            $clausulas = array(
                'condicoes' => array(
                    array(
                        'campo' => 'status',
                        'valor' => 'a'
                    )
                )
            );

            $this->data['planos'] = $this->superModel->select('planos', $clausulas);

            $clausulas['order'] = array(
                array(
                    'campo' => 'categoria'
                )
            );

            $this->data['listaCategorias'] = $this->superModel->select('categoria', $clausulas);

            $clausulas = array(
                'condicoes' => array(
                    array(
                        'campo' => 'status',
                        'valor' => 'a'
                    )
                ),
                'order' => array(
                    array(
                        'campo' => 'ordem',
                        'valor' => 'ASC'
                    )
                )
            );

            $this->data['planosOpcoes'] = $this->superModel->select('planos_opcoes', $clausulas);

            foreach($this->data['planos'] as $key => $plano) {
                foreach($this->data['planosOpcoes'] as $opcao) {
                    $clausulas = array(
                        'row' => true,
                        'condicoes' => array(
                            array(
                                'campo' => 'id_planos',
                                'valor' => $plano->id_plano,
                            ),
                            array(
                                'campo' => 'id_planos_opcoes',
                                'valor' => $opcao->id_planos_opcoes,
                            )
                        )
                    );

                    $relacao = $this->superModel->select('planos_relacao', $clausulas);

                    $this->data['planos'][$key]->opcoes[$opcao->id_planos_opcoes] = $relacao ? ($relacao->info ? $relacao->info : true) : false;
                }
            }

            $this->data['titulo'] = 'Anunciar';

            $this->layout->view('restrita/anunciar', $this->data);
        }
	}

    private function realizaPagamento($produto) {
        $this->load->library('PagSeguroLibrary/PagSeguroLibrary.php');

        $paymentRequest = new PagSeguroPaymentRequest();

        $paymentRequest->setReference($produto['idFatura']);
        $paymentRequest->addItem('1', $produto['nome'], $produto['qtde'], $produto['total']);

        $paymentRequest->setSender(
            $produto['comprador']['nome'],
            $produto['comprador']['email'],
            $produto['comprador']['ddd'],
            $produto['comprador']['telefone'],
            'CPF',
            $produto['comprador']['cpf']
        );

        $paymentRequest->setCurrency("BRL");

        $paymentRequest->setRedirectUrl("http://www.sossegue.com.br/restrita/dados/anuncio");
        $paymentRequest->addParameter('notificationURL', 'http://www.sossegue.com.br/api/transacoesPagseguro');

        try {
            $credentials = PagSeguroConfig::getAccountCredentials();
            $url = $paymentRequest->register($credentials);

            $condicoes = array(
                array(
                    'campo' => 'id_profissional_fatura',
                    'valor' => $produto['idFatura']
                )
            );

            $this->superModel->update('profissional_fatura', array('link_pagamento' => $url), $condicoes);

            redirect($url);
        } catch (PagSeguroServiceException $e) {
            die($e->getMessage());
        }
    }
}
