<?php
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
class Home extends MY_Front {
    
    public function index($msg = '') {
        $this->data ['msg'] = $msg;
        insertTag ( 'js', 'jquery.maskedinput.min.js', $this->data );

        $data = array (
            'select' => 'foto, parceiro, link',
            'condicoes' => array (
                array (
                    'campo' => 'status',
                    'valor' => 'a' 
                ) 
            ) 
        );

        $this->data ['parceiros'] = $this->superModel->select ( 'parceiros', $data );

        $data = array (
            'select' => 'foto, nome, texto, "f" AS tipo',
            'condicoes' => array (
                array (
                    'campo' => 'status',
                    'valor' => 'a' 
                ) 
            ),
            'limit' => array (
                'qtde' => 3,
                'page' => 0 
            ) 
        );

        $feedbacks = $this->superModel->select ( 'feedback', $data );

        $data = array (
            'select' => 'foto, nome, comentario AS texto, "p" AS tipo',
            'join' => array (
                array (
                    'tabela' => 'usuario',
                    'on' => 'nota.id_usuario = usuario.id_usuario' 
                ),
                array (
                    'tabela' => 'profissional',
                    'on' => 'nota.id_profissional = profissional.id_profissional' 
                ) 
            ),
            'order' => array (
                array (
                    'campo' => 'nota',
                    'valor' => 'desc' 
                ),
                array (
                    'campo' => 'nota.id',
                    'valor' => 'random' 
                ) 
            ),
            'condicoes' => array (
                array (
                    'campo' => 'nota.status',
                    'valor' => 'a' 
                ) 
            ),
            'limit' => array (
                'qtde' => 3,
                'page' => 0 
            ) 
        );

        $notas = $this->superModel->select ( 'nota', $data );

        $this->data ['feedbacks'] = array_merge ( $feedbacks, $notas );
        $this->layout->view ( 'home', $this->data );
    }

    public function enviarContato() {
        if ($this->form_validation->run ( 'contato' )) {

                $url = 'https://www.google.com/recaptcha/api/siteverify';
                $privatekey = '6Lcd4hcUAAAAAHUQECPyBfufmQcUMmBpLeAP3UGj'; // secret key que o google disponibilizou

                $response = file_get_contents($url."?secret=".$privatekey."&response=".
                                $_POST['g-recaptcha-response']."&remoteip=".$_SERVER['REMOTE_ADDR']);

                $data = json_decode ( $response );

                $visitante = array (
                                'nome' => $this->input->post ( 'nome' ),
                                'sobrenome' => $this->input->post ( 'sobrenome' ),
                                'email' => $this->input->post ( 'email' ),
                                'telefone' => $this->input->post ( 'telefone' ),
                                'endereco' => $this->input->post ( 'endereco' ),
                                'mensagem' => $this->input->post ( 'mensagem' ),
                                'data' => now () 
                );
                if(isset($data->success) AND $data->success==true){

                        $this->superModel->insert ( 'contato', $visitante );

                        $configEmail = $this->superModel->getRow ( 'config_email' );
                        $this->load->library ( 'email' );

                        $mensagem = '<br/>' . 'Nome: ' . $visitante ['nome'] . ' ' . $visitante ['sobrenome'] . ' <br/>' . 'Email: ' . $visitante ['email'] . ' <br/>' . 'Telefone: ' . $visitante ['telefone'] . ' <br/>' . 'Endereço: ' . $visitante ['endereco'] . ' <br/>' . 'Mensagem: <br/><br/>' . nl2br ( $visitante ['mensagem'] ) . ' <br/>';

                        $emailEnviado = $this->email->from ( $visitante ['email'] )->to ( $configEmail->username )->subject ( "Formulário de Contato do Site" )->message ( $mensagem )->send ();
                        if (! $emailEnviado) {
                                $this->superModel->insert ( 'log', array (
                                                'texto' => $this->email->print_debugger (),
                                                'pagina' => $this->uri->uri_string (),
                                                'post' => $this->input->post () ? json_encode ( $this->input->post () ) : '',
                                                'data' => now (),
                                                'ip' => $this->input->ip_address () 
                                ) );
                        }
                        $this->index ( 'Formulário de contato enviado com sucesso!' );
                }
                $this->index ( 'Por favor, preencha corretamente todos os campos do formulário!' );
        } else {
                $this->index ( 'Por favor, preencha corretamente todos os campos do formulário!' );
        }
    }

    public function newsInscrever() {
        if ($this->form_validation->run ( 'newsletter' )) {
                $email = $this->input->post ( 'emailNews' );

                $consulta = $this->superModel->select ( 'newsletter', array (
                                'condicoes' => array (
                                                array (
                                                                'campo' => 'email',
                                                                'valor' => $email 
                                                ) 
                                ),
                                'row' => TRUE 
                ) );

                if (! $consulta) {
                        $newsletter = array (
                                        'email' => $email,
                                        'status' => 's' 
                        );

                        $this->superModel->insert ( 'newsletter', $newsletter );
                        $msg = 'Cadastro efetuado com sucesso!';
                } else {
                        $msg = 'E-mail já cadastrado em nossa base!';
                }

                $this->mailchimp();

                $this->index ( $msg );
        } else {
                $this->index ( 'Por favor, digite seu E-mail!' );
        }
    }

    private function mailchimp(){
        session_start();
        $fname = '';
        $lname = '';
        $email = $this->input->post('newsletter');
        if(!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL) === false){
            // MailChimp API credentials
            /*$apiKey = 'InserirMailChimpAPIKey';
            $listID = 'InserirMailChimpListID';*/
            $apiKey = 'a7862396fe2beb456157bdfbefe3e428-us16';
            $listID = '4a340b6af2';
            
            // MailChimp API URL
            $memberID = md5(strtolower($email));
            $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
            $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listID . '/members/' . $memberID;
            
            // informação do inscrito
            $json = json_encode(array(
                'email_address' => $email,
                'status'        => 'subscribed',
                'merge_fields'  => array(
                    'FNAME'     => $fname,
                    'LNAME'     => $lname
                )
            ));
            
            // enviar um HTTP POST request with curl
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            // mensagem de status baseado na resposta do código
            if ($httpCode == 200) {
                $_SESSION['msg'] = '<p style="color: #34A853">Você está inscrito no Sossegue.</p>';
            } else {
                switch ($httpCode) {
                    case 214:
                        $msg = 'Você já está inscrito.';
                        break;
                    default:
                        $msg = 'Ocorreu um problema. Tente novamente.';
                        break;
                }
                return '<p style="color: #EA4335">'.$msg.'</p>';
            }
        }else{
            return '<p style="color: #EA4335">Por favor, insira um e-mail válido.</p>';
        }
    }

}
?>