<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>

<style type="text/css">

    .meuBloco{
        visibility: hidden;
    }
    .ui-widget p {
        margin-bottom: 2px;
    }
</style>

<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
</button>
</br>

<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#dados">Dados</a></li>
    <li><a data-toggle="tab" href="#senha">Alterar Senha</a></li>
</ul>

<br/>
<div class="tab-content" style="overflow: hidden;">

    <div id="dados" class="tab-pane fade in active">
        <?php echo form_open('restrita/visualizar/salvarDados', array('id' => 'formDados', 'class' => 'bounceInLeft form-horizontal', 'data-wow-duration' => '2s')); ?>
        <?php if ($this->session->userdata('form_validation')) { ?>
            <div class="col-sm-12">
                <div class="ui-widget">
                    <div class="ui-state-error ui-corner-all"> 
                        <span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
                      x  <strong>Alert:</strong> <?php echo $this->session->userdata('form_validation') ?>
                    </div>
                </div> 
            </div>
        <?php } ?>
        <div class="col-sm-12">
            <input class="form-control cpf" type="text" placeholder="CPF" name="cpf" id="cpf" value="<?php echo $dados[0]->cpf; ?>" data-origem="<?php echo $dados[0]->cpf; ?>" required disabled>
        </div>
        <div class="col-sm-12">
            <input class="form-control" type="text" placeholder="Nome" name="nome"  id="nome" value="<?php echo $dados[0]->nome; ?>" data-origem="<?php echo $dados[0]->nome; ?>" required disabled>
        </div>
        <div class="col-sm-12">
            <input class="form-control" type="text" placeholder="Email" name="email" id="email" value="<?php echo $dados[0]->email; ?>" data-origem="<?php echo $dados[0]->email; ?>" required disabled>
        </div>

        <?php if ($dados[0]->id_tipo_usuario == 6) { ?>

            <div class="col-sm-12">
                <input class="form-control" type="text" placeholder="Telefone" name="telefone" id="telefone" value="<?php echo $dados[0]->telefone; ?>" data-origem="<?php echo $dados[0]->telefone; ?>" pattern="(\([1-9]{2}\))\s([9]{1})([0-9]{4})-([0-9]{4})|(\([1-9]{2}\))\s([1-8]{1})([0-9]{3})-([0-9]{4})$"  title="Preencha o campo * Telefone com seu celular ou telefone." required disabled>
            </div>

        <?php } ?>
        <div class="col-sm-12"> 
            <select name="setor" id="setor" class="form-control" required disabled data-origem="<?php echo $user[0]->setor; ?>">
                <option value="" class="option_cad">SETOR</option>
                <?php foreach ($setores as $setor) { ?>
                    <option value="<?php echo $setor->nome; ?>" <?php if ($setor->nome == $user[0]->setor) echo "selected" ?> class="option_cad"><?php echo $setor->nome; ?></option>
                <?php } ?>
            </select> 
        </div>

        <div class="col-sm-3">
            <input  class="btn btn-success btn-block" type="button" id="btnEditarDados"  value="Editar" />
        </div>

        <div class="col-sm-3">
            <input  class="btn btn-success btn-block meuBloco" type="submit" id="btnSalvarDados" form= "formDados" value="Salvar" />
        </div>

        <div class="col-sm-3">
            <input  class="btn btn-success btn-block meuBloco" type="button" id="btnCancelarDados" value="Cancelar" />
        </div>
        <?php echo form_close(); ?>
    </div>

    <div id="senha" class="tab-pane fade">
        <?php echo form_open('restrita/visualizar/salvarSenha', array('id' => 'formSenha', 'class' => 'bounceInLeft form-horizontal', 'data-wow-duration' => '2s')); ?>
        <ul class="nav nav-tabs">
            <div class="col-sm-12">
                <input class="form-control" type="password" placeholder="Senha" name="senha" id="senha" value="" required disabled>
            </div>
            <div class="col-sm-12">
                <input class="form-control" type="password" placeholder="Confirmar Senha" name="senhaconfirmada" id="senhaconfirmada" value="" required disabled> 
            </div>



            <div class="col-sm-3">
                <input  class="btn btn-success btn-block" type="button" id="btnEditarSenha"  value="Editar" />
            </div>

            <div class="col-sm-3">
                <input  class="btn btn-success btn-block meuBloco" type="submit" id="btnSalvarSenha" form= "formSenha" value="Salvar" />
            </div>

            <div class="col-sm-3">
                <input  class="btn btn-success btn-block meuBloco" type="button" id="btnCancelarSenha" value="Cancelar" />
            </div>

            <?php echo form_close(); ?>
    </div>



</div>

<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
