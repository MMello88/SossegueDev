<div class="main-slider slider-pro" id="my-slider">
      <div class="sp-slides"> 
        <?php $arr = range(1,4); ?>
        <?php while( sizeof( $arr )>0 ) { 
          shuffle( $arr );
          ?>
        <div class="sp-slide"> <img class="sp-image" src="<?php echo base_url("assets/media/main-slider/$arr[0].jpg")?>" height="650" width="1600" alt="logo"> <span class="sp-layer hidden-xs"
                  data-horizontal="0"
                  data-vertical="-14vw"
                  data-position="centerCenter"
                  data-show-transition="left"
                  data-hide-transition="up"
                  data-show-duration="800"
                  data-show-delay="400"></span>
          <div class="sp-layer main-slider__title"
                data-horizontal="0"
                data-vertical="-4vw"
                data-position="centerCenter"
                data-show-transition="left"
                data-hide-transition="up"
                data-show-duration="800"
                data-show-delay="800"
                data-hide-delay="400">
          </div>               
          <p class="sp-layer main-slider__slogan text-center hidden-xs"
                data-horizontal="0"
                data-vertical="6vw"
                data-position="centerCenter"
                data-show-transition="left"
                data-hide-transition="up"
                data-show-duration="800"
                data-show-delay="1200"
                data-hide-delay="800"> 
          </p>
          <div class="sp-layer hidden-xs"
                data-horizontal="0"
                data-vertical="20vw"
                data-position="centerCenter"
                data-show-transition="left"
                data-hide-transition="up"
                data-show-duration="800"
                data-show-delay="1600"
                data-hide-delay="1200"> </div>
        </div>
        <?php unset( $arr[0] ); ?>
        <?php } ?>
        
        
      </div>
      <!-- end sp-slides --> 
    </div>
    <!-- end main-slider -->




<div class="row busca-banner">
	<div class="container">
		<div class="col-md-7 txt-center txt-banner">
		  <div>
		        <h1>RECEBA 3 ORÇAMENTOS</h1>
		        <h6>PROCURAMOS OS MELHORES PROFISSIONAIS DA REGIÃO</h6>
		        <br />
		        <a href="<?php echo base_url('pedidos'); ?>" class="btn btn-default btn-second btn-effect btn-banner-b">Pedir Orçamento</a>   
		  </div><br /><br />
			</div>
			<div class="col-md-4 txt-center txt-banner">
				<h2>ANUNCIE SEU SERVIÇO E <br />OBTENHA MAIS CLIENTES</h2><br />
	   			<a href="<?php echo base_url('cadastro'); ?>" class="btn btn-default btn-second btn-effect btn-banner-b">Anuncie</a>
			</div>
		     <div class="form-group col-md-6">    
		       
		    </div>
		</div>
	</div>
</div>

<div class="margin-banner"></div>
