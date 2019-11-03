<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>
<?php echo form_open_multipart('restrita/usuarios/alterar', array('id' => 'formCadastro')); ?>
<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#dados">Dados pessoais</a></li>
  <li><a data-toggle="tab" href="#endereco">Endereço</a></li>
</ul>
<br/>
<div class="tab-content" style="overflow: hidden;">
  <div id="dados" class="tab-pane fade in active">
    <div class="col-sm-12">
        <input class="form-control" type="text" placeholder="Nome" name="nome" value="<?php echo $usuario->nome; ?>" required>
    </div>
    <div class="col-sm-12">
        <input class="form-control" type="email" placeholder="Email" name="email" value="<?php echo $usuario->email; ?>" required>
    </div>
    <div class="col-sm-12">
        <input class="form-control" type="text" placeholder="CPF / CNPJ" name="cpf" value="<?php echo $usuario->cpf; ?>" required>
    </div>
    <div class="col-sm-12">
        <input class="form-control" type="text" placeholder="Telefone" name="telefone" id="telefone" value="<?php echo $usuario->telefone; ?>">
    </div>
    <div class="col-sm-12">
        <input class="form-control" type="text" placeholder="Celular" name="celular" id="celular" value="<?php echo $usuario->celular; ?>">
    </div>
    <div class="col-sm-12">
        <input class="form-control" type="password" placeholder="Nova senha" name="senha" id="senha">
    </div>
    <div class="col-sm-12">
        <input class="form-control" type="password" placeholder="Confirme a nova senha" id="confSenha">
    </div>
  </div>
  <div id="endereco" class="tab-pane fade">
    <div class="col-sm-12">
        <select name="estado" id="estado" class="form-control">
            <option value="" class="option_cad">ESTADO</option>
            <?php foreach($estados as $estado) { ?>
            <option value="<?php echo $estado->id; ?>" class="option_cad" <?php if(isset($usuario->id_estado) && $estado->id === $usuario->id_estado) echo 'selected'; ?>><?php echo $estado->nome; ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="col-sm-12">
        <select name="cidade" id="cidade" class="form-control">
            <option value="" class="option_cad">CIDADE</option>
            <?php foreach($cidades as $cidade) { ?>
            <option value="<?php echo $cidade->id; ?>" class="option_cad" <?php if($cidade->id === $usuario->id_cidade) echo 'selected'; ?>><?php echo $cidade->nome; ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="col-sm-12">
        <input class="form-control" type="text" placeholder="Bairro" name="bairro" value="<?php echo $usuario->bairro; ?>">
    </div>
    <div class="col-sm-12">
        <input class="form-control" type="text" placeholder="Endereço" name="endereco" value="<?php echo $usuario->endereco; ?>">
    </div>
    <div class="col-sm-12">
        <input class="form-control" type="text" placeholder="Número" name="numero" value="<?php echo ((int)$usuario->numero === 0 ? '' : $usuario->numero); ?>">
    </div>
    <div class="col-sm-12">
        <input class="form-control" type="text" placeholder="Complemento" name="complemento" value="<?php echo $usuario->complemento; ?>">
    </div>
  </div>
</div>
<div class="col-sm-3">
    <a href="<?php echo base_url('restrita/usuarios/listar'); ?>" class="btn btn-success btn-block">Cancelar</a>
</div>
<div class="col-sm-3">
    <button type="submit" class="btn btn-warning btn-block">Alterar</button>
</div>
<input type="hidden" name="id_usuario" value="<?php echo $usuario->id_usuario; ?>" />
<input type="hidden" name="id_usuario_endereco" value="<?php echo $usuario->id_usuario_endereco; ?>" />
<?php echo form_close(); ?>
<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
