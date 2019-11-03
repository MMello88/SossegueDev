<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>
<?php echo form_open('restrita/categorias/alterarSubcategoria'); ?>
<div class="col-sm-12">
    <select name="id_categoria" class="form-control" required>
        <option value="">Categoria</option>
        <?php foreach($listaCategorias as $categoria) { ?>
        <option value="<?php echo $categoria->id_categoria; ?>" <?php if($subcategoria->id_categoria === $categoria->id_categoria) echo 'selected="selected"' ?>><?php echo $categoria->categoria; ?></option>
        <?php } ?>
    </select>
</div>
<div class="col-sm-12">
    <input class="form-control" type="text" placeholder="Nome da subcategoria" name="subcategoria" value="<?php echo $subcategoria->subcategoria; ?>" required>
</div>
<div class="col-sm-3">
    <a href="<?php echo base_url('restrita/categorias/subcategorias'); ?>" class="btn btn-success btn-block">Cancelar</a>
</div>
<div class="col-sm-3">
    <button type="submit" class="btn btn-warning btn-block">Alterar</button>
</div>
<input type="hidden" name="id" value="<?php echo $subcategoria->id_subcategoria; ?>" />
<?php echo form_close(); ?>
<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
