<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>

<style type="text/css">
    .team-box {
        background: #FFF;
        color: #000;
        border: 1px solid #CCC;
        border-radius: 3px;
        padding-bottom: 10px;
        padding-top: 10px;
        margin-bottom: 10px;
        text-align: center;
    }

    .meuBloco{
        visibility: hidden;
    }

    .meuInput{
    }
</style>

<ul id="myTabs" class="nav nav-tabs">
    <li><a data-toggle="tab" href="#dados">Dados</a></li>
    <li><a data-toggle="tab" href="#enderecoTag">Endereco</a></li>
    <li><a data-toggle="tab" href="#senha">Alterar Senha</a></li>
    <li><a data-toggle="tab" href="#setores">Setores</a></li>
</ul>
<br/>
<div class="tab-content" style="overflow: hidden;">
    <div id="dados" class="tab-pane fade in active">
        <?php echo form_open('restrita/empresariais/atualizaDados', array('id' => 'formDados', 'class' => 'bounceInLeft form-horizontal', 'data-wow-duration' => '2s')); ?>
        <?php if ($this->session->userdata('form_validation')) { ?>
            <div class="col-sm-12">
                <div class="ui-widget">
                    <div class="ui-state-error ui-corner-all"> 
                        <span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
                        <strong>Alert:</strong> <?php echo $this->session->userdata('form_validation') ?>
                    </div>
                </div> 
            </div>
        <?php } ?>
        <div class="col-sm-12">
            <input class="form-control cnpj"  type="text" placeholder="CNPJ" name="cpf" id="cpf" value="<?php echo $dados[0]->cpf; ?> " data-origem="<?php echo $dados[0]->cpf; ?>" required  disabled>
        </div>
        <div class="col-sm-12">
            <input class="form-control"  type="text" placeholder="Nome Fantasia" name="nome" id="nome" value="<?php echo $dados[0]->nome; ?>"  data-origem="<?php echo $dados[0]->nome; ?>" required  disabled>
        </div>
        <div class="col-sm-12">
            <input class="form-control"  type="text" placeholder="Razao Social" name="razao" id="razao" value="<?php echo $dados[0]->razao; ?>" data-origem="<?php echo $dados[0]->razao; ?>"  required  disabled>
        </div>

        <div class="col-sm-12">
            <input class="form-control "  type="text" placeholder="Telefone (1)" name="telefone1" id="telefone1" value="<?php echo $dados[0]->telefone; ?>"  data-origem="<?php echo $dados[0]->telefone; ?>"  pattern="(\([1-9]{2}\))\s([9]{1})([0-9]{4})-([0-9]{4})|(\([1-9]{2}\))\s([1-8]{1})([0-9]{3})-([0-9]{4})$"  title="Preencha o campo * Telefone com seu celular ou telefone." disabled>
        </div>

        <div class="col-sm-12">
            <input class="form-control " type="text" placeholder="Telefone (2)" name="telefone2" id="telefone2" value="<?php echo $dados[0]->telefone2; ?>" data-origem="<?php echo $dados[0]->telefone2; ?>" pattern="(\([1-9]{2}\))\s([9]{1})([0-9]{4})-([0-9]{4})|(\([1-9]{2}\))\s([1-8]{1})([0-9]{3})-([0-9]{4})$"  title="Preencha o campo * Telefone com seu celular ou telefone." disabled>
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
    <div id="enderecoTag" class="tab-pane fade">
        <?php echo form_open('restrita/empresariais/atualizaEndereco', array('id' => 'formEnd')); ?>

        <div class="col-sm-12">
            <select name="estado" id="estado" class="form-control" disabled  data-origem="<?php echo $endereco[0]->id_estado; ?>">
                <option value="">ESTADO</option>
                <?php foreach ($estadosTotais as $estado) { ?>
                    <option value="<?php echo $estado->id; ?>" data-origem="<?php echo $estado->nome; ?>" <?php if ($estado->id === $endereco[0]->id_estado) echo 'selected="selected"'; ?> ><?php echo $estado->nome; ?></option>
                <?php } ?>
            </select> 
        </div>
        <div class="col-sm-12">
            <select name="cidade" id="cidade" class="form-control"  disabled="true" data-origem="<?php echo $endereco[0]->id_cidade; ?>">
                <option class="option_cad">CIDADE</option>
                <?php echo $cidadeTotais; ?>
            </select>
            </select>
        </div>
        <div class="col-sm-12">
            <input class="form-control"  type="text" placeholder="Bairro" name="bairro" id="bairro" value="<?php echo $endereco[0]->bairro; ?>"  data-origem="<?php echo $endereco[0]->bairro; ?>" required  disabled="true">
        </div>
        <div class="col-sm-12">
            <input class="form-control"  type="text" placeholder="Numero" name="numero" id="numero" value="<?php echo $endereco[0]->numero; ?>"  data-origem="<?php echo $endereco[0]->numero; ?>" required  disabled="true">
        </div>
        <div class="col-sm-12">
            <input class="form-control"  type="text" placeholder="Endereco" name="endereco" id="endereco" value="<?php echo $endereco[0]->endereco; ?>" data-origem="<?php echo $endereco[0]->endereco; ?>" required  disabled="true">
        </div>
        <div class="col-sm-12">
            <input class="form-control"  type="text" placeholder="Complemento" name="complemento" id="complemento" value="<?php echo $endereco[0]->complemento ?>" data-origem="<?php echo $endereco[0]->complemento; ?>" required  disabled="true">
        </div>

        <div class="col-sm-3">
            <input  class="btn btn-success btn-block" type="button" id="btnEditarEnd"  value="Editar"/>
        </div>

        <div class="col-sm-3">
            <input  class="btn btn-success btn-block  meuBloco"  form= "formEnd" type="submit" id="btnSalvarEnd"  value="Salvar" />
        </div>

        <div class="col-sm-3">
            <input  class="btn btn-success btn-block  meuBloco" type="button" id="btnCancelarEnd"  value="Cancelar" />
        </div>
        <?php echo form_close(); ?>
    </div>

    <div id="senha" class="tab-pane fade">
        <?php echo form_open('restrita/empresariais/atualizaSenha', array('id' => 'formSenha')); ?>

        <div class="col-sm-12">
            <input class="form-control"  type="password" placeholder="Senha" name="senha" id="senha" value="" required  disabled="true">
        </div>
        <div class="col-sm-12">
            <input class="form-control"  type="password" placeholder="Confirmar Senha" name="senhaconfirmada" id="senhaconfirmada" value="" required  disabled="true"> 
        </div>

        <div class="col-sm-3">
            <input  class="btn btn-success btn-block " type="button" id="btnEditarSenha"  value="Editar" />
        </div>

        <div class="col-sm-3">
            <input  class="btn btn-success btn-block   meuBloco" type="submit" id="btnSalvarSenha" form= "formSenha"  value="Salvar" />
        </div>

        <div class="col-sm-3">
            <input  class="btn btn-success btn-block  meuBloco" type="button" id="btnCancelarSenha"  value="Cancelar" />
        </div>
        <?php echo form_close(); ?>
    </div>

    <div id="setores" class="tab-pane fade" >

        <table id="" class="table table-striped">
            <thead>
                <tr>
                    <th>Setor</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>     
                <?php foreach ($setores as $s) { ?>
                    <tr>
                        <td>
                            <?php echo $s->nome; ?>
                        </td>
                        <td>
                            <a  href="<?php echo base_url('restrita/empresariais/excluir/' . $s->id_setor); ?>" class="btn btn-success btn-sm">-</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody> 
        </table> 
        <div class="row"> 
            <div class="col-sm-12">
                <button onclick="myFunction2()" class="btn btn-success btn-sm">+</button>
            </div>
        </div >

        <div id="myDIV" style="display:none">
            <?php echo form_open('restrita/empresariais/setor', array('id' => 'formSetor', 'data-wow-duration' => '2s')); ?>
            <h5>Preencha o Nome do Setor que deseja inserir:</br></h5>
            <input type="text" name="setor" id="setor" value=""></br></br>
            <div class="col-sm-">
                <button form="formSetor" class="btn btn-success btn-sm" >Salvar Setor</button>
            </div>
            <?php echo form_close(); ?>
        </div></br>

    </div>
</div>
<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
