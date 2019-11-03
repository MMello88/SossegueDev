<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>
<?php echo form_open('restrita/categorias/inserirSubcategoria'); ?>
<div class="col-sm-12">
    <select name="id_categoria" class="form-control" required>
        <option value="">Categoria</option>
        <?php foreach($listaCategorias as $categoria) { ?>
        <option value="<?php echo $categoria->id_categoria; ?>"><?php echo $categoria->categoria; ?></option>
        <?php } ?>
    </select>
</div>
<div class="col-sm-12">
    <input class="form-control" type="text" placeholder="Nome da subcategoria" name="subcategoria" required>
</div>
<div class="col-sm-3">
    <a href="<?php echo base_url('restrita/categorias/listar'); ?>" class="btn btn-success btn-block">Cancelar</a>
</div>
<div class="col-sm-3">
    <button type="submit" class="btn btn-warning btn-block">Cadastrar</button>
</div>
<input type="hidden" name="status" value="a" />
<?php echo form_close(); ?>
<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
