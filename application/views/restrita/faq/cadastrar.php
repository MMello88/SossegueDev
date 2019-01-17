<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>
<?php echo form_open('restrita/faq/inserir'); ?>
<div class="col-sm-12">
    <select name="tipo" class="form-control" required>
        <option value="">Tipo</option>
        <option value="u">Usuário</option>
        <option value="a">Anunciante</option>
    </select>
</div>
<div class="col-sm-12">
    <input class="form-control" type="text" placeholder="Pergunta" name="pergunta" required>
</div>
<div class="col-sm-12">
    <textarea class="form-control" placeholder="Resposta" rows="10" name="resposta"></textarea>
</div>
<div class="col-sm-3">
    <a href="<?php echo base_url('restrita/faq'); ?>" class="btn btn-success btn-block">Cancelar</a>
</div>
<div class="col-sm-3">
    <button type="submit" class="btn btn-warning btn-block">Cadastrar</button>
</div>
<?php echo form_close(); ?>
<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
