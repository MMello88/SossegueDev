<?php session_start(); // colocar no topo do script ?>

<title><?= $explicativo->header_title; ?> 
    <?php if (!empty($cidade)) {
        echo $cidades[0]->nome;
    } ?> | Sossegue
</title>
<?php if (!empty($cidade)) : ?>
    <meta content="<?= str_replace("%cidade%", $cidades[0]->nome, $explicativo->meta_description) ?>" name="description">
<?php else : ?>
    <meta content="<?= str_replace("%cidade%", '', $explicativo->meta_description) ?>" name="description">
<?php endif; ?>
<meta name="robots" content="index, follow">
<meta name=viewport content="width=device-width, initial-scale=1">
	<script type='application/ld+json'> 
		{
			"@context": "https://www.schema.org",
			"@type": "WebSite",
			"name": "Sossegue",
			"url": "https://www.sossegue.com.br"
		}
	</script>
 
	<section class="section_mod-d border-top">
      <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3 wow bounceInLeft txt-center" data-wow-duration="1s">
                <h1 class="ui-title-block">
					<strong>
                    <?php
                    if (!empty($cidade)) {
                        echo str_replace('%%', '' . $cidades[0]->nome, $viewsubcategoria[0]->titulo);
                    } else {
                        echo str_replace('%%', '', $viewsubcategoria[0]->titulo);
                    }
                    ?>
                    </strong>
                </h1>
                <h2><strong><?php echo $viewsubcategoria[0]->subtitulo; ?></strong></h2>
            </div>

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
 
					<div class="container col-md-4 col-md-offset-1 margin_g form_cadastro">
                        <ul class="nav nav-tabs">
							<li class="active"><a   data-toggle="tab" href="#pessoa_fisica">Para minha casa</a></li>
							<li><a   data-toggle="tab" href="#pessoa_juridica">Para minha Empresa</a></li>
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
									<input class="form-control form-b" type="mail" placeholder="* Email" name="email" required>
									</div>
									<div >
									<input class="form-control form-b" type="text"  id="celular" placeholder="* Celular" name="celular" required pattern="(\([1-9]{2}\))\s([9]{1})([0-9]{4})-([0-9]{4})|(\([1-9]{2}\))\s([1-8]{1})([0-9]{3})-([0-9]{4})$"  title="Preencha o campo * Celular com seu celular ou telefone.">
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

									<div class="checkbox-inline">                                 
									<input type="checkbox" value="" class="check-concordo" required>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span style="font-family: Helvetica, Arial, sans-serif;">* CONCORDO COM OS <a data-toggle="modal" data-target=".bs-example-modal-lg">TERMOS E CONDIÇÕES</a></span><br /><br />
									</div>


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
										<input class="form-control form-b" type="text" id="cnpj" placeholder="* CNPJ" name="cnpj" required>
									</div>
									<div >
										<input class="form-control form-b" type="text" placeholder="* Nome Fantasia" name="nome" id="nome" required>
									</div>
									<div>
										<input class="form-control form-b" type="mail" placeholder="* email" name="email"required>
									</div>
									<div>
										<input class="form-control form-b" type="text" id="telefone" placeholder="* Telefone" name="telefone" title="Preencha o campo * Telefone com telefone da sua empresa." required>
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


										<div class="form-btn-submit">
										<button class="btn btn-default btn-block btn-second btn-effect">Cadastrar</button>
									</div>
								<?php echo form_close(); ?>
							</div>
						</div>
					</div>
				</div>
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
			
			<div align="justify" class="container" style="margin-top: 50px;">
				<div class="row">
					<div class="col-md-10 col-md-offset-1">
						<div class="list-block__info" color="#999999" style="margin: 0 0 20px 0">
							<h2 align="center"><?php echo $explicativo->titulo; ?></h2>
						</div>
						<div class="list-block__info" color="#999999" style="font-size: 15px;">
							<?php echo $explicativo->texto; ?>
						</div>
					</div>
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

