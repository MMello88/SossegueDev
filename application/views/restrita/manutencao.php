<?php session_start(); // colocar no topo do script ?>

<title>Tenha um Orçamento de <?= $subcategoria->subcategoria; ?> <?php if (!empty($cidade)) {  echo "de " . $cidades[0]->nome; } ?> | Sossegue</title>
<meta content="Encontre <?= $subcategoria->titulo; ?> confiáveis e qualificados, de forma rápida e prática! Na Sossegue você receberá um Orçamento dos Melhores <?= $subcategoria->titulo; ?> da sua Região." name="description">
<meta name="robots" content="index, follow">
<meta name=viewport content="width=device-width, initial-scale=1">
	<script type='application/ld+json'> 
	{
  		"@context": "http://www.schema.org",
  		"@type": "WebSite",
  		"name": "Sossegue",
  		"url": "http://www.sossegue.com.br"
	}
 </script>

 <section class="section_mod-d border-top">
      <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3 wow bounceInLeft txt-center" data-wow-duration="1s">
                <h1 class="ui-title-block"><strong>
                    <?php 
                      if (!empty($cidade)){

                        echo str_replace('%%', 'DE ' . $cidades[0]->nome, $viewsubcategoria[0]->titulo); 
                      } else {
                        echo str_replace('%%', '', $viewsubcategoria[0]->titulo); 
                      }
                    ?>
                    </strong></h1>
                <h2><strong><?php echo $viewsubcategoria[0]->subtitulo; ?></strong></h2> 
            </div>
          <!-- end col -->

            <div class="container">
                <div class="row">

                <style>
                    .panel-tabs {
                        position: relative;
                        bottom: 30px;
                        clear:both;
                        border-bottom: 1px solid transparent;
                    }

                    .panel-tabs > li {
                        float: left;
                        margin-bottom: -1px;
                    }

                    .panel-tabs > li > a {
                        margin-right: 2px;
                        margin-top: 4px;
                        line-height: .85;
                        border: 1px solid transparent;
                        border-radius: 4px 4px 0 0;
                        color: #ffffff;
                    }

                    .panel-tabs > li > a:hover {
                        border-color: transparent;
                        color: #ffffff;
                        background-color: transparent;
                    }

                    .panel-tabs > li.active > a,
                    .panel-tabs > li.active > a:hover,
                    .panel-tabs > li.active > a:focus {
                        color: #fff;
                        cursor: default;
                        -webkit-border-radius: 2px;
                        -moz-border-radius: 2px;
                        border-radius: 2px;
                        background-color: rgba(255,255,255, .23);
                        border-bottom-color: transparent;
                    }
                    /*========================================  MODIFICAÇÕES TEMPORARIAS ===========================*/
                    .panel-primary > .panel-heading {
                        color: #ffffff;
                        background-color: #486d8b !important;
                        border-color: #355671 !important;
                    }
                    .panel-title{color:#fff !important;}
                    .panel-primary{border-color: #355671 !important; padding-bottom: 10px; margin-top: 40px;}
                </style>

                    
                        
                    <div class="col-md-7 wow bounceInRight animated" data-wow-duration="1s" style="visibility: visible; animation-duration: 1s;">
                        <div class="section-list-block">
                            <ul class="list-block list-unstyled">
                                                                <li class="list-block__item"> 
                                    <a class="list-block__link">                                        
                                        <img src="<?php echo $viewsubcategoria[0]->imagem_primeiro; ?> " alt="icone 1" style="float: left; padding: 0px 25px 0px 0px;">
                                        <div class="list-block__title"><font size="3"><?php echo $viewsubcategoria[0]->detalhe_primeiro; ?></font></div>
                                        <div class="list-block__info"><font size="2"><?php echo $viewsubcategoria[0]->subdetalhe_primeiro; ?></font></div>
                                    </a>
                                </li>
                                                                <li class="list-block__item"> 
                                    <a class="list-block__link">                                        
                                        <img src="<?php echo $viewsubcategoria[0]->imagem_segundo; ?> " alt="icone 1" style="float: left; padding: 0px 25px 0px 0px;">
                                        <div class="list-block__title"><font size="3"><?php echo $viewsubcategoria[0]->detalhe_segundo; ?></font></div>
                                        <div class="list-block__info"><font size="2"><?php echo $viewsubcategoria[0]->subdetalhe_segundo; ?></font></div>
                                    </a>
                                </li>
                                                                <li class="list-block__item"> 
                                    <a class="list-block__link">                                        
                                        <img src="<?php echo $viewsubcategoria[0]->imagem_terceiro; ?> " alt="icone 1" style="float: left; padding: 0px 25px 0px 0px;">
                                        <div class="list-block__title"><font size="3"><strong><?php echo $viewsubcategoria[0]->detalhe_terceiro; ?></strong>!</font></div>
                                        <div class="list-block__info"><font size="2"><?php echo $viewsubcategoria[0]->subdetalhe_terceiro; ?></font></div>
                                    </a>
                                </li>
                                <li class="list-block__item"> 
                                    <a class="list-block__link">                                        
                                        <img src="<?php echo $viewsubcategoria[0]->imagem_quarto; ?> " alt="icone 1" style="float: left; padding: 0px 25px 0px 0px;">
                                        <div class="list-block__title"><font size="3"><?php echo $viewsubcategoria[0]->detalhe_quarto; ?></font></div>
                                        <div class="list-block__info"><font size="2"><?php echo $viewsubcategoria[0]->subdetalhe_quarto; ?></font></div>
                                    </a>
                                </li>
                            </ul>                            
                        </div>
                    </div>
                   

                       <div class="container col-md-4 col-md-offset-1 margin_g form_cadastro">
                        <ul class="nav nav-tabs">
                            <li class="active"><a   data-toggle="tab" href="#pessoa_fisica">Cadastro Pessoa Fisica</a></li>
                            <li><a   data-toggle="tab" href="#pessoa_juridica">Cadastro Empresas</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="pessoa_fisica" class="tab-pane fade in active">
                            <div>
                                <?php echo form_open('manutencao/orcamento', array('id' => 'form', 'class' => 'bounceInLeft form-b', 'data-wow-duration' => '2s')); ?>
                               </br><div class="form-request__text form-b ">Faça seu Pedido</div>
                                <div class="col-sm-6"><h6>* campos obrigatórios</h6></div><br clear=all/>
                                <div>
                                <input class="form-control form-b" type="text" placeholder="* Nome Completo" name="nome" id="nome" required>
                                </div>
                            
                                <div>
                                <input class="form-control form-b" type="mail" placeholder="* email" name="email" required>
                                </div>
                                <div >
                                <input class="form-control form-b" type="text"  id="celular" placeholder="* Celular" name="celular" pattern="\([1-9]{2}\) [9]{1}[0-9]{4}-[0-9]{4}" required title="Preencha o campo * Celular como no exemplo a seguir: (xx) 9xxxx-xxxx ">
                                </div>
                            
                                <input class="" type="hidden" value="<?php echo $subcategoria->id_subcategoria; ?> " id="subcategoria" name="subcategoria" required>
                            
                                <?php if (empty($cidade)) { ?>
                                <div>
                                    <select name="id_cidade" class="form-control form-b">
                                    <option class="option_cad"><strong> SELECIONE A CIDADE</strong></option>
                                    <?php foreach($cidades as $cidade) { ?> <!-- forecah -->
                                        <option value="<?php echo $cidade->id; ?>" class="option_cad"><strong><?php echo $cidade->nome; ?></strong></option>
                                    <?php } ?> <!-- end forecah -->
                                    </select>
                                </div>
                                <?php } else { ?>
                                <?php if (!empty($cidades)) { ?> 
                                    <input type="hidden" value="<?php echo $cidades[0]->id; ?> " name="id_cidade">
                                <?php } ?>
                                <?php } ?>

                                 <div class="form-btn-submit">
                                <button class="btn btn-default btn-block btn-second btn-effect">Fazer Pedido</button>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>

                    <div id="pessoa_juridica" class="tab-pane fade">  
                                     
                      <?php echo form_open('cadastro/cadastrar', array('id' => 'formCadastro', 'class' => 'bounceInLeft form-b', 'data-wow-duration' => '2s')); ?>
                           </br><div class="form-request__text form-b">Cadastre-se agora</div>
                            <div class="col-sm-6"><h6>* campos obrigatórios</h6></div><br clear=all />
                            <div>
                                <input class="form-control form-b" type="text" id="cpf" placeholder="* CNPJ" name="cpf" required>
                            </div>
                            <div >
                                <input class="form-control form-b" type="text" placeholder="* Nome Fantasia" name="nome" id="nome" required>
                            </div>
                            <div>
                                <input class="form-control form-b" type="mail" placeholder="* email" name="email"required>
                            </div>
                            <div>
                                <input class="form-control form-b" type="text" id="telefone" placeholder="* Telefone" name="telefone" required>
                            </div>
                            <div>
                                <input class="form-control form-b" type="password" placeholder="* Senha (min seis caracteres)" name="senha" id="senha" minlength=6 required >
                            </div>
                            <div>
                                <input class="form-control form-b" type="password" placeholder="* Confirmar senha" id="confSenha" minlength=6 required>
                            </div> 
                         
                                 <?php if (!empty($cidade)) { ?>
                            <div>
                                    <select name="id_cidade" class="form-control form-b">
                                    <option class="option_cad"><strong> SELECIONE A CIDADE</strong></option>
                                    <?php foreach($cidades as $cidade) { ?> <!-- forecah -->
                                        <option value="<?php echo $cidade->id; ?>" class="option_cad"><strong><?php echo $cidade->nome; ?></strong></option>
                                     
                                    <?php } ?>
                               </select>
                                
                                </div>
                                <?php } else { ?>
                                <?php if (empty($cidades)) { ?> 
                                    <input type="hidden" value="<?php echo $cidades[0]->id; ?> " name="id_cidade">
                                <?php } ?>
                                <?php } ?>
                               
                                <div class="checkbox-inline">                                 
                                <input type="checkbox" value="" class="check-concordo" required>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span style="font-family: Helvetica, Arial, sans-serif;">* CONCORDO COM OS <a data-toggle="modal" data-target=".bs-example-modal-lg">TERMOS E CONDIÇÕES</a></span><br /><br />
                                </div>

                            <?php if($termos) { ?>
                            <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                                <div class="modal-dialog modal-lg modal-termos">
                                    <div class="modal-content txt-cinza">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        <div class="modal-body"><?php echo $termos->texto; ?></div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="form-btn-submit">
                                <button class="btn btn-default btn-block btn-second btn-effect">Cadastrar</button>
                            </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
    </div>
</div>
                <div p align="center">
                    <div class="list-block__title" color="#999999"><h2><?php echo $viewsubcategoria[0]->titulo_rodape; ?></h2></div>
                    <div class="list-block__info" color="#999999"><h3><?php echo $viewsubcategoria[0]->subtitulo_rodape; ?></h3></div>
					<div class="list-block__info"><h3><?php echo $viewsubcategoria[0]->subtitulo_rodape_2; ?></h3></div></center>
               

                <div class="row margin-g">
                    <div class="section-default  border-bottom_thin wow zoomIn" data-wow-duration="1s">
                        <div class="container">
                            <div class="row">
                                <div class="col-xs-12 txt-center">
                                    <h2 class="ui-title-block">Por que anunciar na sossegue</h2>
                                    <div class="decor-1 decor-1_mod-a decor-1_mod-d"></div>
                                </div>

                                <div class="col-xs-12">
                                    <ul class="advantages advantages_mod-a list-unstyled">
                                        <li class="advantages__item clearfix">
                                            <i class="icon flaticon-rocketship4"></i>
                                            <div class="advantages__inner">
                                                <div class="advantages__name">CONSIGA AINDA MAIS CLIENTES COM A SOSSEGUE</div>
                                                <div class="advantages__text">Experimente a plataforma, <a href="<?php echo base_url('cadastro')?>">cadastre-se</a> e Sossegue.</div>
                                            </div>
                                        </li>
                                        <li class="advantages__item clearfix">
                                            <i class="icon flaticon-badges3"></i>
                                            <div class="advantages__inner">
                                                <div class="advantages__name">CADASTRO GRATUITO</div>
                                                <div class="advantages__text">Você aumenta sua renda e não paga nada para fazer parte da nossa equipe de Sossegados!</div>
                                            </div>
                                        </li>
                                        <li class="advantages__item clearfix">
                                            <i class="icon flaticon-house158"></i>
                                            <div class="advantages__inner">
                                                <div class="advantages__name">AUMENTE SUA EXPOSIÇÃO NA INTERNET</div>
                                                <div class="advantages__text">Nosso site está entre os primeiros nas buscas do Google! Tenha ainda mais visibilidade na internet!</div>
                                            </div>
                                        </li>
                                    </ul>
                                </div><!-- end col -->
                            </div><!-- end row -->
                        </div><!-- end container -->
                    </div><!-- end section-default -->

                </div>
            </div>

        </div>
      </div>
    </section>

    <?php if($this->session->flashdata('msg')) { ?>
    <div class="modal fade" id="modalCadastro" tabindex="-1" role="dialog" style="margin-top: 80px">
      <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?php echo $this->session->flashdata('msg'); ?></h4>
          </div>
          <div class="modal-body">
            <button type="button" class="btn btn-info" data-dismiss="modal">Cancelar</button>
            <a href="<?php echo base_url('login'); ?>" type="button" class="btn btn-primary">Login</a>
          </div>
        </div>
      </div>
    </div>
    <?php } ?>
	<?php include_once("analyticstracking.php") ?>

