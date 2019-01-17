<title>Perguntas Frequentes - Receba 3 Orçamentos | Sossegue</title>
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
            <h1 class="ui-title-block">Perguntas Frequentes</h1>
            <h5>Tire dúvidas em nossa página de perguntas frequentes organizadas para cada tipo de usuário.</h5><br /><br />
          </div>
          <!-- end col -->

            <div class="container">
                <div class="row">

                    <?php if($perguntaUsuarios) { ?>
                    <div class="col-md-6 col-xs-12">
                        <h4 style="text-align: center">Perguntas de usuários </h4>
                        <div class="panel-group" id="faqAccordion">
                            <?php foreach($perguntaUsuarios as $faq) { ?>
                            <div class="panel panel-default ">
                                <div class="panel-heading accordion-toggle question-toggle collapsed" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question<?php echo $faq->id; ?>">
                                     <h4 class="panel-title">
                                        <a class="ing"><?php echo $faq->pergunta; ?></a>
                                  </h4>

                                </div>
                                <div id="question<?php echo $faq->id; ?>" class="panel-collapse collapse" style="height: 0px;">
                                    <div class="panel-body">
                                         <h5><span class="label label-primary">Resposta</span></h5>
                                         <p>
                                             <?php echo nl2br($faq->resposta); ?>
                                         </p>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>

                    <?php if($perguntaAnunciantes) { ?>
                    <div class="col-md-6 col-xs-12">
                        <h4 style="text-align: center">Perguntas de anunciantes </h4>
                        <div class="panel-group" id="faqAccordion2">
                            <?php foreach($perguntaAnunciantes as $faq) { ?>
                            <div class="panel panel-default ">
                                <div class="panel-heading accordion-toggle question-toggle collapsed" data-toggle="collapse" data-parent="#faqAccordion2" data-target="#question<?php echo $faq->id; ?>">
                                     <h4 class="panel-title">
                                        <a class="ing"><?php echo $faq->pergunta; ?></a>
                                  </h4>

                                </div>
                                <div id="question<?php echo $faq->id; ?>" class="panel-collapse collapse" style="height: 0px;">
                                    <div class="panel-body">
                                         <h5><span class="label label-primary">Resposta</span></h5>
                                         <p>
                                             <?php echo nl2br($faq->resposta); ?>
                                         </p>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
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
    <?php include_once("analyticstracking.php") ?>

