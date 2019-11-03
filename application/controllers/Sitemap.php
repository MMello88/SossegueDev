<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Example use of the CodeIgniter Sitemap Generator Model
 * 
 * @author Gerard Nijboer <me@gerardnijboer.com>
 * @version 1.0
 * @access public
 * <!-- created with Free Online Sitemap Generator www.xml-sitemaps.com -->
 */
class Sitemap extends CI_Controller {

	public function __construct() {
		parent::__construct();
		// We load the url helper to be able to use the base_url() function
		$this->load->helper('url');
		
		$this->load->model('sitemapmodel');
		
		$this->articles = array(
			array(
				'loc' => base_url(''),
				'lastmod' => null,//date('Y-m-d', time()),
				'changefreq' => 'weekly',
				'priority' => 1
			),array(
				'loc' => base_url('cadastro'),
				'lastmod' => null,//date('Y-m-d', time()),
				'changefreq' => 'weekly',
				'priority' => '0.8'
			),
			array(
				'loc' => base_url('sobre/quem-somos'),
				'lastmod' => null,//date('Y-m-d', time()),
				'changefreq' => 'weekly',
				'priority' => '0.8'
			),
			array(
				'loc' => base_url('faq'),
				'lastmod' => null,//date('Y-m-d', time()),
				'changefreq' => 'weekly',
				'priority' => '0.8'
			),
			array(
				'loc' => base_url('sobre/termos-e-condicoes'),
				'lastmod' => null,//date('Y-m-d', time()),
				'changefreq' => 'weekly',
				'priority' => '0.8'
			),
			array(
				'loc' => base_url('blog'),
				'lastmod' => null,//date('Y-m-d', time()),
				'changefreq' => 'weekly',
				'priority' => '0.8'
			)
		);
		
		$data = array (
			'select' => '*',
			'condicoes' => array (
				array (
					'campo' => 'status',
					'valor' => 'a' 
				) 
			) 
		);
		
		$cidades = $this->superModel->select ( 'cidade', $data );
		
		foreach	($cidades as $cidade){
			// Array of some articles for demonstration purposes
			array_push($this->articles,
				array(
					'loc' => base_url('pedidos/' . $cidade->link),
					'lastmod' => null,//date('Y-m-d', time()),
					'changefreq' => 'weekly',
					'priority' => '0.8'
				),
				array(
					'loc' => base_url('Manutencao/Eletricista/' . $cidade->link),
					'lastmod' => null,//date('Y-m-d', time()),
					'changefreq' => 'weekly',
					'priority' => '0.8'
				),
				array(
					'loc' => base_url('Manutencao/Encanador/' . $cidade->link),
					'lastmod' => null,//date('Y-m-d', time()),
					'changefreq' => 'weekly',
					'priority' => '0.8'
				),
				array(
					'loc' => base_url('Manutencao/Marido-de-Aluguel/' . $cidade->link),
					'lastmod' => null,//date('Y-m-d', time()),
					'changefreq' => 'weekly',
					'priority' => '0.8'
				),
				array(
					'loc' => base_url('Manutencao/Montador-de-Moveis/' . $cidade->link),
					'lastmod' => null,//date('Y-m-d', time()),
					'changefreq' => 'weekly',
					'priority' => '0.8'
				),
				array(
					'loc' => base_url('Manutencao/Instalacao-Ar-Condicionado/' . $cidade->link),
					'lastmod' => null,//date('Y-m-d', time()),
					'changefreq' => 'weekly',
					'priority' => '0.8'
				),
				array(
					'loc' => base_url('Manutencao/Limpeza-Ar-Condicionado/' . $cidade->link),
					'lastmod' => null,//date('Y-m-d', time()),
					'changefreq' => 'weekly',
					'priority' => '0.8'
				)
			);
		}		
	}
	
	/**
	 * Generate a sitemap index file
	 * More information about sitemap indexes: http://www.sitemaps.org/protocol.html#index
	 */
	public function index() {
		$this->sitemapmodel->add(base_url('sitemap/general'), date('Y-m-d', time()));
		$data = array (
			'select' => '*',
			'condicoes' => array (
				array (
					'campo' => 'status',
					'valor' => 'a' 
				) 
			) 
		);
		
		$cidades = $this->superModel->select ( 'cidade', $data );
		
		foreach	($cidades as $cidade){		
			$this->sitemapmodel->add(base_url('sitemap/pedidos/' . $cidade->link), date('Y-m-d', time()));
			$this->sitemapmodel->add(base_url('sitemap/Manutencao/Eletricista/' . $cidade->link), date('Y-m-d', time()));
			$this->sitemapmodel->add(base_url('sitemap/Manutencao/Encanador/' . $cidade->link), date('Y-m-d', time()));
			$this->sitemapmodel->add(base_url('sitemap/Manutencao/Marido-de-Aluguel/' . $cidade->link), date('Y-m-d', time()));
			$this->sitemapmodel->add(base_url('sitemap/Manutencao/Montador-de-Moveis/' . $cidade->link), date('Y-m-d', time()));
			$this->sitemapmodel->add(base_url('sitemap/Manutencao/Instalacao-Ar-Condicionado/' . $cidade->link), date('Y-m-d', time()));
			$this->sitemapmodel->add(base_url('sitemap/Manutencao/Limpeza-Ar-Condicionado/' . $cidade->link), date('Y-m-d', time()));
		}
		$this->sitemapmodel->add(base_url('sitemap/cadastro'), date('Y-m-d', time()));
		$this->sitemapmodel->add(base_url('sitemap/sobre/quem-somos'), date('Y-m-d', time()));
		$this->sitemapmodel->add(base_url('sitemap/faq'), date('Y-m-d', time()));
		$this->sitemapmodel->add(base_url('sitemap/sobre/termos-e-condicoes'), date('Y-m-d', time()));
		$this->sitemapmodel->output('sitemapindex');
	}
	
	/**
	 * Generate a sitemap both based on static urls and an array of urls
	 */
	public function general() {
		$this->sitemapmodel->add(base_url(), NULL, 'monthly', 1);
		$this->sitemapmodel->add(base_url('contact'), NULL, 'monthly', 0.9);

		foreach ($this->articles as $article) {
			$this->sitemapmodel->add($article['loc'], $article['lastmod'], $article['changefreq'], $article['priority']);
		}
		$this->sitemapmodel->output();
	}
	
	/**
	 * Generate a sitemap only on an array of urls
	 */
	public function articles() {
		foreach ($this->articles as $article) {
			$this->sitemapmodel->add($article['loc'], $article['lastmod'], $article['changefreq'], $article['priority']);
		}
		$this->sitemapmodel->output();
	}
	
}