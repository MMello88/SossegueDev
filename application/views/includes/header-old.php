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
                    <li><a>Manutenção </a>
                        <ul role="menu" class="dropdown-menu">
                            <li> <a href="<?php echo base_url('eletricista/ribeirao_preto'); ?>" target="_blank">Eletricista</a> </li>  
                            <li> <a href="<?php echo base_url('encanador/ribeirao_preto'); ?>" target="_blank">Encanador</a> </li>  
                            <li> <a href="<?php echo base_url('jardinagem/ribeirao_preto'); ?>" target="_blank">Jardinagem e Paisagismo</a> </li>  
                            <li> <a href="<?php echo base_url('marido_de_aluguel/ribeirao_preto'); ?>" target="_blank">Marido de Aluguel</a> </li>
                            <li> <a href="<?php echo base_url('montador_de_moveis/ribeirao_preto'); ?>" target="_blank">Montador de Móveis</a> </li>
                        </ul>
                    </li>
                    <li><a>Reformas </a>
                        <ul role="menu" class="dropdown-menu">
                            <li> <a href="<?php echo base_url('gesso/ribeirao_preto'); ?>" target="_blank">Gesso e Drywall</a> </li>  
                            <li> <a href="<?php echo base_url('marceneiro/ribeirao_preto'); ?>" target="_blank">Marceneiro</a> </li>  
                            <li> <a href="<?php echo base_url('pintor/ribeirao_preto'); ?>" target="_blank">Pintor</a> </li>                      
                        </ul>
                    </li>
                    <li><a href="<?php echo base_url('blog'); ?>" target="_blank">Blog </a>
                    </li>
                  </ul>
                </nav>
              </div>
            </div>


<?php /* ?>
              <nav class="navbar navbar-inverse" role="navigation">
                <div class="container-fluid">
                  <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                      <span class="sr-only">Toggle navigation</span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                    </button>
                  </div>
                  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                      
                      <?php foreach($categorias as $categoria){ ?>
                        <li class="dropdown">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $categoria->nome; ?></a>
                          <?php if(isset($categoria->subcategorias)) { ?>
                            <ul class="dropdown-menu">
                              <?php foreach($categoria->subcategorias as $subcategoria){ ?>
                                <li><a href="<?php echo base_url('busca/profissionais?profissao=' . $subcategoria->nome); ?>"><?php echo $subcategoria->nome; ?></a></li>
                              <?php } ?>
                            </ul>
                          <?php } ?>
                        </li>
                      <?php } ?>
                    </ul>                    
                  </div>
                </div>
              </nav>
<?php */ ?>

          </div>
          <!-- end col --> 
        </div>
        <!-- end row --> 
      </div>
      </div>
      <!-- end container --> 
    </header>
    <!-- end header -->
