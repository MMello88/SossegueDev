<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Cadastro extends MY_Front {

	public function index() {

        $this->layout->view('restrita/cadastroUsuarios', $this->data);
	   
    }

     public function modal() {
        
        $this->layout->view('restrita/cadastroUsuariosModal', $this->data);
    }

    public function adicionar() {

        $this->session->set_userdata('modal',true);
        $this->layout->view('restrita/cadastroUsuariosModal2', $this->data);
    }
    
    public function mensagem() {
        
        $usuario= $this->input->post('email');
        $aprovador=$this->session->userdata('email');
        $aprovadorNome=$this->session->userdata('nome');
        //enviaEmail($usuario,$aprovador,$aprovadorNome) ;
        $this->session->set_userdata('mensagem','O Link foi Enviado para o Email!');
        

        $this->layout->view('restrita/cadastroUsuarios', $this->data);

    }

  public function link($tipo, $idEmpresa) {
    $this->data['idEmpresa'] =$idEmpresa;
    $this->data['tipo'] = $tipo;
 
 $sql = "SELECT a.nome"
                 . " FROM tbl_setor a "
                 . " LEFT JOIN tbl_setor_empresa b ON (b.id_setor = a.id_setor) "
                 . " WHERE b.id_empresa=".$idEmpresa;
     $setores = $this->superModel->query( $sql );
     $this->data['setores'] = $setores;

 if ($tipo=='requerente') {

    
    $this->layout->view('restrita/cadastroUsuariosRequerente', $this->data);
 
 }
   if ($tipo=='aprovador') {

    $this->layout->view('restrita/cadastroUsuariosAprovador', $this->data);
 
 }
       
    }
  
/*
  public function salvar($tipo, $idEmpresa) {
 
  if($this->form_validation->run('restrita/cadastro/cadastrar')){ //$this->form_validation->run('restrita/cadastro/cadastrar')
           
            $clausulas = array(
                'select' => 'id_usuario',
                'row' => true,
                'condicoes' => array(
                    array(
                        'campo' => 'cpf',
                        'valor' => $this->input->post('cpf')
                    ),
                    array(
                        'campo' => 'email',
                        'valor' => $this->input->post('email'),
                        'or' => TRUE
                    )
                )
            );

            if(!$this->superModel->select('usuario', $clausulas)){

              
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
                            'valor' => $tipo
                        )
                    )
                );
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

                $usuario = array(
                    'id_tipo_usuario' => $this->superModel->select('tipo_usuario', $clausulas)->id_tipo_usuario,
                    'nome' => $this->input->post('nome'),
                    'email' => $this->input->post('email'),
                    'senha' => Bcrypt::hash($this->input->post('senha')),
                    'cpf' => $this->input->post('cpf'),
                    'telefone' => $this->input->post('telefone'),
                    'status' => 'a',
                    'codigo_confirmacao' => tokenGenerate(32),
                    'confirmado_expiracao' => date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . " + $diasConfirmacao days"))
                );

                $idUsuario = $this->superModel->insert('usuario', $usuario);

                $user = array(
                   
                        'id_usuario' => $idUsuario,
                        'id_usuario_empresa' =>$idEmpresa,
                        'setor' => $this->input->post('setor'),
                 );


                if ($tipo =='requerente') {
                    
                     $idRequerente = $this->superModel->insert('requerente', $user);
 
                } 
                 
                 if ($tipo =='aprovador'){ 
                    
                    $idAprovador = $this->superModel->insert('aprovador', $user);
                 }
               

               // $usuario['link'] = base_url('restrita/cadastro/confirmaCadastro?token=' . $usuario['codigo_confirmacao'] . '&email=' . $usuario['email']);
               // $this->enviaEmail($usuario);
             

                $this->superModel->salvaLog('Login no sistema', array('email' => $usuario['email']));
                $this->db->where('id_usuario', $idUsuario);
                $this->db->update('usuario', array('ultimo_acesso' => date('Y-m-d H:i:s')));

                $data = array(
                    'logado' => TRUE,
                    'nome' => $usuario['nome'],
                    'email' => $usuario['email'],
                    'id' => $idUsuario,
                    'tipo' => $usuario['id_tipo_usuario']
                );

                $this->session->set_userdata($data);
                $this->layout->view('login', $this->data);
       
                
            } else { 
                $this->lang->load('mensagens');
                $this->session->set_flashdata('msg', $this->lang->line('usuario_ja_cadastrado'));
                redirect('restrita/cadastro/link/'.$tipo.'/'.$idEmpresa);
            }

        } else {
            redirect('restrita/cadastro/link/'.$tipo.'/'.$idEmpresa);
        }




    }*/

  public function confirmaCadastro() {
        if($this->input->get('token') && $this->input->get('email')) {
            $data = array(
                'select' => 'id_usuario',
                'row' => true,
                'condicoes' => array(
                    array(
                        'campo' => 'confirmado',
                        'valor' => 'n'
                    ),
                    array(
                        'campo' => 'email',
                        'valor' => $this->input->get('email')
                    ),
                    array(
                        'campo' => 'codigo_confirmacao',
                        'valor' => $this->input->get('token')
                    )
                )
            );

            $usuario = $this->superModel->select('usuario', $data);

            if($usuario) {
                $data = array(
                    'confirmado' => 's'
                );

                $condicoes = array(
                    array(
                        'campo' => 'id_usuario',
                        'valor' => $usuario->id_usuario
                    )
                );

                $this->superModel->update('usuario', $data, $condicoes);

                $data = array(
                    'select' => 'id_profissional',
                    'row' => true,
                    'condicoes' => array(
                        array(
                            'campo' => 'id_usuario',
                            'valor' => $usuario->id_usuario
                        )
                    )
                );

            $this->layout->view('restrita/confirmacao-cadastro', $this->data);
            
            } else {
                redirect('');
            }
        } else {
            redirect('');
        }
    }

    public function expirado() {
        if($this->session->userdata('email') && $this->session->userdata('link')) {
            $this->layout->view('cadastro-expirado', $this->data);
        } else {
            redirect('');
        }
    }

    public function reenviaLink() {
        if($this->session->userdata('email') && $this->session->userdata('link')) {
            $data['email'] = $this->session->userdata('email');
            $data['link'] = $this->session->userdata('link');
            $this->session->unset_userdata('email');
            $this->session->unset_userdata('link');

            $this->enviaEmail($data);
            $this->session->set_flashdata('msg', 'Link de confirmação reenviado para seu email!');
        }

        redirect('');
    }

}
