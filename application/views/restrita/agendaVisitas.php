<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>
<?php if(isset($cadastroIncompleto)) { ?>
<div class="alert alert-danger alert-dismissible" role="alert">
    Por favor, complete o cadastro com o endereço completo!
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php } ?>
<?php echo form_open('#', array('id' => 'formCadastro')); ?>
<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#dados">Agendamento de Visitas Técnicas</a></li>
  <li><a data-toggle="tab" href="#endereco">Agendamento Serviços</a></li>
</ul>
<br/>
<div class="tab-content" style="overflow: hidden;">
  <div id="emissao" class="tab-pane fade in active">
    <div class="col-sm-12">
        <input class="form-control" type="text" placeholder="Nome do Solicitante" name="nome_solicitante" id="nome_solicitante" value="" required>
    </div>
    <div class="col-sm-12">
        <input class="form-control" type="text" placeholder="Nome do Responsavel pela Aprovação" name="nome_responsavel" id="nome_responsavel" value="">
    </div>
    <div class="col-sm-12">
        <input class="form-control" type="text" placeholder="Descrição" name="descricao" id="descricao" value="" required>
    </div>
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
    

<div id="servicos" class="tab-pane fade">
    <div class="tab-content" style="overflow: hidden;">
        <div id="emissao" class="tab-pane fade in active">
        <div class="col-sm-12">
            <input class="form-control" type="text" placeholder="Nome do Solicitante" name="nome_solicitante" id="nome_solicitante" value="" required>
        </div>
        <div class="col-sm-12">
            <input class="form-control" type="text" placeholder="Nome do Responsavel pela Aprovação" name="nome_responsavel" id="nome_responsavel" value="">
        </div>
        <div class="col-sm-12">
            <input class="form-control" type="text" placeholder="Descrição" name="descricao" id="descricao" value="" required>
         </div>
     </div>
 </div>
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
</div>

<div class="col-sm-3">
    <a href="<?php echo base_url('restrita'); ?>" class="btn btn-success btn-block">Cancelar</a>
</div>
<div class="col-sm-3">
    <button type="submit" class="btn btn-warning btn-block">Enviar</button>
</div>
</div>
<?php echo form_close(); ?>
<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
