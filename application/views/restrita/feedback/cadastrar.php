<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>
<?php echo form_open_multipart('restrita/feedback/inserir'); ?>
<div class="col-sm-12">
    <input class="form-control" type="text" placeholder="Nome" name="nome" required>
</div>
<div class="col-sm-12">
    <textarea class="form-control" placeholder="Texto" name="texto" required></textarea>
</div>
<div class="col-sm-12">
    <input class="form-control" type="file" placeholder="Foto" name="feedback">
</div>
<div class="col-sm-3">
    <a href="<?php echo base_url('restrita/feedback'); ?>" class="btn btn-success btn-block">Cancelar</a>
</div>
<div class="col-sm-3">
    <button type="submit" class="btn btn-warning btn-block">Cadastrar</button>
</div>
<?php echo form_close(); ?>
<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
