
<footer class="footer wow zoomIn" data-wow-duration="1s">
      <div class="footer-main">
        <div class="section__inner">
          <div class="container">
            <div class="row">
              <div class="col-md-3 col-sm-6">
                <div class="footer__section"> <a class="footer__logo" href="javascript:void(0);"><img src="<?php echo base_url('assets/img/logo_mod-b.png')?>" height="43" width="197" alt="Logo"></a>
                  <div class="footer__info">
                    <?php echo nl2br($config['rodape']); ?>
                  </div>
                  <ul class="social-links">
                    <?php if($config['facebook']) { ?>
                    <li><a class="icon fa fa-facebook" href="<?php echo $config['facebook']; ?>" target="_blank"></a></li>
                    <?php } ?>
                    <?php if($config['twitter']) { ?>
                    <li><a class="icon fa fa-twitter" href="<?php echo $config['twitter']; ?>"></a></li>
                    <?php } ?>
                    <?php if($config['gplus']) { ?>
                    <li><a class="icon fa fa-google-plus" href="<?php echo $config['gplus']; ?>"></a></li>
                    <?php } ?>
                    <?php if($config['pinterest']) { ?>
                    <li><a class="icon fa fa-pinterest-p" href="<?php echo $config['pinterest']; ?>"></a></li>
                    <?php } ?>
                  </ul>
                </div>
              </div>
              <!-- end col -->
              
              <div class="col-md-2 col-sm-6">
                <section class="footer__section">
                  <h3 class="footer__title">Links</h3>
                  <div class="decor-1 decor-1_mod-b"></div>
                  <ul class="footer-list list-unstyled">
                    <li class="footer-list__item"> <a class="footer-list__link" href="<?php echo base_url(); ?>" target="_blank">Home</a> </li>
                    <li class="footer-list__item"> <a class="footer-list__link" href="<?php echo base_url('blog'); ?>" target="_blank">Blog</a> </li>
                    <li class="footer-list__item"> <a class="footer-list__link" href="<?php echo base_url('quem_somos'); ?>" target="_blank">Quem Somos</a> </li>
                    <li class="footer-list__item"> <a class="footer-list__link" href="<?php echo base_url('cadastro'); ?>" target="_blank">Cadastrar</a> </li>
                    <li class="footer-list__item"> <a class="footer-list__link" href="<?php echo base_url('faq'); ?>" target="_blank">Perguntas frequentes</a></li>
                    <li class="footer-list__item"> <a class="footer-list__link" href="<?php echo base_url('termos_e_condicoes'); ?>" target="_blank">Termos de Uso</a></li>
                  </ul>
                </section>
              </div>
              <!-- end col -->
              
              
              
              <div class="col-md-3 col-sm-6">
                <section class="footer__section">
                  <h3 class="footer__title">Contato</h3>
                  <div class="decor-1 decor-1_mod-b"></div>
                  <?php if($config['endereco']) { ?>
                  <div class="footer-contact"> <i class="icon icon-home"></i>
                    <div class="footer-contact__inner">
                      <div class="footer-contact__title">Endereço</div>
                      <div class="footer-contact__info"><?php echo $config['endereco']; ?></div>
                    </div>
                  </div>
                  <?php } ?>
                  <!-- end footer-contact -->
                  <?php if($config['telefone']) { ?>
                  <div class="footer-contact"> <i class="icon icon-call-in"></i>
                    <div class="footer-contact__inner">
                      <div class="footer-contact__title">Telefone</div>
                      <div class="footer-contact__info"><?php echo $config['telefone']; ?></div>
                      <?php if($config['telefone2']) { ?>
                      <div class="footer-contact__info"><?php echo $config['telefone2']; ?></div>
                      <?php } ?>
                      <?php if($config['telefone3']) { ?>
                      <div class="footer-contact__info"><?php echo $config['telefone3']; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <?php } ?>
                  <!-- end footer-contact -->
                  <?php if($config['email']) { ?>
                  <div class="footer-contact"> <i class="icon icon-envelope-open"></i>
                    <div class="footer-contact__inner">
                      <div class="footer-contact__title">E-mail</div>
                      <div class="footer-contact__info"><?php echo $config['email']; ?></div>
                    </div>
                  </div>
                  <?php } ?>
                  <!-- end footer-contact -->
                  <?php if($config['horario']) { ?>
                  <div class="footer-contact"> <i class="icon icon-clock"></i>
                    <div class="footer-contact__inner">
                      <div class="footer-contact__title">Horario</div>
                      <div class="footer-contact__info"><?php echo $config['horario']; ?></div>
                    </div>
                  </div>
                  <?php } ?>
                  <!-- end footer-contact --> 
                </section>
              </div>
              <!-- end col --> 

              <?php if($config['facebook']) { ?>
              <div class="col-md-3 col-sm-6">
                <div class="fb-page" data-href="<?php echo $config['facebook']; ?>" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><div class="fb-xfbml-parse-ignore"><blockquote cite="<?php echo $config['facebook']; ?>"><a href="<?php echo $config['facebook']; ?>">Sossegue</a></blockquote></div></div>
              </div>
              <?php } ?>
              <!-- end col --> 

            </div>
            <!-- end row --> 
          </div>
          <!-- end container --> 
        </div>
        <!-- end section__inner --> 
      </div>
      <!-- end footer-main -->
      
      <?php if(strtolower($config['newsletter']) === 'sim') { ?>
      <?php echo form_open('home/newsInscrever', array('class' => 'footer-form')); ?>
        <div class="container">
          <div class="row">
            <div class="col-xs-12">
              <div class="footer-form__title-group">
                <div class="footer__title">newsletter</div>
                <div class="decor-1 decor-1_mod-c"></div>
              </div>
              <input class="form-control" type="email" name="emailNews" placeholder="Seu e-mail" value="<?php echo set_value('emailNews') ?>" required>
              <button type="submit" class="btn btn-default btn-second btn-effect">INSCREVER</button>
            </div>
          </div>
        </div>
      <?php echo form_close(); ?>
      <?php } ?>
      
      <div class="copyright border-top">
        <div class="container">
          <div class="row">
            <div class="col-xs-12">
              <div class="text-center">Sossegue © <?php echo date('Y');?>. Todos os direitos reservados.</div>
            </div>
          </div>
        </div>
      </div>
      <!-- end copyright --> 
    </footer>
    <!-- end footer -->
