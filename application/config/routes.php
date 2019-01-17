<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "home";
$route['404_override'] = 'erro_404';


$route['restrita'] = 'restrita/acesso';
$route['restrita/estaticas/alterar'] = 'restrita/estaticas/alterar';
$route['restrita/estaticas/(:any)'] = 'restrita/estaticas/index/$1';

$route['seo/sitemap\.xml'] = "seo/sitemap";

$route['pedidos/(:any)'] = 'pedidos/index/$1';

$route['Manutencao'] = 'erro_404';
$route['Manutencao/getFiltrosOrcamento/(:any)'] = 'manutencao/getFiltrosOrcamento/$1';
$route['Manutencao/(:any)'] = 'manutencao/index/$1';

$route['Manutencao/Orcamento'] = 'manutencao/orcamento';
$route['Manutencao/actionPedido'] = 'manutencao/actionPedido'; 
$route['Manutencao/AdicionarCarrinho'] = 'manutencao/adicionarCarrinho';
$route['Manutencao/FinalizarPedido'] = 'manutencao/finalizarPedido';


$route['Carrinho'] = 'carrinho/index';
$route['Carrinho/Alterar'] = 'carrinho/alterar';
$route['Carrinho/Remover/(:any)'] = 'carrinho/remover/$1';
$route['Carrinho/Finalizar'] = 'carrinho/finalizar';

//funciona já

$route['restrita/lista/prestador/categorias'] = 'precificacao/categoria_prestada/listarSubcategoria';
$route['restrita/prof/categ/salvar'] = 'precificacao/categoria_prestada/salvar';
$route['restrita/prof/mensagem/aviso'] = 'precificacao/categoria_prestada/mensagemInicial';
$route['restrita/prof/mensagem/NaoFinalizado'] = 'precificacao/categoria_prestada/mensagemNaoTerminado';
$route['restrita/prof/mensagem/finalizado'] = 'precificacao/categoria_prestada/mensagemTerminado';

$route['restrita/prof/pergunta'] = 'precificacao/pergunta_prestador/pergunta';
$route['restrita/prof/inicio'] = 'precificacao/pergunta_prestador/iniciar';
$route['restrita/prof/resposta/gravar'] = 'precificacao/pergunta_prestador/gravar'; 

$route['restrita/prof/pergunta/proxima/(:any)'] = 'precificacao/pergunta_prestador/proxima/$1';
$route['restrita/prof/pergunta/anterior/(:any)'] = 'precificacao/pergunta_prestador/anterior/$1';


$route['quem-somos'] = 'sobre/index/quem-somos';
$route['termos-e-condicoes'] = 'sobre/index/termos-e-condicoes';
/* End of file routes.php */
/* Location: ./application/config/routes.php */