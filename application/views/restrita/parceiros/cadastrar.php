<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>
<?php echo form_open_multipart('restrita/parceiros/inserir'); ?>
<div class="col-sm-12">
    <input class="form-control" type="text" placeholder="Nome do parceiro" name="parceiro" required>
</div>
<div class="col-sm-12">
    <input class="form-control" type="file" placeholder="Foto" name="fotoparceiro">
</div>
<div class="col-sm-12">
    <input class="form-control" type="text" placeholder="Link" name="link">
</div>
<div class="col-sm-3">
    <a href="<?php echo base_url('restrita/parceiros'); ?>" class="btn btn-success btn-block">Cancelar</a>
</div>
<div class="col-sm-3">
    <button type="submit" class="btn btn-warning btn-block">Cadastrar</button>
</div>
<?php echo form_close(); ?>
<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
