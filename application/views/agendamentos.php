<meta name="robots" content="noindex, nofollow">
    <section class="section_mod-d border-top">
      <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3 wow bounceInLeft txt-center" data-wow-duration="1s">
                <h1 class="ui-title-block">PREENCHA O FORMULÁRIO</h1>
                <h3> QUE LOGO ENTRAREMOS EM CONTATO PARA A SUA APROVAÇÃO DO SERVIÇO</h3>
            </div>
          <!-- end col -->

            <div class="container">
                <div class="row">

                <style>
                    .panel-tabs {
                        position: relative;
                        bottom: 30px;
                        clear:both;
                        border-bottom: 1px solid transparent;
                    }

                    .panel-tabs > li {
                        float: left;
                        margin-bottom: -1px;
                    }

                    .panel-tabs > li > a {
                        margin-right: 2px;
                        margin-top: 4px;
                        line-height: .85;
                        border: 1px solid transparent;
                        border-radius: 4px 4px 0 0;
                        color: #ffffff;
                    }

                    .panel-tabs > li > a:hover {
                        border-color: transparent;
                        color: #ffffff;
                        background-color: transparent;
                    }

                    .panel-tabs > li.active > a,
                    .panel-tabs > li.active > a:hover,
                    .panel-tabs > li.active > a:focus {
                        color: #fff;
                        cursor: default;
                        -webkit-border-radius: 2px;
                        -moz-border-radius: 2px;
                        border-radius: 2px;
                        background-color: rgba(255,255,255, .23);
                        border-bottom-color: transparent;
                    }
                    /*========================================  MODIFICAÇÕES TEMPORARIAS ===========================*/
                    .panel-primary > .panel-heading {
                        color: #ffffff;
                        background-color: #486d8b !important;
                        border-color: #355671 !important;
                    }
                    .panel-title{color:#fff !important;}
                    .panel-primary{border-color: #355671 !important; padding-bottom: 10px; margin-top: 40px;}
                </style>

                    
                        
                    <div class="col-md-7 wow bounceInRight" data-wow-duration="1s">
                        <div class="section-list-block">
                            <ul class="list-block list-unstyled">
                                <?php foreach($textoBlocos as $key => $texto) { ?>
                                <li class="list-block__item"> 
                                    <a class="list-block__link">                                        
                                        <img src="<?php echo base_url('assets/img/icon_cad_' . ($key + 1) . '.png'); ?> " alt="icone 1" style="float: left; padding: 0px 25px 0px 0px;">
                                        <div class="list-block__title"><?php echo $texto->complemento; ?></div>
                                        <div class="list-block__info"><?php echo $texto->valor; ?></div>
                                    </a>
                                </li>
                                <?php } ?>
                            </ul>                            
                        </div>
                    </div>



                    <div class="col-md-4 col-md-offset-1 margin_g form_cadastro">                        
                        <?php echo form_open('agendamentos/agendar', array('id' => 'formCadastro', 'class' => 'bounceInLeft form-b', 'data-wow-duration' => '2s')); ?>
                            <div class="form-request__text form-b col-sm-12">Faça seu Agendamento</div>
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
                            <div class="col-sm-12">
                                <input class="form-control form-b" type="text" placeholder="* CEP" name="bairro" id="bairro" required>
                            </div>
                            <div class="col-sm-6">
                                <select name="categoria" id="categoria" class="form-control form-b" required>
                                    <option value="" class="option_cad">* CATEGORIA</option>
                                    <?php foreach($listaCategorias as $categoria) { ?>
                                    <option value="<?php echo $categoria->id_categoria; ?>" class="option_cad"><?php echo $categoria->categoria; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <select name="subcategoria" id="subcategoria" class="form-control form-b" required>
                                    <option value="" class="option_cad">* SUB CATEGORIA</option>
                                </select>
                            </div>
                            <div class="col-sm-12"><h6>Para qual data deseja agendar?</h6></div><br clear=all/>

                            <div class="col-sm-12">
                                <input class="form-control form-b" type="text" id="data_de" placeholder="* DATA" name="data_de">
                            </div>
                            <div class="col-sm-12"><h6>Horário?</h6></div><br clear=all/>
                            <div class="col-sm-12">
                                <input class="form-control form-b" type="text" placeholder="* Horário" name="horario" id="horario" required>
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
    <div class="modal fade" id="modalCadastro" tabindex="-1" role="dialog" style="margin-top: 80px">
      <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?php echo $this->session->flashdata('msg'); ?></h4>
          </div>
          <div class="modal-body">
            <button type="button" class="btn btn-info" data-dismiss="modal">Cancelar</button>
            <a href="<?php echo base_url('login'); ?>" type="button" class="btn btn-primary">Login</a>
          </div>
        </div>
      </div>
    </div>
    <?php } ?>

