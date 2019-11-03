<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>
<?php echo form_open('restrita/planos/inserirOpcao'); ?>
<div class="col-sm-12">
    <input class="form-control" type="text" placeholder="Nome da opção" name="opcao" required>
</div>
<div class="col-sm-12">
    <input class="form-control" type="text" placeholder="Ordem de exibição" name="ordem" required>
</div>
<div class="col-sm-3">
    <a href="<?php echo base_url('restrita/planos/opcoes'); ?>" class="btn btn-success btn-block">Cancelar</a>
</div>
<div class="col-sm-3">
    <button type="submit" class="btn btn-warning btn-block">Cadastrar</button>
</div>
<?php echo form_close(); ?>
<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
