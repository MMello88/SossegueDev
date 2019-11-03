<?php include_once(APPPATH . 'views/restrita/includes/headerOs.php'); ?>

<style type="text/css">
    .Buttom-Link{
        background-color: #fff;
        border: none;
    }
    .Button {
        -moz-box-shadow:inset 0px -3px 7px 0px #3dc21b;
        -webkit-box-shadow:inset 0px -3px 7px 0px #3dc21b;
        box-shadow:inset 0px -3px 7px 0px #3dc21b;
        background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #44c767), color-stop(1, #5cbf2a));
        background:-moz-linear-gradient(top, #44c767 5%, #5cbf2a 100%);
        background:-webkit-linear-gradient(top, #44c767 5%, #5cbf2a 100%);
        background:-o-linear-gradient(top, #44c767 5%, #5cbf2a 100%);
        background:-ms-linear-gradient(top, #44c767 5%, #5cbf2a 100%);
        background:linear-gradient(to bottom, #44c767 5%, #5cbf2a 100%);
        filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#44c767', endColorstr='#5cbf2a',GradientType=0);
        background-color:#44c767;
        -moz-border-radius:3px;
        -webkit-border-radius:3px;
        border-radius:3px;
        border:1px solid #18ab29;
        display:inline-block;
        cursor:pointer;
        color:#ffffff;
        font-family:Arial;
        font-size:17px;
        padding:9px 23px;
        text-decoration:none;
        text-shadow:0px 1px 0px #2f6627;
    }
    .Button:hover {
        background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #5cbf2a), color-stop(1, #44c767));
        background:-moz-linear-gradient(top, #5cbf2a 5%, #44c767 100%);
        background:-webkit-linear-gradient(top, #5cbf2a 5%, #44c767 100%);
        background:-o-linear-gradient(top, #5cbf2a 5%, #44c767 100%);
        background:-ms-linear-gradient(top, #5cbf2a 5%, #44c767 100%);
        background:linear-gradient(to bottom, #5cbf2a 5%, #44c767 100%);
        filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#5cbf2a', endColorstr='#44c767',GradientType=0);
        background-color:#5cbf2a;
    }
    .Button:active {
        position:relative;
        top:1px;
    }

    .list-unstyled, .list-inline {
        padding-top: 15px;
    }
    .list-unstyled{
        padding-bottom: 5px;
    }

    .img-responsive {
        border-radius: 3px;
        padding-right: 5px;
    }

    .team-box-cont {
        border-bottom: 1px solid #CCC;
    }
    .team-box {
        background: #FFF;
        color: #000;
        border: 1px solid #CCC;
        border-radius: 3px;
        padding-bottom: 15px;
        padding-top: 15px;
        margin-bottom: 15px;
    }
</style>

<div class="modal fade" id="modalLogin" tabindex="-1" role="dialog" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></div>
            <div class="modal-body">
                <div class="text-center">
                    <h4 class="modal-title" id="myModalLabel">Salve ou Cancele seu Pedido de Orcamento para navegar pelo Menu!</h4>
                </div>
            </div>
        </div>
    </div>
</div>
<h1><?php echo $this->session->userdata('idOs2'); ?></h1>

<?php echo form_open('restrita/pedido/actionPedidoOrcamento', array('id' => 'formOs', 'data-wow-duration' => '2s')); ?>

<div class="col-sm-12 form-inline ">
    PEDIDO NRO:
    <input class="form-control" style="text-align:center"type="number" placeholder="Nro do Pedido" name="nro" id="nro" value="<?php echo $nro; ?>" disabled> 
</div>
<div class="col-sm-12">
    <input class="form-control" type="text" placeholder="Nome Solicitante" name="nome_solicitante" value="<?php echo $user[0]->nome; ?>" disabled>
</div>
<div class="col-sm-12">
    <select name="prioridade" id="prioridade" class="form-control" required>
        <option value="" class="option_cad">PRIORIDADE</option>
        <option value="u" class="option_cad">Urgente</option>
        <option value="h" class="option_cad">Para Hoje</option>
        <option value="s" class="option_cad">Para essa Semana</option>
        <option value="m" class="option_cad">Para esse Mes</option>
    </select> 
    <script type="text/javascript">document.getElementById("prioridade").value = "<?php echo $pedidosOrcamentos->prioridade; ?>";</script>
</div>
<div class="col-sm-4">
    <h5><a class="btn btn-info" href="<?php echo base_url('restrita/pedido/' . $this->session->userdata('url')); ?>/<?php echo $idPedidoOrcamento; ?>"><font color="#333">Adicionar mais serviços</font></a></h5>
</div>
<?php if ($pedidos) { ?>
    <div class="col-sm-12"> 
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="col-xs-12 col-md-5">
                    <h3><font color="#aaa">Descrição do Produto</font></h3>
                </div>
                <div class="col-xs-12 col-md-3">
                    <h3><font color="#aaa">Quantidade</font></h3>
                </div>
                <div class="col-xs-12 col-md-3">
                    <h3><font color="#aaa"></font></h3>
                </div>
            </div>
        </div>

        <input type ="hidden" required>
        <div class="row" style="padding-left: 15px; padding-right: 15px;">

            <?php $idNumber = 0; ?>
            <?php foreach ($pedidos as $pedido) { ?> <!-- forecah -->
                <?php $idNumber += 1; ?>
                <div class="col-xs-12 col-md-12 team-box">
                    <div class="col-xs-12 col-md-6">
                        <div class="row">
                            <div class="col-xs-5 col-md-3">
                                <img src="<?php echo base_url('assets/media/categoria_servico/' . $pedido->Servico->categoria->imagem); ?>" class="img-responsive" alt="Rack" height="100" width="100" align="left">
                            </div>
                            <div class="col-xs-7 col-md-9">
                                <ul class="list-unstyled">
                                    <li><strong><?php echo $pedido->Servico->categoria->descricao; ?></strong></li>
                                    <li>Categoria <?php echo $pedido->Servico->categoria->subcategoria->descricao; ?></li>
                                    <li>Servico de <?php echo $pedido->Servico->descricao; ?></li>
                                    <?php foreach ($pedido->filtros as $filtro) { ?> <!-- forecah -->
                                        <?php if (!empty($filtro->filtro)) { ?>
                                            <li><?php echo $filtro->pergunta . ': ' . $filtro->filtro; ?></li>
                                        <?php } else { ?>
                                            <li><?php echo $filtro->pergunta . ': ' . $filtro->valor; ?></li>
                                            <?php
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-2" style="padding-top: 15px;">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon btn menos_quantidade" campo="qntd<?php echo $pedido->id_pedido; ?>"><font style="font-size:20px;"><strong> - </strong></font></div>
                                <input type="hidden" id="id_pedido" name="id_pedido" value="<?php echo $pedido->id_pedido; ?>" required>
                                <input type="hidden" id="id_servico" name="id_servico" value="<?php echo $pedido->id_servico; ?>" required>
                                <input type="text" id="qntd<?php echo $pedido->id_pedido; ?>" name="qntd[<?php echo $pedido->id_pedido; ?>]" class="form-control"  placeholder="Qtde" style="text-align: center;" value="<?php echo $pedido->qntd; ?> " maxlength="2" required>
                                <input type="hidden" id="descricao" name="descricao" value="Servico: <?php echo $pedido->Servico->descricao; ?>, Categoria: <?php echo $pedido->Servico->categoria->descricao ?>, Subcategoria:<?php echo $pedido->Servico->categoria->subcategoria->descricao; ?>" required>
                                <div class="input-group-addon btn mais_quantidade" campo="qntd<?php echo $pedido->id_pedido; ?>"><font style="font-size:20px;"><strong> + </strong></font></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <ul class="list-inline">
                            <li><a href="<?php echo base_url('restrita/ordem/actionRemoverPedido/' . $pedido->id_pedido); ?>"><font face="Arial" size="3" color="#046C00">Remover Pedido</font></a></li>
                        </ul>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="col-sm-12">
                <h5>NENHUM PEDIDO ADICIONADO! ADICIONE PEDIDOS!</h5>
            </div>
        <?php } ?>

        <?php if ($user[0]->id_tipo_usuario == 5) { ?>
            <div class="col-sm-12">
                <select name="setor" id="setor" class="form-control" required>
                    <option value="" class="option_cad">SETOR</option>
                    <?php foreach ($setores as $setor) { ?>
                        <option value="<?php echo $setor->nome; ?>" class="option_cad"><?php echo $setor->nome; ?></option>
                    <?php } ?>
                </select> 
            </div>
        <?php } else { ?>
            <div class="col-sm-12">
                <input class="form-control" name="setor_mostrar" placeholder="<?php echo $setor; ?>"  value="" disabled>
                <input class="form-control" type="hidden" name="setor" id="setor"   value="<?php echo $setor; ?>" >
            </div>
        <?php } ?>
        <div class="col-sm-12">
            <input class="form-control" type="text" placeholder="Normas (Ex.: NR10/NR15/NR35)" name="normas" id="normas" value="<?php echo $pedidosOrcamentos->normas; ?>" >
        </div>
        <div class="col-sm-12">
            <input class="form-control" type="text" placeholder="Qualificacoes" name="qualificacoes" id="qualificacoes" value="<?php echo $pedidosOrcamentos->qualificacoes; ?>" >
        </div>
        <input type="hidden" id="id_pedido_de_orcamento" name="id_pedido_de_orcamento" value="<?php echo $pedidosOrcamentos->id_pedido_de_orcamento; ?>" required>

        <?php if ($pedidos) { ?>
            <div class="col-sm-3">
                <button type='submit' form="formOs" value='Submit' class="btn btn-success btn-block">Salvar</button>
            </div>
        <?php } ?>
        <?php echo form_close(); ?>
        <div class="col-sm-3">
            <a class="btn btn-success btn-block" href="<?php echo base_url('restrita/pedido/cancelar_edicao'); ?>">Cancelar</a>
        </div>
        <div class="col-sm-3">
            <a class="btn btn-success btn-block" href="<?php echo base_url('restrita/pedido/cancelar'); ?>">Cancelar Pedido</a>
        </div>
    </div>
</div>
<?php include_once("analyticstracking.php"); ?>
<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>