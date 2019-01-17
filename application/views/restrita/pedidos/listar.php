<style>
    .team-box {
        background: #FFF;
        color: #000;
        border: 1px solid #CCC;
        border-radius: 3px;
        padding-bottom: 15px;
        padding-top: 15px;
        margin-bottom: 15px;
    }
    .Andamento{
        color:#8a6d3b;
    }
    .Finalizado{
        color: #3c763d;
    }
    .Excluído{
        color: #a94442;
    }
</style>
<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>
<?php if($this->session->flashdata('msg')) { ?>
<div class="alert alert-<?php echo(!$this->session->flashdata('erro') ? 'success' : 'danger') ;?> alert-dismissible" role="alert">
    <?php echo $this->session->flashdata('msg'); ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php } ?>
<table id="minhaTabela" class="table table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Dados Cliente</th>
        <th>Categoria</th>
        <th>Cidade</th>
        <th>CEP</th>
        <th>Descrição</th>
        <th>Data do Pedido</th>
        <th></th>

      </tr>
    </thead>
    <tbody>
        <?php foreach($listaOrcamento as $orcamento) { ?>
        <tr>
            <td><?php echo $orcamento->id_orcamento; ?></td>
            <td><?php echo $orcamento->nome_cli.'<br />'.$orcamento->celular_cli.'<br />'.$orcamento->email_cli; ?></td>
            <td><?php echo $orcamento->cat_nome.'<br />'.$orcamento->sub_nome; ?></td>
            <td><?php echo $orcamento->nome_cidade; ?></td>
            <td><?php echo $orcamento->bairro_cli; ?></td>
            <td><?php echo $orcamento->descricao; ?></td>
            <td><?php echo $orcamento->data_orcamento; ?></td>
            <td>
                <div class="btn-group-sm btn-group-vertical" role="group" aria-label="...">
                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#myModalPedido" data-whatever="<?php echo $orcamento->id_orcamento; ?>">Ver Pedidos</button>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalRemoverPedido" data-whatever="<?php echo $orcamento->id_orcamento; ?>">Remover</button>
                </div>
            </td>
        </tr>
        </div>
        <?php } ?>
    </tbody>
</table>

<!-- myModalPedido -->
<div class="modal fade" id="myModalPedido" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
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
<!-- fim myModalPedido -->

<!-- myModalRemoverPedido -->
<div class="modal fade" id="myModalRemoverPedido" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php echo form_open('restrita/pedidos/remover_pedido', array('id' => 'formCadastro', 'class' => 'bounceInLeft form-horizontal', 'data-wow-duration' => '2s')); ?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Deletar Pedido</h4>
        </div>
        <div class="modal-body">
          Deseja deletar o pedido do cliente xyz?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-info btn-sm" data-dismiss="modal">Fechar</button>
          <button type="submit" class="btn btn-warning btn-sm">Remover Pedido</button>
        </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>
<!-- fim myModalRemoverPedido -->

<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
