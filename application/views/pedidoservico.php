    <section class="section_mod-a section_mod-d border-top">
      <div class="container">
        <div class="row">
          <div class="col-md-6 col-md-offset-3 wow bounceInLeft txt-center" data-wow-duration="1s">
            <h1 class="ui-title-block">Como Funciona</h1>
            <h5>Nós te ajudamos a contratar o melhor<br /> profissional para o serviço que você precisa.</h5>
          </div>
  

            
            
                    <div class="col-md-4 col-md-offset-1 margin_g form_cadastro">                        
                        <?php echo form_open('manutencao/pedidoservico', array('id' => 'formCadastro', 'class' => 'bounceInLeft form-b', 'data-wow-duration' => '2s')); ?>
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
                            <div class="col-sm-6">
                                <select name="categoria" id="categoria" class="form-control form-b">
                                    <option value="" class="option_cad">Manutenção</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <select name="subcategoria" id="subcategoria" class="form-control form-b">
                                    <option value="3" class="option_cad"><strong>Eletricista</strong></option>
                                </select>
                            </div>
							<div class="col-sm-12">
                                <input class="form-control form-b" type="text" placeholder="* Descrição do Serviço" name="descricao" id="descricao" required>
                            </div>
                            <div class="col-md-8 col-md-offset-2">
                                <button class="btn btn-default btn-block btn-second btn-effect">Fazer Pedido</button>
                            </div>
                        <?php echo form_close(); ?>
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
