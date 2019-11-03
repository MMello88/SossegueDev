<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>
<?php echo form_open_multipart('restrita/usuarios/inserir', array('id' => 'formCadastro')); ?>
<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#dados">Dados pessoais</a></li>
  <li><a data-toggle="tab" href="#endereco">Endereço</a></li>
</ul>
<br/>
<div class="tab-content" style="overflow: hidden;">
  <div id="dados" class="tab-pane fade in active">
    <div class="col-sm-12">
        <input class="form-control" type="text" placeholder="Nome" name="nome" required>
    </div>
    <div class="col-sm-12">
        <input class="form-control" type="email" placeholder="Email" name="email" required>
    </div>
    <div class="col-sm-12">
        <input class="form-control" type="text" placeholder="CPF / CNPJ" name="cpf" required>
    </div>
    <div class="col-sm-12">
        <input class="form-control" type="text" placeholder="Telefone" name="telefone" id="telefone">
    </div>
    <div class="col-sm-12">
        <input class="form-control" type="text" placeholder="Celular" name="celular" id="celular">
    </div>
    <div class="col-sm-12">
        <input class="form-control" type="password" placeholder="Senha" name="senha" id="senha" required>
    </div>
    <div class="col-sm-12">
        <input class="form-control" type="password" placeholder="Confirma senha" id="confSenha" required>
    </div>
  </div>
  <div id="endereco" class="tab-pane fade">
    <div class="col-sm-12">
        <select name="estado" id="estado" class="form-control" required>
            <option value="" class="option_cad">ESTADO</option>
            <?php foreach($estados as $estado) { ?>
            <option value="<?php echo $estado->id; ?>" class="option_cad"><?php echo $estado->nome; ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="col-sm-12">
        <select name="cidade" id="cidade" class="form-control" required>
            <option value="" class="option_cad">CIDADE</option>
        </select>
    </div>
    <div class="col-sm-12">
        <input class="form-control" type="text" placeholder="Bairro" name="bairro">
    </div>
    <div class="col-sm-12">
        <input class="form-control" type="text" placeholder="Endereço" name="endereco">
    </div>
    <div class="col-sm-12">
        <input class="form-control" type="text" placeholder="Número" name="numero">
    </div>
    <div class="col-sm-12">
        <input class="form-control" type="text" placeholder="Complemento" name="complemento">
    </div>
  </div>
</div>
<div class="col-sm-3">
    <a href="<?php echo base_url('restrita/usuarios/listar'); ?>" class="btn btn-success btn-block">Cancelar</a>
</div>
<div class="col-sm-3">
    <button type="submit" class="btn btn-warning btn-block">Cadastrar</button>
</div>
<input type="hidden" name="status" value="a" />
<?php echo form_close(); ?>
<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
