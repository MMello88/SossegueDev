<?php include_once('includes/header.php'); ?>
<?php echo form_open_multipart('restrita/estaticas/alterar', array('id' => 'formCadastro')); ?>
<input type="hidden" name="id" value="<?php echo $pagina->id; ?>">
<div class="col-sm-12">
    <div class="summernote"><?php echo $pagina->texto; ?></div>
</div>
<div class="col-sm-12">
    <select name="status" class="form-control">
        <?php if($pagina->status === 'a') { ?>
        <option value="a" selected>Ativo</option>
        <option value="i">Inativo</option>
        <?php } else { ?>
        <option value="i" selected>Inativo</option>
        <option value="a">Ativo</option>
        <?php }?>
    </select>
</div>
<div class="col-sm-3">
    <a href="<?php echo base_url('restrita/home'); ?>" class="btn btn-success btn-block">Cancelar</a>
</div>
<div class="col-sm-3">
    <button type="submit" class="btn btn-warning btn-block" onclick="">Alterar</button>
</div>
<textarea name="texto" id="texto" class="hide"></textarea>
<?php echo form_close(); ?>
<?php include_once('includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
