<meta name="robots" content="noindex, nofollow">
    <section class="section_mod-d border-top">
      <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3 wow bounceInLeft txt-center" data-wow-duration="1s">
                <h1 class="ui-title-block">FAÇA SEU PEDIDO DE ORÇAMENTO</h1>
            </div>
          <!-- end col -->

            <div class="container">
                <div class="row">
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
                        <?php echo form_open('cadastro/comum', array('id' => 'formCadastro', 'class' => 'bounceInLeft form-b', 'data-wow-duration' => '2s')); ?>
                            <div class="form-request__text form-b col-sm-12">Preencha os Campos</div>
                            <div class="col-sm-12">
                                <input class="form-control form-b" type="text" placeholder="Nome Completo" name="nome" id="nome" required>
                            </div>
                            <div class="col-sm-12">
                                <input class="form-control form-b" type="mail" placeholder="email" name="email" required>
                            </div>
                            <div class="col-sm-6">
                                <input class="form-control form-b" type="text" id="telefone" placeholder="Telefone" name="telefone">
                            </div>
                            <div class="col-sm-6">
                                <input class="form-control form-b" type="text" id="celular" placeholder="Celular" name="celular">
                            </div>
                           <div class="col-sm-6">
                                <select name="categoria" id="categoria" class="form-control form-b" required>
                                    <option value="" class="option_cad"> CATEGORIA</option>
                                    <?php foreach($listaCategorias as $categoria) { ?>
                                    <option value="<?php echo $categoria->id_categoria; ?>" class="option_cad"><?php echo $categoria->categoria; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <select name="subcategoria" id="subcategoria" class="form-control form-b" required>
                                    <option value="" class="option_cad"> SUB CATEGORIA</option>
                                </select>
                            </div>
                            <div class="col-sm-12">
                                <input class="form-control form-b" type="textarea" id="descricao" placeholder="Descreva o serviço que procura" name="descricao">
                            </div>

                            <div class="col-md-8 col-md-offset-2">
                                <button class="btn btn-default btn-block btn-second btn-effect">Cadastrar</button>
                            </div>
                        <?php echo form_close(); ?>
                    </div>
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

