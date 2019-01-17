<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>
<?php if($fotos) { ?>
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
                            <a href="#" class="pop" data-src="<?php echo base_url('uploads/profissionais/'. $foto->foto); ?>" title="Visualizar"><i class="fa fa-eye"></i></a>
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
<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
