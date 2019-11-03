<meta name="robots" content="noindex, nofollow">
<section class="section_mod-d border-top" id="busca">
    <div class="container">
          <div class="row">
            <?php if($gratuitos || $pagos) { ?>
                <div class="col-xs-12">
                    <?php if(isset($profissao) && isset($cidade)) { ?>
                    <h1 class="ui-title-block">Busca por <span class="color_primary"><?php echo $profissao; ?></span> em <span class="color_primary"><?php echo $cidade; ?></span></h1>
                    <div class="decor-1 decor-1_mod-a"></div>
                    <?php } else { ?>
                        <?php if(isset($profissao)) { ?>
                        <div class="col-sm-6">
                            <h1 class="ui-title-block">Busca por <span class="color_primary"><?php echo $profissao; ?></span></h1>
                            <div class="decor-1 decor-1_mod-a"></div>
                        </div>
                        <div class="col-sm-6">
                            <select class="form-control filtro" data-profissao="<?php echo $profissao; ?>">
                                <option value="" class="option_cad">FILTRE POR CIDADE</option>
                                <?php foreach($listaCidades as $cidade) { ?>
                                <option value="<?php echo $cidade->nome; ?>"><?php echo $cidade->nome; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <?php } else {  ?>
                        <div class="col-sm-6">
                            <h1 class="ui-title-block">Busca por profissionais em <span class="color_primary"><?php echo $cidade; ?></span></h1>
                            <div class="decor-1 decor-1_mod-a"></div>
                        </div>
                        <div class="col-sm-6">
                            <select class="form-control filtro" data-cidade="<?php echo $cidade; ?>">
                                <option value="" class="option_cad">FILTRE POR PROFISSÃO</option>
                                <?php foreach($listaProfissoes as $profissao) { ?>
                                <option value="<?php echo $profissao->subcategoria; ?>"><?php echo $profissao->subcategoria; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <?php } ?>
                    <?php }  ?>
                </div>
                <?php if($pagos) { ?>
                    <ul class="list-unstyled">
                    <?php foreach($pagos as $profissional) { ?>
                        <li>
                            <div class="col-md-9 col-sm-8 col-xs-12">
                                <div class="pull-left foto">
                                    <a href="<?php echo base_url('profissional/visualizar/' . $profissional->url); ?>" title="Visualizar mais informações de <?php echo $profissional->nome; ?>">
                                        <?php if($profissional->foto) { ?>
                                        <img src="<?php echo base_url('uploads/profissionais/' . $profissional->foto); ?>" alt="<?php echo $profissional->nome; ?>" class="img-circle img-responsive" />
                                        <?php } else { ?>
                                        <i class="fa fa-user fotoBusca"></i>
                                        <?php } ?>
                                    </a>
                                </div>
                                <div class="pull-left info">
                                    <span>Nome:</span>
                                    <a href="<?php echo base_url('profissional/visualizar/' . $profissional->url); ?>" title="Visualizar mais informações de <?php echo $profissional->nome; ?>">
                                        <?php echo $profissional->nome; ?>
                                    </a>
                                    <br/>
                                    <?php if($profissional->telefone) { ?>
                                    <span>Telefone:</span>
                                    <a href="tel:<?php echo preg_replace('/-|\s+|\(|\)/', '', $profissional->telefone); ?>">
                                        <?php echo $profissional->telefone; ?>
                                    </a>
                                    <?php } ?>
                                    <?php if($profissional->celular) { ?>
                                    <br/>
                                    <span>Celular:</span>
                                    <a href="tel:<?php echo preg_replace('/-|\s+|\(|\)/', '', $profissional->celular); ?>">
                                        <?php echo $profissional->celular; ?>
                                    </a>
                                    <?php } ?>
                                    <br/>
                                    <span>Cidade:</span>
                                    <span class="color_primary"><?php echo $profissional->cidade; ?></span>
                                    <br/>
                                    <span>Profissão:</span>
                                    <span class="color_primary"><?php echo $profissional->profissao; ?></span>
                                    <br/>
                                    <br/>
                                    <?php if($profissional->descricao) { ?>
                                    <a href="<?php echo base_url('profissional/visualizar/' . $profissional->url); ?>" title="Visualizar mais informações de <?php echo $profissional->nome; ?>" class="descricao">
                                        <?php echo nl2br($profissional->descricao); ?>
                                    </a>
                                    <?php } ?>
                                </div>
                           </div>
                           <div class="col-md-3 col-sm-6 col-xs-12">
                               <strong><?php echo ($profissional->nota ? $profissional->totalAvaliacoes . ($profissional->totalAvaliacoes > 1 ? ' Avaliações' : ' Avaliação') : 'Nenhuma Avaliação!')?></strong>
                                   <div class="color_primary">
                                   <?php if($profissional->nota) { ?>
                                       <?php $nota = intval($profissional->nota); ?>
                                       <?php $resto = substr($profissional->nota, 2,2); ?>
                                       <?php for($i = 1; $i <= 5; $i++) { ?>
                                           <?php if($nota >= $i) { ?>
                                           <i class="fa fa-star"></i>
                                           <?php } else { ?>
                                               <?php if($resto === '5') { ?>
                                               <?php $resto = '';?>
                                               <i class="fa fa-star-half-full"></i>
                                               <?php } else { ?>
                                               <i class="fa fa-star-o"></i>
                                               <?php } ?>
                                           <?php } ?>
                                       <?php } ?>
                                   <?php } else { ?>
                                       <?php for($i = 1; $i <= 5; $i++) { ?>
                                       <i class="fa fa-star-o"></i>
                                       <?php } ?>
                                   <?php } ?>
                                   </div><br />
                               <a class="header-contacts__info btn_1" href="<?php echo base_url('profissional/visualizar/' . $profissional->url); ?>">Detalhes</a>
                               <br />
                                <a class="header-contacts__info btn_1" href="<?php echo base_url('pedidos/realizar'); ?>">Orçamento Rápido</a>
                               <?php if($profissional->plano == 'Avançado') { ?>
                               <div class="row">
                                    <br />
                                   <div class="col-md-12"> 
                                        <img src="<?php echo base_url('assets/img/selo.png'); ?>" alt="selo de planos sossegue" style="float: left;"> &nbsp;<b>Sossegue <?php echo $profissional->plano; ?></b>
                                    </div>
                               </div>
                               <?php } ?>
                           </div>
                        </li>
                    <?php } ?>
                    </ul>
                <?php } ?>
                <?php if($gratuitos) { ?>
                    <ul class="list-unstyled">
                    <?php foreach($gratuitos as $profissional) { ?>
                    <li>

                    <div class="col-md-10 col-sm-8 col-xs-12">
                            <div class="pull-left foto">
                            <?php if($profissional->foto) { ?>
                                <img src="<?php echo base_url('uploads/profissionais/' . $profissional->foto); ?>" alt="<?php echo $profissional->nome; ?>" class="img-circle img-responsive" />
                            <?php } else { ?>
                                <i class="fa fa-user fotoBusca"></i>
                            <?php } ?>
                            </div>
                        <div class="pull-left info">
                            <span>Nome:</span>
                            <span class="color_primary">
                                <?php echo $profissional->nome; ?>
                            </span>
                            <br/>
                            <?php if($profissional->telefone) { ?>
                            <span>Telefone:</span>
                            <span class="color_primary">
                                <?php echo $profissional->telefone; ?>
                            </span>
                            <br/>
                            <?php } ?>
                            <?php if($profissional->celular) { ?>
                            <span>Celular:</span>
                            <span class="color_primary">
                                <?php echo $profissional->celular; ?>
                            </span>
                            <br/>
                            <?php } ?>
                            <span>Cidade:</span>
                            <span class="color_primary">
                                <?php echo $profissional->cidade; ?>
                            </span>
                            <br/>
                            <span>Profissão:</span>
                            <span class="color_primary">
                                <?php echo $profissional->profissao; ?>
                            </span>
                        </div>
                    </div>
                    </li>
                    <?php } ?>
                    </ul>
                <?php } ?>
                
            <?php } else { ?>
            <div class="col-xs-12">
                <h1 class="ui-title-block">Nenhum(a) <span class="color_primary"><?php echo $profissao; ?></span> encontrado(a)!</h1>
                <div class="decor-1 decor-1_mod-a"></div>
            </div>
            <div class="col-sm-12">
                <select class="form-control filtro" data-profissao="<?php echo $profissao; ?>">
                    <option value="" class="option_cad">SELECIONE OUTRA CIDADE</option>
                    <?php foreach($listaCidades as $cidade) { ?>
                    <option value="<?php echo $cidade->nome; ?>"><?php echo $cidade->nome; ?></option>
                    <?php } ?>
                </select>
            </div>
            <?php } ?>
          </div>
    </div>
</section>

<?php if($pagos || $gratuitos) { ?>
<div class="container">
    <?php echo $this->pagination->create_links(); ?>
</div>
<?php } ?>
<?php include_once("analyticstracking.php") ?>
