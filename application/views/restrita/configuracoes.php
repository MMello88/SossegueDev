
<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>
<?php echo form_open_multipart('restrita/configuracoes/alterar', array('id' => 'formCadastro')); ?>
<ul class="nav nav-tabs">
  <?php foreach($this->data['confCategorias'] as $key => $categoria) { ?>
  <li class="<?php echo $key === 0 ? 'active' : ''; ?>"><a data-toggle="tab" href="#<?php echo str_replace(' ', '', $categoria->nome); ?>"><?php echo $categoria->nome; ?></a></li>
  <?php } ?>
</ul>
<br/>
<div class="tab-content" style="overflow: hidden;">
  <?php foreach($this->data['confCategorias'] as $key => $categoria) { ?>
  <div id="<?php echo str_replace(' ', '', $categoria->nome); ?>" class="tab-pane fade <?php echo $key === 0 ? 'in active' : ''; ?>">
      <?php foreach($categoria->configuracoes as $configuracao) { ?>
      <div class="row">
          <div class="col-sm-4">
              <input class="form-control" type="text" value="<?php echo $configuracao->campo; ?>" disabled />
          </div>
          <div class="col-sm-4">
              <textarea class="form-control" rows="1" name="valor[<?php echo $configuracao->id; ?>]"><?php echo $configuracao->valor; ?></textarea>
          </div>
          <div class="col-sm-4">
              <input class="form-control" type="text" name="complemento[<?php echo $configuracao->id; ?>]" value="<?php echo $configuracao->complemento; ?>" />
          </div>
      </div>
      <?php } ?>
  </div>
  <?php } ?>
</div>
<div class="col-sm-3">
    <a href="<?php echo base_url('restrita/home'); ?>" class="btn btn-success btn-block">Cancelar</a>
</div>
<div class="col-sm-3">
    <button type="submit" class="btn btn-warning btn-block">Alterar</button>
</div>
<?php echo form_close(); ?>
<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
