<meta name="robots" content="noindex, nofollow">    
    
    <section class="section_mod-d border-top">
      <div class="container">
        <div class="row">
          <div class="col-md-6 col-md-offset-3 wow bounceInLeft txt-center" data-wow-duration="1s">
            <h1 class="ui-title-block">Crie uma nova senha</h1>
            <h5>Se pretende anunciar seu serviço e não possui cadastro. <a href="<?php echo base_url('cadastro'); ?>">Clique aqui</a> </h5>
          </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="panel panel-login form-signin">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-12 text-center">
                                        <a href="#" class="active" id="login-form-link">Redefinir senha</a>
                                    </div>
                                </div>                                
                            </div>
                            <hr class="colorgraph">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <?php echo form_open('login/redefinirSenha', array('id' => 'login-form', 'role' => 'form', 'style' => 'display:block')); ?>
                                            <div class="form-group">
                                                <input type="email" name="email" id="email" tabindex="1" class="form-control" placeholder="Confirme seu e-mail" value="" required>
                                            </div>
                                            <div class="form-group">
                                                <input type="password" name="senha" id="senha" tabindex="2" class="form-control" placeholder="Senha" required>
                                            </div>    
                                            <div class="form-group">
                                                <input type="password" id="confSenha" tabindex="2" class="form-control" placeholder="Confirme a senha" required>
                                            </div>                                        
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-sm-6 col-sm-offset-3">
                                                        <input type="submit" id="login-submit" tabindex="4" class="btn btn_1 btn-block btn-effect" value="Redefinir senha">
                                                    </div>
                                                </div>
                                            </div> 

                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="text-center">
                                                            <a href="<?php echo base_url('cadastro/comum'); ?>" tabindex="5" class="forgot-password">Criar uma nova conta.</a>
                                                        </div>
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
<?php include_once("analyticstracking.php") ?>
