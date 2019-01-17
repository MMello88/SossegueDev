<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>
<?php echo form_open('restrita/cidades/alterar'); ?>
<div class="col-sm-12">
    <input class="form-control" type="text" placeholder="Nome da Cidade" name="nome" value="<?php echo $cidade->nome; ?>" required>
</div>
<div class="col-sm-12">
    <input class="form-control" type="text" placeholder="Link" name="link" value="<?php echo $cidade->link; ?>" required>
</div>

<div class="col-sm-12">
    <p>Estados</p>
</div>
<div class="col-sm-12">
    <select name="estado">
        <option value="Selecione o estato" disabled selected></option>
    <?php foreach($estados as $estado) { ?>
        <?php if($estado->id == $cidade->estado) { ?>
        <option value="<?php echo $estado->id; ?>" selected><?php echo $estado->nome; ?></option>
        <?php } else { ?>
        <option value="<?php echo $estado->id; ?>"><?php echo $estado->nome; ?></option>
        <?php } ?>
    <?php } ?>
    </select>
</div>
<div class="col-sm-12">
    <p>Sattus</p>
</div>
<div class="col-sm-12">
    <select name="status">
        <option value="d" <?php if($cidade->status == 'd') echo 'selected'; ?>>Desativado</option>
        <option value="a" <?php if($cidade->status == 'a') echo 'selected'; ?>>Ativado</option>
    </select>
</div>
<div class="clearfix"></div>
<div class="col-sm-3">
    <a href="<?php echo base_url('restrita/cidades/listar'); ?>" class="btn btn-success btn-block">Cancelar</a>
</div>
<div class="col-sm-3">
    <button type="submit" class="btn btn-warning btn-block">Alterar</button>
</div>
<input type="hidden" name="id" value="<?php echo $cidade->id; ?>" />
<?php echo form_close(); ?>
<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
