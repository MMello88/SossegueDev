
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
    .Excluido{
        color: #a94442;
    }
    
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
<?php include_once(APPPATH .'views/restrita/includes/header.php'); ?>

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
        <th>Dados Empresa</th>
        <th>Dados Aprovador</th> 
        <th>Cidade</th>
        <th>Pedidos de Orçamento</th>
        <th>Data/Horario</th>
        <th>Prioridade</th>
        <th>Status do Orçamentos</th>
       </th> <th>

      </tr>
    </thead>
    <tbody>
        <?php foreach($listar as $os) { ?>
        <tr>
            <td><?php echo $os->nome_empresa ;?></br><?php echo $os->email_empresa ;?></br><?php echo $os->telefone_empresa ;?></td>
            <td><?php echo $os->nome_aprov ;?></br><?php echo $os->email_aprov ;?></br><?php echo $os->telefone_aprov ;?></td>
            <td><?php echo $os->nome_cid1; ?></td>
            <td><?php echo $os->nro_pedido_de_orcamento; ?></td>
            <td><?php echo $os->data; ?></br><?php echo $os->hora; ?></td>
          <?php switch ($os->prioridade) {
               case 'u':
                  $prioridade='Urgente';
                   break;
                case 'h':
                  $prioridade='Para Hoje';
                   break;
                case 's':
                  $prioridade='Para essa Semana';
                   break;
                case 'm':
                  $prioridade='Para esse Mes';
                   break;
               }?>
               <td><?php echo $prioridade; ?></td>

            <td><?php echo $os->status_orc; ?></td>
            <td>
                <div class="btn-group-sm btn-group-vertical" role="group" aria-label="...">
                    <button  type="button" class="btn btn-warning" data-toggle="modal" data-target="#myModalOs" data-whatever="<?php echo $os->id_pedido_de_orcamento;?>" >Ver Pedidos de Orçamento</button>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalRemoverOs" data-whatever="<?php echo $os->id_pedido_de_orcamento;?> ">Remover</button>
                </div>
            </td>
        </div>
        </tr>
        <?php } ?>
    </tbody>
</table>



<!-- myModalOs -->
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



<div class="modal fade" id="myModalRemoverOs" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php echo form_open('restrita/admin/remover', array('id' => 'formCadastro', 'class' => 'bounceInLeft form-horizontal', 'data-wow-duration' => '2s')); ?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Deletar Pedido de Orçamento</h4>
        </div>
        <div class="modal-body">
          Deseja deletar este Pedido de Orçamento.?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-info btn-sm" data-dismiss="modal">Fechar</button>
          <button type="submit" class="btn btn-warning btn-sm">Remover Pedido de Orçamento</button>
        </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php"); ?>



