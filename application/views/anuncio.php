<meta name="robots" content="noindex, nofollow">
<section class="section_mod-d border-top" id="busca">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h1 class="ui-title-block"><?php echo $profissional->nome; ?> - <span class="color_primary"><?php echo $profissional->subcategoria; ?></span></h1>
                <div class="decor-1 decor-1_mod-a"></div>
            </div>



            <div class="row">    
                
                <div class="col-xs-12 col-sm-12 col-md-6 toppad" >
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">INFORMAÇÕES PESSOAIS</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-3 col-lg-3 " align="center">
                                    <?php if(!$profissional->foto) { ?>
                                    <i class="fa fa-user " style="font-size: 8em"></i>
                                    <?php } else { ?>
                                    <img alt="<?php echo $profissional->nome; ?>" src="<?php echo base_url('uploads/profissionais/' . $profissional->foto); ?>" class="img-circle img-responsive">
                                    <?php } ?>
                                </div>
                                <div class=" col-md-9 col-lg-9 "> 
                                    <table class="table table-user-information">
                                        <tbody>
                                            <tr>
                                                <td>Profissão:</td>
                                                <td><?php echo $profissional->subcategoria; ?></td>
                                            </tr>
                                            <?php if($profissional->endereco) { ?>
                                            <tr>
                                                <td>Endereço:</td>
                                                <td><?php echo $profissional->endereco . ', ' . $profissional->numero . ' ' . $profissional->complemento; ?></td>
                                            </tr>
                                            <?php } ?>
                                            <?php if($profissional->bairro) { ?>
                                            <tr>
                                                <td>Bairro:</td>
                                                <td><?php echo $profissional->bairro; ?></td>
                                            </tr>
                                            <?php } ?>
                                            <tr>
                                                <td>Cidade:</td>
                                                <td><?php echo $profissional->cidade . ' - ' . $profissional->estado; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Email:</td>
                                                <td><?php echo $profissional->email; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Telefone:</td>
                                                <td><?php echo $profissional->telefone . ($profissional->celular ? ' / ' . $profissional->celular : ''); ?></td>
                                            </tr>
                                            <?php if($profissionalLinks) { ?>
                                            <tr>
                                                <td>Redes Sociais e site</td>
                                                <td>
                                                <?php foreach($profissionalLinks as $link) { ?>
                                                    <?php if($link->link) { ?>
                                                    <div class="col-md-4">
                                                        <a href="<?php echo (substr($link->link, 0, 4) !== 'http' ? "http://$link->link" : $link->link); ?>" target="_blank">
                                                            <img src="<?php echo base_url('uploads/' . $link->img); ?>" alt="<?php echo $profissional->nome; ?>">
                                                        </a>
                                                    </div>
                                                    <?php } ?>
                                                <?php } ?>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>                          
                                </div>
                            </div>
                        </div>  
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6 toppad" >
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">AVALIAÇÃO E DESCRIÇÃO - <a href="#avaliar" style="color:#ffcb1e;">AVALIAR</a></h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
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
                                    </div>
                                </div>           

                                <?php if($profissional->descricao){ ?>
                                <div class=" col-md-12">
                                    <br/>
                                    <strong>Descrição</strong>
                                    <br/>
                                    <?php echo nl2br($profissional->descricao); ?>
                                </div>
                                <?php } ?>
                            </div>
                        </div>  
                    </div>
                </div>

            </div>

            <?php if($fotos) { ?>
            <div class="row"> 
                <div class="col-xs-12 col-sm-12 col-md-12 toppad" >
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Imagens</h3>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?php foreach($fotos as $foto){ ?>
                                    <div class="col-md-3 col-xs-12 thumb">
                                        <a href="<?php echo base_url('uploads/profissionais/' . $foto->foto); ?>" class="thumbnail fancybox" rel="group">
                                            <img class="img-responsive" src="<?php echo base_url('uploads/profissionais/' . $foto->foto); ?>" alt="<?php echo $profissional->nome; ?>">
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>

            <div class="row">
                <div class="col-md-12 toppad" >
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">REGIÃO ATENDIDA</h3>
                        </div>
                        <?php if(empty($avaliacoes)) { ?>
                            <div id="avaliar"></div>
                        <?php } ?>
                        <div class="panel-body">
                            <div class="row">
                                <div id="mapa" data-profissional="<?php echo $profissional->nome?>" data-endereco="<?php echo $profissional->endereco.', '.$profissional->numero.', '.$profissional->cidade.'-'.$profissional->estado?>" style="width: 100%; height: 450px"></div>
                            </div>
                        </div>  
                    </div>
                </div>
                <?php if($avaliacoes) { ?>
                <div class="col-md-12 toppad" >
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">AVALIAÇÕES E COMENTÁRIOS</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <?php foreach($avaliacoes as $avaliacao){ ?>
                                    <div class="col-md-12">
                                        <strong><?php echo $avaliacao->nome; ?></strong> <?php echo $avaliacao->data; ?>
                                        <div class="color_primary">
                                           <?php $nota = intval($avaliacao->nota); ?>
                                           <?php for($i = 1; $i <= 5; $i++) { ?>
                                               <?php if($nota >= $i) { ?>
                                               <i class="fa fa-star"></i>
                                               <?php } else { ?>
                                                   <i class="fa fa-star-o"></i>
                                               <?php } ?>
                                           <?php } ?>
                                        </div>
                                    </div>           

                                    <div class=" col-md-12 lista-comentarios"> 
                                        <br />
                                        <p><?php echo nl2br($avaliacao->comentario); ?></p>
                                    </div>
                                <?php } ?>
                                <div id="avaliar"></div>
                            </div>
                        </div> 
                    </div>
                </div>
                <?php } ?>                
            </div>
            
        </div>
    </div>
</section>

<div class="container">
    <div class="row">
        <?php echo $this->pagination->create_links(); ?>
        <div class="col-md-6 col-md-offset-3 toppad" >
            <div class="row">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">AVALIAR ANUNCIANTE  - <a data-toggle="modal" data-target="#myModal" style="color:#ffcb1e; cursor: pointer;">COMO AVALIAR</a></h3>
                        
                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" style="color:#666 !important;" id="myModalLabel">Como avaliar</h4>
                                    </div>
                                    <div class="modal-body" style="color:#666 !important;">
                                        <p><?php echo nl2br($comoAvaliar->texto); ?></p>
                                    </div>                                  
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <?php echo form_open('profissional/avaliar', array('id' => 'formCadastro', 'class' => 'bounceInLeft form', 'data-wow-duration' => '2s')); ?>
                                <input type="hidden" name="url" value="<?php echo $profissional->url; ?>">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label style="color:#ccc; font-family: 'Roboto Condensed';" >NOTA</label><br /><br />
                                        <?php for($i = 1; $i <= 5; $i++) { ?>
                                        <div class="radio-inline color_primary notaRadio">
                                            <label for="nota<?php echo $i; ?>" class="fa fa-star-o">
                                                <input id="nota<?php echo $i; ?>" type="radio" name="nota" value="<?php echo $i; ?>" class="hide" required>
                                            </label>
                                        </div>
                                        <?php } ?>
                                        <textarea required name="comentario" style="border:1px solid rgba(204, 204, 204, 0.5) !important;" class="form-control form" cols="80" rows="10" placeholder="Comentário"></textarea>
                                        <button class="btn btn_1">Enviar</button>
                                    </div>
                                </div>
                                <br/>
                            <?php echo form_close(); ?>
                        </div>
                    </div>  
                </div>
            </div>
        </div>

    </div>
</div>


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

