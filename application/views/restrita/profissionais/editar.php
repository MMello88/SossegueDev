<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>
<?php echo form_open_multipart('restrita/usuarios/alterarProfissional', array('id' => 'formCadastro')); ?>
<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#dados">Dados pessoais</a></li>
  <li><a data-toggle="tab" href="#endereco">Endereço</a></li>
  <li><a data-toggle="tab" href="#selecaoPlano">Categoria e plano</a></li>
  <li><a data-toggle="tab" href="#relatorio">Relatório</a></li>
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
        <input class="form-control" type="text" placeholder="Data de nascimento" name="nascimento" id="nascimento" value="<?php echo $usuario->nascimento; ?>" required>
    </div>
    <div class="col-sm-12">
        <input class="form-control" type="text" placeholder="Telefone" name="telefone" id="telefone" value="<?php echo $usuario->telefone; ?>">
    </div>
    <div class="col-sm-12">
        <input class="form-control" type="text" placeholder="Celular" name="celular" id="celular" value="<?php echo $usuario->celular; ?>">
    </div>
    <div class="col-sm-12">
        <textarea class="form-control" placeholder="Descrição" name="descricao"><?php echo $usuario->descricao; ?></textarea>
    </div>
    <?php foreach($links as $link) { ?>
    <div class="col-sm-12">
        <input class="form-control" type="text" placeholder="<?php echo $link->nome; ?>" name="links[<?php echo $link->id_links; ?>]" value="<?php echo $usuario->link[$link->nome]; ?>">
    </div>
    <?php } ?>
    <div class="col-sm-12">
        <input class="form-control" type="password" placeholder="Nova senha" name="senha" id="senha">
    </div>
    <div class="col-sm-12">
        <input class="form-control" type="password" placeholder="Confirme a nova senha" id="confSenha">
    </div>
    <?php if($usuario->foto) { ?>
    <div class="col-sm-12">
        <img class="pop" style="cursor: pointer;" title="Visualizar" src="<?php echo base_url('uploads/profissionais/' . $usuario->foto); ?>" alt="">
        <a href="#" class="excluirFoto" data-campo="id_profissional" data-id="<?php echo $usuario->id_profissional; ?>" data-tabela="profissional" data-foto="<?php echo DIR_PROFISSIONAL . $usuario->foto; ?>" data-toggle="modal" data-target="#modalDelete">Excluir</a>
    </div>
    <?php } ?>
    <div class="col-sm-12">
        <input class="form-control" type="file" placeholder="Foto" name="foto">
    </div>
  </div>
  <div id="endereco" class="tab-pane fade">
    <div class="col-sm-12">
        <select name="estado" id="estado" class="form-control">
            <option value="" class="option_cad">ESTADO</option>
            <?php foreach($estados as $estado) { ?>
            <option value="<?php echo $estado->id; ?>" class="option_cad" <?php if($estado->id === $usuario->id_estado) echo 'selected'; ?>><?php echo $estado->nome; ?></option>
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
  <div id="selecaoPlano" class="tab-pane fade">
    <div class="col-sm-12">
        <select name="categoria" id="categoria" class="form-control" required>
            <option value="" class="option_cad">CATEGORIA</option>
            <?php foreach($listaCategorias as $categoria) { ?>
            <option value="<?php echo $categoria->id_categoria; ?>" class="option_cad" <?php if($categoria->id_categoria === $usuario->id_categoria) echo 'selected'; ?>><?php echo $categoria->categoria; ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="col-sm-12">
        <select name="subcategoria" id="subcategoria" class="form-control" required>
            <option value="" class="option_cad">SUB CATEGORIA</option>
            <?php foreach($subcategorias as $subcategoria) { ?>
            <option value="<?php echo $subcategoria->id_subcategoria; ?>" class="option_cad" <?php if($subcategoria->id_subcategoria === $usuario->id_subcategoria) echo 'selected'; ?>><?php echo $subcategoria->subcategoria; ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="col-sm-12">
        <select name="plano" id="plano" class="form-control" required>
            <option value="" class="option_cad">PLANO</option>
            <?php foreach($planos as $plano) { ?>
            <option value="<?php echo $plano->id_plano; ?>" class="option_cad" <?php if($plano->id_plano === $usuario->id_planos) echo 'selected'; ?>><?php echo $plano->plano . ($plano->preco !== '0.00' ? ' R$' . $plano->preco : ''); ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="col-sm-12">
        <input class="form-control" type="text" placeholder="Total" name="total" value="<?php echo $usuario->total; ?>" required>
    </div>
    <div class="col-sm-12">
        <input class="form-control" type="text" placeholder="Data de Inicio" name="inicio" id="inicio" value="<?php echo $usuario->inicio; ?>" required>
    </div>
    <div class="col-sm-12">
        <input class="form-control" type="text" placeholder="Data de Término" name="fim" id="fim" value="<?php echo $usuario->fim; ?>" required>
    </div>
    <div class="col-sm-12">
        <input class="form-control" type="text" placeholder="Transação Pagseguro" name="id_transacao" value="<?php echo $usuario->id_transacao; ?>">
    </div>
    <div class="col-sm-12">
        <select name="situacao" class="form-control">
            <option value="" class="option_cad">SITUAÇÃO DO PAGAMENTO</option>
            <option value="a" <?php if($usuario->situacao === 'a') echo 'selected'; ?>>Aprovado</option>
            <option value="p" <?php if($usuario->situacao === 'p') echo 'selected'; ?>>Pendente</option>
            <option value="c" <?php if($usuario->situacao === 'c') echo 'selected'; ?>>Cancelado</option>
        </select>
    </div>
    <div class="col-sm-12">
        <select name="status" class="form-control">
            <?php if($usuario->status === 'a') { ?>
            <option value="a" selected>Ativo</option>
            <option value="i">Inativo</option>
            <?php } else { ?>
            <option value="i" selected>Inativo</option>
            <option value="a">Ativo</option>
            <?php }?>
        </select>
    </div>
  </div>
  <div id="relatorio" class="tab-pane fade">
    <div style="overflow: hidden; min-width: 600px;">
        <div class="col-sm-6">
            <select name="mes" id="mes" class="form-control">
                <option value="" class="option_cad">Mês</option>
                <?php foreach($meses as $key => $mes) { ?>
                <option <?php if($key + 1 === (int) date('m')) echo 'selected'; ?> value="<?php echo ($key + 1 < 10 ? '0' . $key + 1 : $key + 1); ?>"><?php echo $mes; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="col-sm-6">
            <select name="ano" id="ano" class="form-control">
                <option value="" class="option_cad">Ano</option>
                <?php foreach(range(2016, date('Y')) as $ano) { ?>
                <option <?php if($ano === (int) date('Y')) echo 'selected'; ?> value="<?php echo $ano; ?>"><?php echo $ano; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="col-sm-12">
              <script type="text/javascript" src="//www.gstatic.com/charts/loader.js"></script>
              <div id="chart_div"></div>
        </div>
    </div>
  </div>
</div>
<div class="col-sm-3">
    <a href="<?php echo base_url('restrita/usuarios/profissionais'); ?>" class="btn btn-success btn-block">Cancelar</a>
</div>
<div class="col-sm-3">
    <button type="submit" class="btn btn-warning btn-block">Alterar</button>
</div>
<input type="hidden" name="id_usuario" value="<?php echo $usuario->id_usuario; ?>" />
<input type="hidden" name="id_profissional" id="id_profissional" value="<?php echo $usuario->id_profissional; ?>" />
<input type="hidden" name="id_profissional_fatura" value="<?php echo $usuario->id_profissional_fatura; ?>" />
<input type="hidden" name="id_usuario_endereco" value="<?php echo $usuario->id_usuario_endereco; ?>" />
<input type="hidden" name="fotoAntiga" value="<?php echo $usuario->foto; ?>" />
<?php echo form_close(); ?>
<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
