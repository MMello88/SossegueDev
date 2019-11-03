<style>
      blink {
        animation: blinker 2.0s linear infinite;
        color: #1c87c9;
       }
      @keyframes blinker {  
        50% { opacity: 0.0; }
       }
</style>
<header class="header">
            
      <div class="header-main">
        <div class="container">
          <div class="row">

            <div class="col-sm-8 col-8">
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
            <div class="col-sm-4 col-4">
              <h5>Preencha o Formulário ou nos ligue!</h5>
              <?php

                if (empty($cidade)){
                  echo "
                    <div class='countainer clearfix'>
                      <div style='width:100%; float:left; margin-bottom:10px'>
                        <i class='icon fa fa-phone' style='font-size:40px; float:left; margin-right:5px;'></i>
                        <label style='font-size:30px;'>(16) 4141-3241</label>
                      </div>
                      <div style='width:100%'>
                        <blink>
                          <a href='https://wa.me/55016981928545' target='_blank' style='float:left; margin-right:5px; color:#09D261'>
                            <i class='icon fa fa-whatsapp' style='font-size:40px;'></i>
                          </a>
                        </blink>
                        <label style='font-size:30px;'>(16) 98192-8545</label>
                      </div>
                    </div>
                  ";
                } else {
                  if(empty($cidades[0]->contatos)){
                    foreach ($cidades[0]->contatos as $contato) {
                      echo "<div class='countainer clearfix'>";
                      if(!empty($contato->telefone))
                        echo
                          "<div style='width:100%; float:left; margin-bottom:10px'>
                            <i class='icon fa fa-phone' style='font-size:40px; float:left; margin-right:5px;'></i>
                            <label style='font-size:30px;'>$contato->telefone</label>
                          </div>";
                      if(!empty($contato->celular)){
                        $remove = ["(", ")", "-", " "];
                        $cel = str_replace($remove,"",$contato->celular);
                        echo "
                        <div style='width:100%'>
                          <blink>
                            <a href='https://wa.me/55{$cel}' target='_blank' style='float:left; margin-right:5px; color:#09D261'>
                              <i class='icon fa fa-whatsapp' style='font-size:40px;'></i>
                            </a>
                          </blink>
                          <label style='font-size:30px;'>$contato->celular</label>
                        </div>
                        ";
                      }
                      echo "</div>";
                    }
                  } else {
                    echo "
                      <div class='countainer clearfix'>
                        <div style='width:100%; float:left; margin-bottom:10px'>
                          <i class='icon fa fa-phone' style='font-size:40px; float:left; margin-right:5px;'></i>
                          <label style='font-size:30px;'>(16) 4141-3241</label>
                        </div>
                        <div style='width:100%'>
                          <blink>
                            <a href='https://wa.me/55016981928545' target='_blank' style='float:left; margin-right:5px; color:#09D261'>
                              <i class='icon fa fa-whatsapp' style='font-size:40px;'></i>
                            </a>
                          </blink>
                          <label style='font-size:30px;'>(16) 98192-8545</label>
                        </div>
                      </div>
                    ";
                  }
                }
                ?>
            </div>
        
        
                  
          
          </div>
        </div>
       </div>
      <!-- end header-main -->

<?php
  $linkCidade = '';
  if (isset($cidade) && !empty($cidade)){
    $linkCidade = '/'.$cidade;
  }
?>

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
                    <li><a href="<?php echo base_url($item->classe.'/'.removeAcentos($item->link).$linkCidade); ?>">FIXAÇÃO </a></li>
                        <?php } else { ?>
                    <li><a href="<?php echo base_url($item->classe.'/'.removeAcentos($item->link).$linkCidade); ?>"><?php echo $item->subcategoria; ?> </a></li>
                        <?php } ?>
                    <?php } else { ?>
                    <li><a href="<?php echo base_url($item->classe.'/'.removeAcentos($item->link).$linkCidade); ?>"><?php echo $item->subcategoria; ?> </a></li>
                    <?php } ?>
                  <?php } ?>
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