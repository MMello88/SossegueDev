<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>
<style type="text/css">

    .Button{
        color: #fff;
        background-color: #5cb85c;
        border:solid 2px;
        border-color: #4cae4c;
    }

    .Button:hover, .Button:focus, .Button.focus {
        color: #fff;
        background-color: #449d44;
        border-color: #398439;
    }

</style>
<script>
    var csfrData = '<?php echo $this->security->get_csrf_hash(); ?>';
</script>

<?php if ($this->session->flashdata('msg_confirmacao')) { ?>


    <div class="modal fade" id="modalLogin" tabindex="-1" role="dialog" style="margin-top: 80px">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">  <?php echo $this->session->flashdata('msg_confirmacao'); ?></h4>
                </div>
            </div>
        </div>
    </div>

<?php } ?>



<!-- myModalRemoverOs -->
<div class="modal fade" id="myModalRemoverOs" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?php echo form_open('restrita/pedidos_orcamentos/cancelar', array('id' => 'formCadastro', 'class' => 'bounceInLeft form-horizontal', 'data-wow-duration' => '2s')); ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Deletar Pedido</h4>
            </div>
            <div class="modal-body">
                Deseja deletar este Pedido?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info btn-sm" data-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-warning btn-sm">Remover Pedido</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="myModalOs" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Serviço selecionados</h4>
            </div>
            <div class="modal-body">
                <ul>
                    <li>
                        pedido 1
                    </li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info btn-sm" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<table id="minhaTabela" class="table table-striped">
    <thead>
        <tr>
            <th>Data</th>
            <th>Setor</th>
            <th>Nome Requerente/Nome Aprovador</th>
            <th>Pedidos de Orçamento</th>
            <th>Status dos Pedidos de Orçamento</th>
            <th>Prioridade</th>
            <th>Status do Orçamento</th>
            <th> </th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pedidosUsuarioSessao as $os) { ?>

            <tr>
                <td><?php echo date("d/m/Y", strtotime($os->data)); ?></br><?php echo $os->hora; ?></td>
                <td><?php echo $os->localidade; ?></td>
                <td><b>Requerente:</b><?php echo $os->nome_sol; ?></br>
                    <b>Aprovador:</b><?php echo $os->nome_aprov; ?>
                    <?php
                    if ($os->data_hora_alteracao != "") {
                        echo '</br><b>Alterado: </b><span title="' . date("d/m/Y H:i", strtotime($os->data_hora_alteracao)) . '">' . $os->nome_alteracao . "</span>";
                    }
                    ?>
                    <?php
                    if ($os->nome_exclusao != "") {
                        echo '</br><b>Excluído: </b>' . $os->nome_exclusao;
                    }
                    ?>
                </td>
                <td>Pedido Nro  <?php echo $os->nro_pedido_de_orcamento; ?> </td>
                <td><?php echo $os->status; ?>
                    <?php if ($os->status == 'Pendentes de Aprovação') { ?>
                        <div class="btn-group-sm btn-group-vertical" role="group" aria-label="...">

                            <a class="btn  btn-success btn-sm Button " href="<?php echo base_url('restrita/pedidos_orcamentos/aprovar/' . $os->id_pedido_de_orcamento); ?>">
                                <span class="glyphicon-class">Aprovar</span>
                            </a>
                            <a class="btn btn-primary btn-sm " href="<?php echo base_url('restrita/pedidos_orcamentos/recusar/' . $os->id_pedido_de_orcamento); ?>">
                                <span class="glyphicon-class">Não Aprovar</span>
                            </a>
                        </div>
                    <?php } ?>
                </td>

                <?php
                switch ($os->prioridade) {
                    case 'u':
                        $prioridade = 'Urgente';
                        break;
                    case 'h':
                        $prioridade = 'Para Hoje';
                        break;
                    case 's':
                        $prioridade = 'Para essa Semana';
                        break;
                    case 'm':
                        $prioridade = 'Para esse Mes';
                        break;
                }
                ?>
                <td><?php echo $prioridade; ?></td>

                <td>
                    <?php if ($user[0]->id_tipo_usuario != 4) { ?>
                        <select  class="statusOrc" id_pedido_de_orcamento="<?php echo $os->id_pedido_de_orcamento; ?>" data-status_orc="<?php echo $os->status_orc; ?>">
                            <option value=" " class="option_cad">Mudar Status</option>
                            <?php echo $this->geral->combo_status_orcamento($os->status_orc); ?>
                        </select>
                    <?php } ?>
                </td>
                <td>
                    <div class="btn-group-sm btn-group-vertical" role="group" aria-label="...">
                        <button  type="button" class="btn btn-sm Button" data-toggle="modal" data-target="#myModalOs" data-whatever="<?php echo $os->id_pedido_de_orcamento; ?>" >Ver Pedido de Oçamento </button>
                        <?php if ($os->status == 'Pendentes de Aprovação') { ?>
                     <a class="btn btn-warning" href="<?php echo base_url('restrita/pedidos_orcamentos/alterar/' . $os->id_pedido_de_orcamento); ?>">
                                <span class="glyphicon-class">Editar</span>
                            </a>
                        <?php } ?>
                    </div>
                </td>

                </div>
                </td>

            </tr>
            </td>
            </tr>
        <?php } ?>

        <?php foreach ($pedidosUsuarioReq as $os) { ?>
            <tr>
                <td><?php echo date("d/m/Y", strtotime($os->data)); ?></br><?php echo $os->hora; ?></td>
                <td><?php echo $os->localidade; ?></td>
                <td><b>Requerente:</b><?php echo $os->nome_sol; ?></br>
                    <b>Aprovador:</b><?php echo $os->nome_aprov; ?>
                    <?php
                    if ($os->data_hora_alteracao != "") {
                        echo '</br><b>Alterado: </b><span title="' . date("d/m/Y H:i", strtotime($os->data_hora_alteracao)) . '">' . $os->nome_alteracao . "</span>";
                    }
                    ?>
                    <?php
                    if ($os->nome_exclusao != "") {
                        echo '</br><b>Excluído: </b>' . $os->nome_exclusao;
                    }
                    ?>
                </td>
                <td>Pedido Nro  <?php echo $os->nro_pedido_de_orcamento; ?></td>
                <td><?php echo $os->status; ?>
                </td>
                <?php
                switch ($os->prioridade) {
                    case 'u':
                        $prioridade = 'Urgente';
                        break;
                    case 'h':
                        $prioridade = 'Para Hoje';
                        break;
                    case 's':
                        $prioridade = 'Para essa Semana';
                        break;
                    case 'm':
                        $prioridade = 'Para esse Mes';
                        break;
                }
                ?>
                <td><?php echo $prioridade; ?></td>

                <td><?php echo $os->status_orc; ?></td>

                <td>
                    <div class="btn-group-sm btn-group-vertical" role="group" aria-label="...">
                        <button  type="button" class="btn btn-sm Button" data-toggle="modal" data-target="#myModalOs" data-whatever="<?php echo $os->id_pedido_de_orcamento; ?>" >Ver Pedido de Oçamento </button>
                        <?php if ($os->status == 'Pendentes de Aprovação') { ?>
                            <?php if ($os->nome_sol == $self) { ?>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalRemoverOs" data-whatever="<?php echo $os->id_pedido_de_orcamento; ?> ">Excluir</button>
                                <a class="btn btn-warning" href="<?php echo base_url('restrita/pedidos_orcamentos/alterar/' . $os->id_pedido_de_orcamento); ?>">
                                    <span class="glyphicon-class">Editar</span></a>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </td>
            </tr>
        <?php } ?>

        <?php foreach ($pedidosUsuarioAprov as $os) { ?>
            <tr>
                <td><?php echo date("d/m/Y", strtotime($os->data)); ?></br><?php echo $os->hora; ?></td>
                <td><?php echo $os->localidade; ?></td>
                <td><b>Requerente:</b><?php echo $os->nome_sol; ?></br>
                    <b>Aprovador:</b><?php echo $os->nome_aprov; ?>
                    <?php
                    if ($os->data_hora_alteracao != "") {
                        echo '</br><b>Alterado: </b><span title="' . date("d/m/Y H:i", strtotime($os->data_hora_alteracao)) . '">' . $os->nome_alteracao . "</span>";
                    }
                    ?>
                    <?php
                    if ($os->nome_exclusao != "") {
                        echo '</br><b>Excluído: </b>' . $os->nome_exclusao;
                    }
                    ?>
                </td>
                <td>Pedido Nro  <?php echo $os->nro_pedido_de_orcamento; ?> </br>
                </td>

                <td><?php echo $os->status; ?>
                    </br>
                    <?php if ($os->status == 'Pendentes de Aprovação') { ?>
                        <div class="btn-group-sm btn-group-vertical" role="group" aria-label="...">
                            <a class="btn btn-sm Button" href="<?php echo base_url('restrita/pedidos_orcamentos/aprovar/' . $os->id_pedido_de_orcamento); ?>">
                                <span class="glyphicon-class">Aprovar</span>
                            </a>
                            <a class="btn btn-primary btn-sm " href="<?php echo base_url('restrita/pedidos_orcamentos/recusar/' . $os->id_pedido_de_orcamento); ?>">
                                <span class="glyphicon-class">Não Aprovar</span>
                            </a>
                        </div>
                    <?php } ?>
                </td>
                </td>
                <?php
                switch ($os->prioridade) {
                    case 'u':
                        $prioridade = 'Urgente';
                        break;
                    case 'h':
                        $prioridade = 'Para Hoje';
                        break;
                    case 's':
                        $prioridade = 'Para essa Semana';
                        break;
                    case 'm':
                        $prioridade = 'Para esse Mes';
                        break;
                }
                ?>

                <td><?php echo $prioridade; ?></td>
                <?php if ($user[0]->id_tipo_usuario != 4) { ?>
                    <td>
                        <?php echo form_open('restrita/pedidos_orcamentos/alterarOrc/' . $os->id_pedido_de_orcamento, array('id' => 'formOrc', 'class' => 'bounceInLeft', 'data-wow-duration' => '2s')); ?>
                        <select class="statusOrc" id_pedido_de_orcamento="<?php echo $os->id_pedido_de_orcamento; ?>" data-status_orc="<?php echo $os->status_orc; ?>">
                            <option value="" class="option_cad">Mudar Status</option>
                            <?php echo $this->geral->combo_status_orcamento($os->status_orc); ?>
                        </select>
                        <?php echo form_close(); ?>
                    </td>
                <?php } ?>
                <td>
                    <div class="btn-group-sm btn-group-vertical" role="group" aria-label="...">
                        <button  type="button" class="btn btn-sm Button" data-toggle="modal" data-target="#myModalOs" data-whatever="<?php echo $os->id_pedido_de_orcamento; ?>" >Ver Pedido de Oçamento </button>
                        <?php if ($os->status == 'Pendentes de Aprovação') { ?>
                            <a class="btn btn-warning" href="<?php echo base_url('restrita/pedidos_orcamentos/alterar/' . $os->id_pedido_de_orcamento); ?>">
                                <span class="glyphicon-class">Editar</span>
                            </a>
                        <?php } ?>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
