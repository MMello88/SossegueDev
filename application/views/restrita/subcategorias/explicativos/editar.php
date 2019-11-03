<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>
<?php echo form_open('restrita/categorias/alterarExplicativo'); ?>

<div class="col-sm-12">
    <input class="form-control" type="text" placeholder="Titulo" value="<?php echo $explicativo->titulo; ?>" name="titulo" required>
</div>
<div class="col-sm-12">
    <input class="form-control" type="text" placeholder="Titulo do Cabeçalho" value="<?php echo $explicativo->header_title; ?>" name="header_title">
</div>
<div class="col-sm-12">
    <input class="form-control" type="text" placeholder="Meta Description" value="<?php echo $explicativo->meta_description; ?>" name="meta_description">
</div>
<div class="col-sm-12">
    <div class="summernote"><?php echo $explicativo->texto; ?></div>
</div>
<div class="col-sm-3">
    <a href="<?php echo base_url("restrita/categorias/explicativos/$id_subcategoria"); ?>" class="btn btn-success btn-block">Cancelar</a>
</div>
<div class="col-sm-3">
    <button type="submit" class="btn btn-warning btn-block">Alterar</button>
</div>
<input type="hidden" name="id_subcategoria" value="<?= $explicativo->id_subcategoria ?>" />
<input type="hidden" name="id_explicativos" value="<?= $explicativo->id_explicativos ?>" />
<textarea name="texto" id="texto" class="hide"></textarea>
<?php echo form_close(); ?>
<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
