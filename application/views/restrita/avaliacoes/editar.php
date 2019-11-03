<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>
<?php echo form_open('restrita/avaliacoes/alterar'); ?>
<div class="col-sm-5">
    <div class="form-group">
       <label>Profissional</label>
        <input class="form-control" type="text" value="<?php echo $avaliacao->profissional; ?>" disabled>
    </div>
</div>
<div class="col-sm-5">
    <div class="form-group">
       <label>Usuário</label>
        <input class="form-control" type="text" value="<?php echo $avaliacao->usuario; ?>" disabled>
    </div>
</div>
<div class="col-sm-2">
    <div class="form-group">
       <label>Nota</label>
        <input class="form-control" type="text" value="<?php echo $avaliacao->nota; ?>" disabled>
    </div>
</div>
<div class="col-sm-12">
    <input class="form-control" placeholder="Data da avaliação" type="text" name="data" value="<?php echo convertData($avaliacao->data, 'mysql', 'html'); ?>" required>
</div>
<div class="col-sm-12">
    <textarea class="form-control" placeholder="Comentário" name="comentario" rows="10"><?php echo $avaliacao->comentario; ?></textarea>
</div>
<div class="col-sm-12">
    <textarea class="form-control" placeholder="Resposta" name="resposta" rows="10"><?php echo $avaliacao->resposta; ?></textarea>
</div>
<div class="col-sm-3">
    <a href="<?php echo base_url('restrita/avaliacoes'); ?>" class="btn btn-success btn-block">Cancelar</a>
</div>
<div class="col-sm-3">
    <button type="submit" class="btn btn-warning btn-block">Alterar</button>
</div>
<input type="hidden" name="id_nota" value="<?php echo $avaliacao->id_nota; ?>" />
<?php echo form_close(); ?>
<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>

