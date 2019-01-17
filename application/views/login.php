<title>Receba 3 Orçamentos de Manutenção em Minutos | Sossegue</title>
<meta content="Encontre profissionais confiáveis e qualificados, de forma rápida e prática!" name="description">
<meta name="robots" content="noindex, nofollow">
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

          <!-- end col -->     
          
            <div class="container">
                <div class="row">
                <div class="form-group col-sm-8">    
                    <div class="col-md-6 col-md-offset-3 wow bounceInLeft txt-center" data-wow-duration="1s">
                        <h1 class="ui-title-block">Efetue seu login</h1>
                    </div>
                    <div class="col-md-6 col-md-offset-3">
                        <div class="panel panel-login form-signin">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <a href="#" class="active" id="login-form-link">Entrar</a>
                                    </div>
                                    <div class="col-xs-6">
                                        <a href="#" id="register-form-link">Lembrar senha</a>
                                    </div>
                                </div>                                
                            </div>
                            <hr class="colorgraph">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <?php echo form_open('restrita/acesso/login', array('id' => 'login-form', 'role' => 'form', 'style' => 'display:block')); ?>
                                            <div class="form-group">
                                                <input type="email" name="email" id="email" tabindex="1" class="form-control" placeholder="E-mail" value="">
                                            </div>
                                            <div class="form-group">
                                                <input type="password" name="senha" id="senha" tabindex="2" class="form-control" placeholder="Senha">
                                            </div>                                            
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-6 col-sm-offset-3">
                                                        <input type="submit" id="login-submit" tabindex="4" class="btn btn_1 btn-block btn-effect" value="Entrar">
                                                    </div>
                                                </div>
                                            </div> 

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                    </div>
                                                </div>
                                            </div>                                           
                                        <?php echo form_close(); ?>
                                        <?php echo form_open('login/recuperarSenha', array('id' => 'register-form', 'role' => 'form', 'style' => 'display:none')); ?>
                                            <div class="form-group">
                                                <input type="email" name="email" id="email" tabindex="1" class="form-control" placeholder="E-mail" value="" required>
                                            </div>                                            
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-6 col-sm-offset-3">
                                                        <input type="submit" name="register-submit" id="register-submit" tabindex="4" class="btn btn_1 btn-block btn-effect" value="Recuperar">
                                                    </div>
                                                </div>
                                            </div>
                                        <?php echo form_close(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="titulo-tipo" align="center">
                    <div>
                        <h2 style="color: #DAA520;"> Ainda não é cadastrado? É muito fácil. </h2>
                        <br /><br />
                    <div>
                        <h2>Sou prestador de serviços</h2>
                        <h6> A Sossegue aproxima ainda mais você de seus clientes!<br />
                            Crie seu perfil e conquiste mais clientes.</h6>
                        <br />
                        <a href="<?php echo base_url('cadastro'); ?>" class="btn btn-default btn-second btn-effect btn-banner-b">Anuncie</a>
                    </div><br /><br />
                        <h2>Busco um Profissional</h2>
                        <h6>Encontre facilmente prestadores de serviços na sua cidade! <br />É fácil! É rápido!</h6>
                        <br />
                        <a href="<?php echo base_url('pedidos'); ?>" class="btn btn-default btn-second btn-effect btn-banner-b">Pedir Orçamento</a>
                    </div><br /><br />
                </div>
            </div>



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
    <div class="modal fade" id="modalLogin" tabindex="-1" role="dialog" style="margin-top: 80px">
      <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?php echo $this->session->flashdata('msg'); ?></h4>
          </div>
        </div>
      </div>
    </div>
    <?php } ?>

