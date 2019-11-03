<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>
<?php echo form_open('restrita/planos/inserir'); ?>
<div class="col-sm-12">
    <input class="form-control" type="text" placeholder="Nome do plano" name="plano" required>
</div>
<div class="col-sm-12">
    <input class="form-control money" type="text" placeholder="Preço" name="preco" required>
</div>
<div class="col-sm-12">
    <input class="form-control money" type="text" placeholder="Trimestral / mês" name="trimestral" required>
</div>
<div class="col-sm-12">
    <input class="form-control money" type="text" placeholder="Semestral / mês" name="semestral" required>
</div>
<div class="col-sm-12">
    <input class="form-control money" type="text" placeholder="Anual / mês" name="anual" required>
</div>
<!--
<div class="col-sm-12">
    <input class="form-control" type="text" placeholder="Dias" name="dias">
</div>
-->
<?php foreach($opcoes as $opcao) { ?>
<div class="col-sm-4">
    <p><?php echo $opcao->opcao; ?></p>
</div>
<div class="col-sm-4">
    <select name="opcoes[<?php echo $opcao->id_planos_opcoes; ?>]">
        <option value="" selected>Não</option>
        <option value="s">Sim</option>
    </select>
</div>
<div class="col-sm-4">
    <input type="text" name="informacoes[<?php echo $opcao->id_planos_opcoes; ?>]" placeholder="Informação">
</div>
<div class="clearfix"></div>
<?php } ?>
<div class="col-sm-3">
    <a href="<?php echo base_url('restrita/planos/listar'); ?>" class="btn btn-success btn-block">Cancelar</a>
</div>
<div class="col-sm-3">
    <button type="submit" class="btn btn-warning btn-block">Cadastrar</button>
</div>
<input type="hidden" name="status" value="a" />
<?php echo form_close(); ?>
<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
