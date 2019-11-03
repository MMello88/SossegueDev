<title>Tenha um Orçamento de Profissionais de Ribeirão Preto | Sossegue</title>
<meta content="Encontre profissionais confiáveis e qualificados, de forma rápida e prática!" name="description">
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
                <h1 class="ui-title-block"><strong>RECEBA UM ORÇAMENTO</strong> DE PROFISSIONAIS DE <strong>RIBEIRÃO PRETO</strong></h1>
                <h2>NÃO SE PREOCUPE! SERÃO OS <strong>MELHORES PROFISSIONAIS</strong> COM OS <strong>MENORES PREÇOS</strong>!</h2>
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

                    
                        
                    <div class="col-md-7 wow bounceInRight" data-wow-duration="1s">
                        <div class="section-list-block">
                            <ul class="list-block list-unstyled">
                                <?php foreach($textoBlocos as $key => $texto) { ?>
                                <li class="list-block__item"> 
                                    <a class="list-block__link">                                        
                                        <img src="<?php echo base_url('assets/img/icon_cad_' . ($key + 1) . '.png'); ?> " alt="icone 1" style="float: left; padding: 0px 25px 0px 0px;">
                                        <div class="list-block__title"><?php echo $texto->complemento; ?></div>
                                        <div class="list-block__info"><?php echo $texto->valor; ?></div>
                                    </a>
                                </li>
                                <?php } ?>
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

                    <div class="form_cadastro">                        
                        <?php echo form_open('pedidos/index', array('id' => 'formCadastro', 'class' => 'bounceInLeft form-b', 'data-wow-duration' => '2s')); ?>
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
                                                                <div>
                                        <select name="subcategoria" id="subcategoria" class="form-control form-b" required>
                                            <option value="" class="option_cad">* CATEGORIA</option>
                                            <?php foreach ($listaSubCategorias as $subCategoria) { ?>
                                                <option value="<?php echo $subCategoria->id; ?>" class="option_cad"><?php echo $subCategoria->nome; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

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
<div class="checkbox-inline">                                 
                                <input type="checkbox" value="" class="check-concordo" required>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span style="font-family: Helvetica, Arial, sans-serif;">* CONCORDO COM OS <a data-toggle="modal" data-target=".bs-example-modal-lg">TERMOS E CONDIÇÕES</a></span><br /><br />
                                </div>
                            <?php /*
							<div class="col-sm-12">
                                <input class="form-control form-b" type="text" placeholder="* Descrição do Serviço" name="descricao" id="descricao" required>
                            </div>
                             */?>
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
</div><br /><br /><br />
                
                <div align="center" style="margin: 100px">
                    <div class="list-block__title" color="#999999"><h2>Por que contratar <u><strong>Profissionais</strong></u> pela Sossegue?</h2></div>
                    <div class="list-block__info" color="#999999"><h3>A Sossegue irá enviar <em><strong>Profissionais</strong></em> altamente qualificados e que passaram por um rigoroso controle de qualidade. Preencha o formulário acima e receba seu orçamento.</h3></div>
					<div class="list-block__info"><h3>Agendaremos o mais rápido possível seu serviço na data e horário escolhido. Não deixe essa oportunidade passar! Garanta um orçamento que caiba no seu bolso!</h3></div></center>
                </div>
            </div>

        </div>
      </div>
    </section>

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

