<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>
<?php echo form_open('restrita/categorias/inserirExplicativo'); ?>

<div class="col-sm-12">
    <input class="form-control" type="text" placeholder="Titulo" name="titulo" required>
</div>
<div class="col-sm-12">
    <input class="form-control" type="text" placeholder="Titulo do Cabeçalho" name="header_title">
</div>
<div class="col-sm-12">
    <input class="form-control" type="text" placeholder="Meta Description" name="meta_description">
</div>
<div class="col-sm-12">
    <div class="summernote"></div>
</div>
<div class="col-sm-3">
    <a href="<?php echo base_url("restrita/categorias/explicativos/$id_subcategoria"); ?>" class="btn btn-success btn-block">Cancelar</a>
</div>
<div class="col-sm-3">
    <button type="submit" class="btn btn-warning btn-block">Cadastrar</button>
</div>
<input type="hidden" name="status" value="a" />
<input type="hidden" name="id_subcategoria" value="<?= $id_subcategoria ?>" />
<textarea name="texto" id="texto" class="hide"></textarea>
<?php echo form_close(); ?>
<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
