<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>
<?php echo form_open_multipart('restrita/feedback/alterar'); ?>
<input type="hidden" name="id" value="<?php echo $feedback->id; ?>">
<input type="hidden" name="fotoAntiga" value="<?php echo $feedback->foto; ?>">
<div class="col-sm-12">
    <input class="form-control" type="text" placeholder="Nome" name="nome" value="<?php echo $feedback->nome; ?>" required>
</div>
<div class="col-sm-12">
    <textarea class="form-control" placeholder="Texto" name="texto" required><?php echo $feedback->texto; ?></textarea>
</div>
<?php if($feedback->foto) { ?>
<div class="col-sm-12">
    <img class="pop" style="cursor: pointer;" title="Visualizar" src="<?php echo base_url('uploads/feedback/' . $feedback->foto); ?>" alt="">
    <a href="#" class="excluirFoto" data-campo="id" data-id="<?php echo $feedback->id; ?>" data-tabela="feedback" data-foto="<?php echo DIR_FEEDBACK . $feedback->foto; ?>" data-toggle="modal" data-target="#modalDelete">Excluir</a>
</div>
<?php } ?>
<div class="col-sm-12">
    <input class="form-control" type="file" placeholder="Foto" name="feedback">
</div>
<div class="col-sm-3">
    <a href="<?php echo base_url('restrita/feedback'); ?>" class="btn btn-success btn-block">Cancelar</a>
</div>
<div class="col-sm-3">
    <button type="submit" class="btn btn-warning btn-block">Alterar</button>
</div>
<?php echo form_close(); ?>
<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
