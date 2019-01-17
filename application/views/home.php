    <?php include_once('includes/banner.php'); ?>
    <title>Receba 3 Orçamentos de Manutenção em Minutos | Sossegue</title>
	<meta content="Encontre profissionais confiáveis e qualificados, de forma rápida e prática!" name="description">
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

    <section class="section_mod-a section_mod-d border-top">
      <div class="container">
        <div class="row">
          <div class="col-md-6 col-md-offset-3 wow bounceInLeft txt-center" data-wow-duration="1s">
            <h1 class="ui-title-block">Como Funciona</h1>
            <h5>Nós te ajudamos a contratar o melhor<br /> profissional para o serviço que você precisa.</h5>
          </div>
          <!-- end col -->
          <div class="col-md-8 col-md-offset-2 wow bounceInRight" data-wow-duration="1s">
            <div class="section-list-block">
              <ul class="list-block list-unstyled">
                <li class="list-block__item"> 
                  <a class="list-block__link">
                    <div class="list-block__title txt-center"><h3>Selecione a categoria de serviço que você precisa</h3></div>
                  </a>
                </li>
                <div class="col-md-12 txt-center seta-como-funciona"><i class="fa fa-chevron-down"></i></div>
                <li class="list-block__item"> 
                  <a class="list-block__link">
                    <div class="list-block__title txt-center"><h3>Preencha nosso formulário</h3></div>
                  </a>
                </li>
                <div class="col-md-12 txt-center seta-como-funciona"><i class="fa fa-chevron-down"></i></div>
                <li class="list-block__item"> 
                  <a class="list-block__link">
                    <div class="list-block__title txt-center"><h3>Selecione os serviços desejados</h3></div>
                  </a>
                </li>
                <div class="col-md-12 txt-center seta-como-funciona"><i class="fa fa-chevron-down"></i></div>
                <li class="list-block__item"> 
                  <a class="list-block__link">
                    <div class="list-block__title txt-center"><h3>Entraremos em contato em instantes, fornecendo 3 orçamentos</h3></div>
                  </a>
                </li>
                <div class="col-md-12 txt-center seta-como-funciona"><i class="fa fa-chevron-down"></i></div>
                <li class="list-block__item"> 
                  <a class="list-block__link">
                    <div class="list-block__title txt-center"><h3>Avalie como foi sua experiência</h3></div>
                  </a>
                </li>
              </ul>              
            </div>
          </div>
        </div>
        <!-- end row --> 
      </div>
      <!-- end container --> 
    </section>
    <!-- end section-default -->
    
    <div class="section-banner-1 wow bounceInUp" data-wow-duration="1s">
      <div class="container">
        <div class="row">
          <div class="col-xs-12">
            <div class="section__inner">
              <section class="block-banner-1 clearfix">
                <div class="block-banner-1__inner">
                  <h2 class="block-banner-1__title">Quer encontrar mais clientes? Cadastre-se!</h2>
                </div>
                <a class="btn btn-default btn-second btn-effect" href="<?php echo base_url('cadastro'); ?>">Cadastrar</a> </section>
            </div>
          </div>
          <!-- end col --> 
        </div>
        <!-- end row --> 
      </div>
      <!-- end container --> 
    </div>
    <!-- end section-banner-1 -->
    
    
     
    <div class="section_mod-d border-top wow zoomIn" data-wow-duration="1s">
      <div class="container">
        <div class="row">
          <div class="col-xs-12 txt-center">
            <h2 class="ui-title-block">Contato</h2>
            <div class="decor-1 decor-1_mod-a decor-1_mod-d"></div>
          </div>
          <!-- end col --> 
        </div>
        <!-- end row --> 
      </div>
      <!-- end container --> 
    </div>
    <!-- end section_mod-d -->
    
    <div class="section_mod-b wow zoomIn" data-wow-duration="1s">
          <div class="container">
            <div class="row">
              <div class="col-md-8">
                <?php echo form_open('home/enviarContato', array('class' => 'form-request section-bg_mod-c wow bounceInLeft', 'data-wow-duration' => "2s", 'id' => 'formContato'));?>
                  <div class="row">
                    <div class="col-md-8">
                      <div class="form-request__text">Entre em contato com nossa equipe da Sossegue.</div>
                      <div class="row">
                        <div class="col-sm-6">
                          <input class="form-control" type="text" name="nome" placeholder="Nome" value="<?php echo set_value('nome') ?>" required>
                            <?php if(form_error('nome')) { ?>
                            <?php echo form_error('nome', '<span class="text-danger">', '</span>') ?>
                            <?php } ?>
                        </div><!-- end col -->
                        <div class="col-sm-6">
                          <input class="form-control" type="text" name="sobrenome" placeholder="Sobrenome" value="<?php echo set_value('sobrenome') ?>" required>
                            <?php if(form_error('sobrenome')) { ?>
                            <?php echo form_error('sobrenome', '<span class="text-danger">', '</span>') ?>
                            <?php } ?>
                        </div><!-- end col -->
                      </div><!-- end row -->
                      <div class="row">
                        <div class="col-sm-6">
                          <input class="form-control" type="email" name="email" placeholder="Email" value="<?php echo set_value('email') ?>" required>
                            <?php if(form_error('email')) { ?>
                            <?php echo form_error('email', '<span class="text-danger">', '</span>') ?>
                            <?php } ?>
                        </div><!-- end col -->
                        <div class="col-sm-6">
                          <input class="form-control" type="text" name="telefone" id="telefone" placeholder="Telefone" value="<?php echo set_value('telefone') ?>">
                            <?php if(form_error('telefone')) { ?>
                            <?php echo form_error('telefone', '<span class="text-danger">', '</span>') ?>
                            <?php } ?>
                        </div><!-- end col -->
                      </div><!-- end row -->
                      <div class="row">
                        <div class="col-xs-12">
                          <input class="form-control" type="text" name="endereco" placeholder="Endereço" value="<?php echo set_value('endereco') ?>" required>
                            <?php if(form_error('endereco')) { ?>
                            <?php echo form_error('endereco', '<span class="text-danger">', '</span>') ?>
                            <?php } ?>
                        </div><!-- end col -->
                      </div><!-- end row -->
                      <div class="row">
                        <div class="col-xs-12">
                          <textarea class="form-control" name="mensagem" placeholder="Mensagem" required rows="9"><?php echo set_value('mensagem') ?></textarea>
                            <?php if(form_error('mensagem')) { ?>
                            <?php echo form_error('mensagem', '<span class="text-danger">', '</span>') ?>
                            <?php } ?>
                        </div>
                      </div><!-- end row -->
					  <div class="row">
						<div class="g-recaptcha" data-sitekey="6Lcd4hcUAAAAALS5TcZ88z9ltUBAC6OoFj8XSVoy"></div>
					  </div>
                     <button class="btn btn-default btn-second btn-effect" name="SendButton">Enviar</button>
                    </div><!-- end col -->
                  </div><!-- end row -->
                <?php echo form_close();?><!-- end form-request -->
              </div><!-- end col -->
    		<script src='https://www.google.com/recaptcha/api.js?hl=pt-BR'></script>
              <?php if($feedbacks) { ?>
              <div class="col-md-4">
                <section class="section_mod-c wow bounceInRight" data-wow-duration="1s">
                  <div class="section__inner">
                    <h2 class="ui-title-block ui-title-block_mod-b">Feedback</h2>
                    <div class="decor-1 decor-1_mod-a"></div>
                    <div class="slider-reviews owl-carousel owl-theme owl-theme_mod-b enable-owl-carousel"
                        data-pagination="true"
                        data-single-item="true"
                        data-auto-play="7000"
                        data-transition-style="fade"
                        data-main-text-animation="true" 
                        data-after-init-delay="3000"
                        data-after-move-delay="1000"
                        data-stop-on-hover="true">

                      <?php foreach($feedbacks as $feedback) { ?>
                      <div class="reviews reviews_mod-b">
                        <?php if($feedback->foto) { ?>
                        <?php $img = base_url('uploads/' . ($feedback->tipo === 'f' ? 'feedback' : 'profissionais') . '/' . $feedback->foto); ?>
                        <div class="reviews__img">
                            <img class="img-responsive" src="<?php echo $img; ?>" style="max-height: 85px; max-width: 85px" alt="<?php echo $feedback->nome; ?>">
                        </div>
                        <?php } ?>
                        <div class="reviews__text"><?php echo $feedback->texto; ?></div>
                        <div class="reviews__title">
                          <div class="reviews__autor"><?php echo $feedback->nome; ?></div>
                        </div>
                      </div>
                      <?php } ?>
                    </div><!-- end slider-reviews -->
                  </div>
                </section>
              </div>
              <?php } ?>
            </div><!-- end row -->
          </div><!-- end container -->
        </div><!-- end section_mod-b -->
    
    <?php if($parceiros) { ?>
    <section class="section_mod-d border-top wow zoomIn" data-wow-duration="1s">
      <div class="container">
        <div class="row">
          <div class="col-xs-12">
            <div class="text-center">
              <h2 class="ui-title-block">Parceiros</span></h2>
              <div class="decor-1 decor-1_mod-a"></div>
            </div>
            <ul class="list-clients list-unstyled">
              <?php foreach($parceiros as $parceiro) { ?>
              <li class="list-clients__item">
                  <?php if($parceiro->link) { ?>
                  <a href="<?php echo $parceiro->link; ?>" target="_blank">
                      <img src="<?php echo base_url('uploads/parceiros/' . $parceiro->foto); ?>" height="70" width="125" alt="<?php echo $parceiro->parceiro; ?>">
                      <span class="helper"></span>
                  </a>
                  <?php } else { ?>
                  <img src="<?php echo base_url('uploads/parceiros/' . $parceiro->foto); ?>" height="70" width="125" alt="<?php echo $parceiro->parceiro; ?>">
                  <span class="helper"></span>
                  <?php } ?>
              </li>
              <?php } ?>
            </ul>
          </div>
          <!-- end col --> 
        </div>
        <!-- end row --> 
      </div>
      <!-- end container --> 
    </section>
    <!-- end section_mod-d -->
    <?php } ?>


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
