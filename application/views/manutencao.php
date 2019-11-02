<?php session_start(); // colocar no topo do script ?>

<title>Tenha 3 Orçamentos de <?= $subcategoria->subcategoria; ?> de Ribeirão Preto | Sossegue</title>
<meta content="Encontre <?= $subcategoria->titulo; ?> confiáveis e qualificados, de forma rápida e prática! Na Sossegue você receberá 3 Orçamentos dos Melhores <?= $subcategoria->titulo; ?> da sua Região." name="description">
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

    <section class="section_mod-d border-top">
      <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3 wow bounceInLeft txt-center" data-wow-duration="1s">
                <h1 class="ui-title-block"><strong><?php echo $viewsubcategoria[0]->titulo; ?></strong></h1>
                <h2><strong><?php echo $viewsubcategoria[0]->subtitulo; ?></strong></h2> 
            </div>
          <!-- end col -->
            <div class="container">
                <div class="row">
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



                    <div class="col-md-4 col-md-offset-1 margin_g form_cadastro">                        
                        <?php echo form_open('Manutencao/Orcamento', array('id' => 'formCadastro', 'class' => 'bounceInLeft form-b', 'data-wow-duration' => '2s')); ?>
                            <div class="form-request__text form-b col-sm-12">Faça seu Pedido</div>
                            <div class="col-sm-6"><h6>* campos obrigatórios</h6></div><br clear=all/>
                            <div class="col-sm-12">
                                <input class="form-control form-b" type="text" placeholder="* Nome Completo" name="nome" id="nome" required>
                            </div>
                            <div class="col-sm-12">
                                <input class="form-control form-b" type="mail" placeholder="* email" name="email" required>
                            </div>
                            <div class="col-sm-12">
                                <input class="form-control form-b" type="text" id="celular" placeholder="* Celular" name="celular" required>
                            </div>
                            
                            <input class="" type="hidden" value="<?php echo $subcategoria->id_subcategoria; ?> " id="subcategoria" name="subcategoria" required>
                            
							<?php if (empty($cidade)) { ?>
								<div class="col-sm-6">
									<select name="id_cidade" class="form-control form-b">
									<option class="option_cad"><strong>Selecione a Cidade</strong></option>
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
							
							<?php
                            /*
                            <div class="col-sm-6">
                                <select name="categoria" id="categoria" class="form-control form-b">
                                    <option value="" class="option_cad">Manutenção</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <select name="subcategoria" id="subcategoria" class="form-control form-b">
                                <?php foreach($subcategorias as $subcategoria) { ?> <!-- forecah -->
                                  <?php if ($subcategoria->link == $link_subcategoria) { ?>
                                    <option value="<?php echo $subcategoria->id_subcategoria; ?>" class="option_cad" selected><strong><?php echo $subcategoria->subcategoria; ?></strong></option>
                                  <?php } else { ?>
                                    <option value="<?php echo $subcategoria->id_subcategoria; ?>" class="option_cad"><strong><?php echo $subcategoria->subcategoria; ?></strong></option>
                                  <?php } ?>
                                <?php } ?> <!-- end forecah -->
                                </select>
                            </div>
                            <div class="col-sm-12">
                                <input class="form-control form-b" type="text" placeholder="* Descrição do Serviço" name="descricao" id="descricao" required>
                            </div> */
                            ?>
                            <div class="form-btn-submit">
                                <button class="btn btn-default btn-block btn-second btn-effect col-md-12">Fazer Pedido</button>
                            </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
                


                <div align="center" style="margin: 100px">
                    <div class="list-block__title" color="#999999"><h2><?php echo $viewsubcategoria[0]->titulo_rodape; ?></h2></div>
                    <div class="list-block__info" color="#999999"><h3><?php echo $viewsubcategoria[0]->subtitulo_rodape; ?></h3></div>
					<div class="list-block__info"><h3><?php echo $viewsubcategoria[0]->subtitulo_rodape_2; ?></h3></div></center>
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

