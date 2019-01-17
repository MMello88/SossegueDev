<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>
<?php echo form_open('restrita/cidades/inserir'); ?>
<div class="col-sm-12">
    <input class="form-control" type="text" placeholder="Nome da Cidade" name="nome" required>
</div>
<div class="col-sm-12">
    <input class="form-control" type="text" placeholder="Link" name="link" required>
</div>
<div class="col-sm-12">
    <p>Estados</p>
</div>
<div class="col-sm-12">
    <select name="estado">
        <option value="Selecione o estato" disabled selected></option>
        <?php foreach($estados as $estado) { ?>
        <option value="<?php echo $estado->id; ?>"><?php echo $estado->nome; ?></option>
        <?php } ?>
    </select>
</div>
<div class="col-sm-12">
    <p>Sattus</p>
</div>
<div class="col-sm-12">
    <select name="status">
        <option value="d" selected>Desativado</option>
        <option value="a">Ativado</option>
    </select>
</div>
<div class="clearfix"></div>

<div class="col-sm-3">
    <a href="<?php echo base_url('restrita/cidades/listar'); ?>" class="btn btn-success btn-block">Cancelar</a>
</div>
<div class="col-sm-3">
    <button type="submit" class="btn btn-warning btn-block">Cadastrar</button>
</div>
<input type="hidden" name="status" value="a" />
<?php echo form_close(); ?>
<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
