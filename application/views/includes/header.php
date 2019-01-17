<header class="header">
            
      <div class="header-main">
        <div class="container">
          <div class="row">
            <div class="col-xs-12">
              <div class="header-main__inner clearfix"> 
                <a class="header__logo" href="<?php echo base_url() ?>">
                  <img class="img-responsive" src="<?php echo base_url('assets/img/logo_mod-a.png') ?>" height="43" width="197" alt="Logo">
                </a>
                <br>
                <div class="header-contacts">
                  <span class="header-contacts__item">
                    <i class="icon icon-envelope-open"></i> 
                    <span class="header-contacts__inner"> 
                      <span class="header-contacts__title">
	                      <?php if(!$this->session->userdata('logado')) { ?>
		                      <a class="header-contacts__info btn_1" href="<?php echo base_url('login'); ?>" target="_blank">Entrar</a>
	                	  <?php } else { ?>
                      <a class="header-contacts__info btn_1" href="<?php echo base_url('restrita/home'); ?>" target="_blank"><?php echo ($this->session->userdata('tipo') === 'Comum' ? 'Meus Dados' : 'Painel'); ?></a>
                      <?php } ?>
                    </span> 
                  </span> 

                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- end header-main -->

       <div class="yamm-wrap">
         
      <div class="container">
        <div class="row">
          <div class="col-xs-12">


          <div class="top-nav">              
              <div class="navbar yamm">
                <div class="navbar-header hidden-md hidden-lg hidden-sm">
                  <button type="button" data-toggle="collapse" data-target="#navbar-collapse-1" class="navbar-toggle"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
                  <a href="javascript:void(0);" class="navbar-brand">Menu</a> 
                </div>
                <nav id="navbar-collapse-1" class="navbar-collapse collapse">
                  <ul class="nav navbar-nav">
                  <?php foreach ($MenuSubCategorias as $item) { ?>
                    <?php if ($this->session->userdata('id_orcamento')) {  ?>
                        <?php if ($item->subcategoria == 'Marido de Aluguel') { ?>
                    <li><a href="<?php echo base_url($item->classe.'/'.removeAcentos($item->link)); ?>">FIXAÇÃO </a></li>
                        <?php } else { ?>
                    <li><a href="<?php echo base_url($item->classe.'/'.removeAcentos($item->link)); ?>"><?php echo $item->subcategoria; ?> </a></li>
                        <?php } ?>
                    <?php } else { ?>
                    <li><a href="<?php echo base_url($item->classe.'/'.removeAcentos($item->link)); ?>"><?php echo $item->subcategoria; ?> </a></li>
                    <?php } ?>
                  <?php } ?>
                    <li><a href="<?php echo base_url('blog'); ?>" target="_blank">Blog </a>
                    </li>
                  </ul>
                </nav>
              </div>
            </div>

          </div>
          <!-- end col --> 
        </div>
        <!-- end row --> 
      </div>
      </div>
      <!-- end container --> 
    </header>
    <!-- end header -->
