<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Front extends CI_Controller {
	var $data;
    var $Menus;
        
    public function __construct() {
        parent::__construct();        
        $this->Menus = new Menus();

        $this->data['MenuSubCategorias'] = $this->Menus->getMontaMenu();

        $this->layout->setLayout('frontend');

        

        $this->data['msg'] = '';

        $this->data['css'] = array(
            asset_url('css/master.css?v=3.5'),
            asset_url('plugins/switcher/css/switcher.css'),
            asset_url('plugins/jquery-ui-1.11.4/jquery-ui.min.css'),
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
            asset_url('js/custom.js?v=5.1')
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
    }

    protected function autentica() {
        return $this->session->userdata('logado') ? true : false;
    }
}
