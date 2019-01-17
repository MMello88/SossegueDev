<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>
<?php echo form_open('restrita/planos/alterarOpcao'); ?>
<div class="col-sm-12">
    <input class="form-control" type="text" placeholder="Nome da opção" name="opcao" value="<?php echo $opcao->opcao; ?>" required>
</div>
<div class="col-sm-12">
    <input class="form-control" type="text" placeholder="Ordem" name="ordem" value="<?php echo $opcao->ordem; ?>" required>
</div>
<div class="col-sm-3">
    <a href="<?php echo base_url('restrita/planos/opcoes'); ?>" class="btn btn-success btn-block">Cancelar</a>
</div>
<div class="col-sm-3">
    <button type="submit" class="btn btn-warning btn-block">Alterar</button>
</div>
<input type="hidden" name="id" value="<?php echo $opcao->id_planos_opcoes; ?>" />
<?php echo form_close(); ?>
<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
