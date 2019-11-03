<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>
<ul class="nav nav-tabs">
  <?php if($usuario->plano !== 'Grátis') { ?>
  <li class="active"><a data-toggle="tab" href="#dados">Detalhes</a></li>
  <?php } ?>
  <li class="<?php if($usuario->plano === 'Grátis') echo 'active'; ?>"><a data-toggle="tab" href="#selecaoPlano">Informações do plano</a></li>
  <?php if($maxFotos || $fotos) { ?>
  <li><a data-toggle="tab" href="#fotos">Fotos</a></li>
  <?php } ?>
</ul>
<br/>
  <?php if($usuario->ativo) { ?>
   <?php echo form_open_multipart('restrita/dados/anuncio', array('id' => 'formCadastro')); ?>
   <?php } ?>
<div class="tab-content" style="overflow: hidden; padding-bottom: 70px;">
    <input type="hidden" name="fotoAntiga" value="<?php echo $usuario->foto; ?>" />
    <input type="hidden" name="id_profissional" value="<?php echo $usuario->id_profissional; ?>" />
    <?php if($usuario->plano !== 'Grátis') { ?>
    <div id="dados" class="tab-pane fade in active">
        <div class="col-sm-12">
            <textarea class="form-control" placeholder="Descrição" name="descricao" rows="10"><?php echo $usuario->descricao; ?></textarea>
        </div>
        <?php foreach($links as $link) { ?>
        <div class="col-sm-12">
            <input class="form-control" type="text" placeholder="<?php echo $link->nome; ?>" name="links[<?php echo $link->id_links; ?>]" value="<?php echo $usuario->link[$link->nome]; ?>">
        </div>
        <?php } ?>
    <?php } ?>
    <div id="selecaoPlano" class="tab-pane fade <?php if($usuario->plano === 'Grátis') echo 'in active'; ?>">
        <div class="col-sm-6">
            <select name="categoria" id="categoria" class="form-control" required>
                <option value="" class="option_cad">CATEGORIA</option>
                <?php foreach($listaCategorias as $categoria) { ?>
                <option value="<?php echo $categoria->id_categoria; ?>" class="option_cad" <?php if($categoria->id_categoria === $usuario->id_categoria) echo 'selected'; ?>><?php echo $categoria->categoria; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="col-sm-6">
            <select name="subcategoria" id="subcategoria" class="form-control" required>
                <option value="" class="option_cad">SUB CATEGORIA</option>
                <?php foreach($subcategorias as $subcategoria) { ?>
                <option value="<?php echo $subcategoria->id_subcategoria; ?>" class="option_cad" <?php if($subcategoria->id_subcategoria === $usuario->id_subcategoria) echo 'selected'; ?>><?php echo $subcategoria->subcategoria; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="col-sm-12">
            <input class="form-control" type="text" value="<?php echo $usuario->plano . ($usuario->total > 0 ? ' - R$' . $usuario->total : ''); ?>" disabled />
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Início</label>
                <input class="form-control" type="text" placeholder="Data de Inicio" name="inicio" id="inicio" value="<?php echo $usuario->inicio; ?>" disabled>
            </div>
        </div>
        <div class="col-sm-6">
           <div class="form-group">
                <label>Fim</label>
                <input class="form-control" type="text" placeholder="Data de Término" name="fim" id="fim" value="<?php echo $usuario->fim; ?>" disabled>
            </div>
        </div>
        <?php if($usuario->foto) { ?>
            <div class="col-sm-12">
                <img src="<?php echo base_url('uploads/profissionais/' . $usuario->foto); ?>" alt="">
                <a href="#" class="excluirFoto" data-campo="id_profissional" data-id="<?php echo $usuario->id_profissional; ?>" data-tabela="profissional" data-foto="<?php echo DIR_PROFISSIONAL . $usuario->foto; ?>" data-toggle="modal" data-target="#modalDelete">Excluir</a>
            </div>
        <?php } else {?>
        <div class="col-sm-12">
            <label for="fotoPerfil">Foto do perfil</label>
            <input class="form-control" type="file" placeholder="Foto" name="foto" id="fotoPerfil">
        </div>
        <?php } ?>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label>Situação do anúncio</label>
                <select name="situacao" class="form-control" disabled>
                   <?php if(!$usuario->transacao && $usuario->plano !== 'Grátis') { ?>
                   <option>Pagamento não realizado</option>
                   <?php } else if($usuario->ativo) { ?>
                    <option <?php if($usuario->situacao === 'a') echo 'selected'; ?>>Aprovado</option>
                    <option <?php if($usuario->situacao === 'p') echo 'selected'; ?>>Pendente</option>
                    <option <?php if($usuario->situacao === 'c') echo 'selected'; ?>>Cancelado</option>
                    <?php } else { ?>
                    <option>Expirado</option>
                    <?php } ?>
                </select>
                <?php if($usuario->plano !== 'Grátis' && !$usuario->transacao) { ?>
                <a href="<?php echo $usuario->link_pagamento; ?>" class="btn btn-warning btn-block">Pagar</a>
                <?php } ?>
            </div>
        </div>
        <?php if(!$usuario->ativo) { ?>
        <div class="col-sm-12">
            <a href="<?php echo base_url('restrita/dados/renovarAnuncio'); ?>" class="btn btn-success btn-block">Renovar Anúncio</a>
        </div>
        <?php } ?>
        <?php if(($usuario->plano === 'Grátis' || $usuario->plano === 'Básico') && $usuario->ativo) { ?>
        <div class="col-sm-12">
            <a href="<?php echo base_url('restrita/dados/renovarAnuncio'); ?>" class="btn btn-success btn-block">Upgrade do plano</a>
        </div>
        <?php } ?>
    </div>
    <div style="position: absolute; bottom: 10px; width: 100%; z-index: 999;">
        <div class="col-sm-3">
        <a href="<?php echo base_url('restrita/home'); ?>" class="btn btn-success btn-block">Cancelar</a>
    </div>
    <div class="col-sm-3">
        <button type="submit" class="btn btn-warning btn-block">Alterar</button>
    </div>
    </div>
    <?php echo form_close(); ?>
    <?php if($maxFotos || $fotos) { ?>
    <div id="fotos" class="tab-pane fade">
        <div class="block-flat">
            <div class="galeria gallery-cont editar">
            <?foreach($fotos as $key => $foto){?>
                <div class="item" data-id="<?php echo $foto->id_fotos; ?>">
                    <div class="photo">
                        <div class="img">
                            <img src="<?php echo base_url('uploads/profissionais/'. $foto->foto); ?>" alt="" data-id="<?php echo $foto->id_fotos; ?>" />
                            <input type="hidden" name="fotos[<?php echo $foto->id_fotos; ?>]" id="foto<?php echo $foto->id_fotos; ?>" value="<?php echo $foto->ordem; ?>" />
                            <div class="over">
                                <div class="func">
                                    <a href="#" title="Mover"><i class="fa fa-arrows"></i></a>
                                    <a href="#" class="excluirFotoGaleria" data-id="<?php echo $foto->id_fotos; ?>" data-foto="<?php echo $foto->foto; ?>" title="Excluir" data-target="#excluir" data-toggle="modal"><i class="fa fa-times"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }?>
            </div>
            <div class="modal fade" id="excluir" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></div>
                        <div class="modal-body">
                            <div class="text-center">
                                <div class="i-circle warning"><i class="fa fa-warning"></i></div>
                                <h4>Deseja realmente excluir a foto?</h4>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-info" data-dismiss="modal">Não</button>
                            <button type="button" class="btn btn-primary excluirFoto" id="btnExcluirFoto">Sim</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
        <div class="block-flat quadroFotos <?php if(!$maxFotos) echo 'hide'; ?>">
            <p class="text-center"><i>Use o quadro abaixo para inserir uma galeria de imagens. (Tamanho máximo <?php echo ini_get("upload_max_filesize")?>B por imagem)</i></p>
            <p class="text-center"><i>Fotos restantes: <strong id="maxFotos"><?php echo $maxFotos; ?></strong></i></p>
            <?php echo form_open_multipart('restrita/home/uploadAjax',array('class' => 'dropzone dz-clickable', 'id' => 'my-awesome-dropzone'))?>
                <input type="hidden" name="nome" id="nome" value="<?php echo $usuario->nome; ?>" />
                <input type="hidden" name="id" id="id" value="<?php echo $usuario->id_profissional; ?>" />
                <input type="hidden" id="maxFotos" value="<?php echo $maxFotos; ?>" />
            <?php echo form_close()?>
            <input type="hidden" id="maxFilesize" value="<?php echo str_replace('M','',ini_get("upload_max_filesize"))?>" />
        </div>
    </div>
</div>
<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
