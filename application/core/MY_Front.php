<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Front extends CI_Controller {
	var $data;
    var $Menus;
       
    public function __construct() {
        parent::__construct();        
        //echo "FRONT".__LINE__."<br>";
        $this->Menus = new Menus();

        if (isset($this->data['area_do_pedido_restrito']) && $this->data['area_do_pedido_restrito'] = 1)
            if ($this->session->userdata('idPrestadorCliente'))
                $this->data['MenuSubCategorias'] = $this->Menus->getMontaMenu($this->session->userdata('idPrestadorCliente'), true); //monta o menu
            else
            $this->data['MenuSubCategorias'] = $this->Menus->getMontaMenu(false, false); //monta o menu
        else {
            if ($this->session->userdata('logado')){
                $this->data['MenuSubCategorias'] = $this->Menus->getMontaMenu(false, true);
            } else {
                $this->data['MenuSubCategorias'] = $this->Menus->getMontaMenu(false, false);
            }
        }
        
        $this->layout->setLayout('frontend');        

        $this->data['msg'] = '';

        $this->data['css'] = array(
            asset_url('css/master.css?v=3.5'),
            asset_url('plugins/switcher/css/switcher.css'),
            asset_url('plugins/jquery-ui-1.11.4/jquery-ui.min.css'),
            //asset_url('plugins/select2-4.0.6-rc/css/select2.min.css'),
            //asset_url('plugins/select2-4.0.6-rc/css/select2.css')
        );

        $this->data['js'] = array(
            asset_url('plugins/jquery/jquery-1.11.3.min.js'),
            asset_url('plugins/jquery-ui-1.11.4/jquery-ui.min.js'),
            asset_url('plugins/slider-pro/dist/js/jquery.sliderPro.js'),
            asset_url('js/jquery-migrate-1.2.1.js'),
            asset_url('plugins/bootstrap/js/bootstrap.min.js'),
            asset_url('plugins/letters/jquery.shuffleLetters.js'),
            asset_url('plugins/letters/jquery.tickertype.js'),
            asset_url('plugins/scrollreveal/scrollreveal.min.js'),
            asset_url('js/modernizr.custom.js'),
            asset_url('plugins/owl-carousel/owl.carousel.min.js'),
            asset_url('plugins/isotope/jquery.isotope.min.js'),
            asset_url('js/waypoints.min.js'),
            asset_url('plugins/prettyphoto/js/jquery.prettyPhoto.js'),
            asset_url('js/cssua.min.js'),
            asset_url('js/wow.min.js'),
            asset_url('js/custom.js?v=5.1'),
            //asset_url('js/vazio.js'),
            //asset_url('plugins/select2-4.0.6-rc/js/select2.min.js') ,
            //asset_url('plugins/select2-4.0.6-rc/js/select2.js') 
          

            );

        $data = array(
            'select' => 'campo, valor, complemento',
            'condicoes' => array(
                array(
                    'campo' => 'nome',
                    'valor' => 'Site'
                ),
                array(
                    'campo' => 'nome',
                    'valor' => 'Textos',
                    'or' => TRUE
                )
            ),
            'join' => array(
                array(
                    'tabela' => 'configuracoes',
                    'on' => 'configuracoes.id = id_configuracoes'
                ),
                array(
                    'tabela' => 'configuracoes_categoria',
                    'on' => 'configuracoes_categoria.id_configuracoes_categoria = configuracoes_relacao.id_configuracoes_categoria'
                )
            )
        );

        $this->data['config'] = array();
        $configuracoes = $this->superModel->select('configuracoes_relacao', $data);

        foreach($configuracoes as $configuracao) {
            $this->data['config'][$configuracao->campo] = $configuracao->valor;
            $this->data['config'][$configuracao->campo . '_complemento'] = $configuracao->complemento;
        }
        if($this->session->userdata('contato_cidade') !== null)
            $this->data['cidades'] = $this->getCidades($this->session->userdata('contato_cidade'));
    }

    protected function autentica() {
        return $this->session->userdata('logado') ? true : false;
    }

    protected function getCidades($cidade){
        if (empty($cidade)){
            return $this->getAllCidades();
        } else {
            $data = array (
                'select' => '*',
                'condicoes' => array (
                    array (
                        'campo' => 'status',
                        'valor' => 'a' 
                    ),
                    array (
                        'campo' => 'link',
                        'valor' => $cidade
                    )
                ) 
            );
            $cidades = $this->superModel->select ('cidade', $data);
            if (!empty($cidades)){
                $this->data['cidade'] = $cidade;
                foreach ($cidades as $key => $value) {
                    $cidades[$key]->contatos = $this->getContatoSossegue($value->id);
                }
                return $cidades;
            }
            else
                return $this->getAllCidades();
        }
    }

    protected function getContatoSossegue($id_cidade){
        $data = array (
            'select' => '*',
            'condicoes' => array (
                array (
                    'campo' => 'id_cidade',
                    'valor' => $id_cidade
                )
            ) 
        );
        $contatos = $this->superModel->select ('contato_sossegue_cidade', $data);
        return $contatos;
    }

    protected function getAllCidades(){
        $data = array (
            'select' => '*',
            'condicoes' => array (
                array (
                    'campo' => 'status',
                    'valor' => 'a' 
                ) 
            ) 
        );

        return $this->superModel->select ( 'cidade', $data );
    }

    protected function getCidadeById($id){
        $data = array (
            'select' => '*',
            'condicoes' => array (
                array (
                    'campo' => 'status',
                    'valor' => 'a' 
                ),
                array (
                    'campo' => 'id',
                    'valor' => $id
                )
            ) 
        );
        
        $cidades = $this->superModel->select ('cidade', $data);
        if (!empty($cidades))
            return $cidades[0];
        return null;
    }
}
?>