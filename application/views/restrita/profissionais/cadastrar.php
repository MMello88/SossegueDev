<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>
<?php echo form_open_multipart('restrita/usuarios/inserirProfissional', array('id' => 'formCadastro')); ?>
<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#dados">Dados pessoais</a></li>
  <li><a data-toggle="tab" href="#endereco">Endereço</a></li>
  <li><a data-toggle="tab" href="#selecaoPlano">Categoria e plano</a></li>
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
        <input class="form-control" type="text" placeholder="Data de nascimento" name="nascimento" id="nascimento" required>
    </div>
    <div class="col-sm-12">
        <input class="form-control" type="text" placeholder="Telefone" name="telefone" id="telefone">
    </div>
    <div class="col-sm-12">
        <input class="form-control" type="text" placeholder="Celular" name="celular" id="celular">
    </div>
    <div class="col-sm-12">
        <textarea class="form-control" placeholder="Descrição" name="descricao"></textarea>
    </div>
    <?php foreach($links as $link) { ?>
    <div class="col-sm-12">
        <input class="form-control" type="text" placeholder="<?php echo $link->nome; ?>" name="links[<?php echo $link->id_links; ?>]">
    </div>
    <?php } ?>
    <div class="col-sm-12">
        <input class="form-control" type="password" placeholder="Senha" name="senha" id="senha" required>
    </div>
    <div class="col-sm-12">
        <input class="form-control" type="password" placeholder="Confirma senha" id="confSenha" required>
    </div>
    <div class="col-sm-12">
        <input class="form-control" type="file" placeholder="Foto" name="foto">
    </div>
  </div>
  <div id="endereco" class="tab-pane fade">
    <div class="col-sm-12">
        <select name="estado" id="estado" class="form-control">
            <option value="" class="option_cad">ESTADO</option>
            <?php foreach($estados as $estado) { ?>
            <option value="<?php echo $estado->id; ?>" class="option_cad"><?php echo $estado->nome; ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="col-sm-12">
        <select name="cidade" id="cidade" class="form-control">
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
  <div id="selecaoPlano" class="tab-pane fade">
    <div class="col-sm-12">
        <select name="categoria" id="categoria" class="form-control" required>
            <option value="" class="option_cad">CATEGORIA</option>
            <?php foreach($listaCategorias as $categoria) { ?>
            <option value="<?php echo $categoria->id_categoria; ?>" class="option_cad"><?php echo $categoria->categoria; ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="col-sm-12">
        <select name="subcategoria" id="subcategoria" class="form-control" required>
            <option value="" class="option_cad">SUB CATEGORIA</option>
        </select>
    </div>
    <div class="col-sm-12">
        <select name="plano" id="plano" class="form-control" required>
            <option value="" class="option_cad">PLANO</option>
            <?php foreach($planos as $plano) { ?>
            <option value="<?php echo $plano->id_plano; ?>" class="option_cad"><?php echo $plano->plano . ($plano->preco !== '0.00' ? ' R$' . $plano->preco : ''); ?></option>
            <?php } ?>
        </select>
    </div>
  </div>
</div>
<div class="col-sm-3">
    <a href="<?php echo base_url('restrita/usuarios/profissionais'); ?>" class="btn btn-success btn-block">Cancelar</a>
</div>
<div class="col-sm-3">
    <button type="submit" class="btn btn-warning btn-block">Cadastrar</button>
</div>
<input type="hidden" name="status" value="a" />
<?php echo form_close(); ?>
<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
