<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class RestritaModel extends CI_Model {
    public function  __construct() {
        parent::__construct();
    }

    public function validaPermissao() {
        $data = array(
            'select' => 'id_tipo_usuario',
            'row' => TRUE,
            'condicoes' => array(
                array(
                    'campo' => 'id_usuario',
                    'valor' => $this->session->userdata('id')
                )
            )
        );

        $consulta = $this->superModel->select('usuario', $data);

        if(!$consulta) {
            redirect('login');
        }

        $idTipoUsuario = $consulta->id_tipo_usuario;
        $url = $this->uri->segment(2);

        $clausulas = array(
            'select' => 'menu.id_menu',
            'condicoes' => array(
                array(
                    'campo' => 'status',
                    'valor' => 'a'
                ),
                array(
                    'campo' => 'id_tipo_usuario',
                    'valor' => $idTipoUsuario
                ),
                array(
                    'campo' => 'url',
                    'valor' => $url,
                    'like' => 'after'
                )
            ),
            'join' => array(
                array(
                    'tabela' => 'menu',
                    'on' => 'menu.id_menu = menu_usuario.id_menu'
                )
            )
        );

        return $this->superModel->select('menu_usuario', $clausulas);
    }

    public function login() {
        if(!$this->form_validation->run('restrita/login')) {
            $this->falhaLogin();
            redirect('login');
        } else {
            $email = $this->input->post('email', TRUE);

            $this->db->where('email', $email);
            $this->db->where('status', 'a');
            $usuario = $this->db->get('usuario')->row();

            if(!$usuario) {
                $this->falhaLogin();
            }

            if(Bcrypt::check($this->input->post('senha', TRUE), $usuario->senha)) {
                $this->superModel->salvaLog('Login no sistema', array('email' => $usuario->email));

                $this->db->where('id_usuario', $usuario->id_usuario);
                $this->db->update('usuario', array('ultimo_acesso' => date('Y-m-d H:i:s')));

                $this->db->where('id_tipo_usuario', $usuario->id_tipo_usuario);
                $tipo = $this->db->get('tipo_usuario')->row()->tipo_usuario;

                if($tipo !== 'Admin' && $usuario->confirmado === 'n' && (strtotime(date('Y-m-d H:i:s')) > strtotime($usuario->confirmado_expiracao))) {
                    $data = array(
                        'email' => $usuario->email,
                        'link' => base_url('cadastro/confirmaCadastro?token=' . $usuario->codigo_confirmacao . '&email=' . $usuario->email)
                    );

                    $this->session->set_userdata($data);
                    redirect('cadastro/expirado');
                }

                if($tipo === 'Profissional') {
                    $data = array(
                        'select' => 'planos.plano',
                        'row' => true,
                        'condicoes' => array(
                            array(
                                'campo' => 'profissional.id_usuario',
                                'valor' => $usuario->id_usuario
                            ),
                            array(
                                'campo' => 'profissional_fatura.status',
                                'sinal' => '<>',
                                'valor' => 'd'
                            )
                        ),
                        'join' => array(
                            array(
                                'tabela' => 'usuario',
                                'on' => 'usuario.id_usuario = profissional.id_usuario'
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

                    $plano = $this->superModel->select('profissional', $data);

                    if($plano) {
                        $plano = $plano->plano;
                    }
                }

                $data = array(
                    'logado' => TRUE,
                    'nome' => $usuario->nome,
                    'email' => $usuario->email,
                    'id' => $usuario->id_usuario,
                    'tipo' => $tipo
                );

                if(isset($plano)) {
                    $data['plano'] = $plano;
                }

                $this->db->select('cidade.nome AS cidade');
                $this->db->join('cidade', 'cidade.id = usuario_endereco.id_cidade');
                $this->db->where('id_usuario', $usuario->id_usuario);
                $endereco = $this->db->get('usuario_endereco')->row();

                if($endereco) {
                    $data['cidade'] = $endereco->cidade;
                }

                $this->session->set_userdata($data);

                $url = 'restrita/home';

                if($this->session->userdata('lastUrl')) {
                    $url = $this->session->userdata('lastUrl');
                    $this->session->unset_userdata('lastUrl');
                }

                redirect($url);
            }

            $this->falhaLogin();
        }
    }

    private function falhaLogin() {
        $this->superModel->salvaLog('Erro de login');
        $this->session->set_flashdata('msg', 'Dados inválidos!');

        redirect('login');
    }

    public function logoff() {
        $this->superModel->salvaLog('logoff');
        $this->session->sess_destroy();
        redirect('');
    }
}

?>
