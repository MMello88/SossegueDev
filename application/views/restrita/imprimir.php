<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>

<div>TELEFONE :</div>
<div>ENDEREÇO :</div>
<div>DATA DO ORÇAMENTO:</div>

<div>NOME DA EMPRESA PRESTADORA:</div>
<div>CNPJ: </div>
<div>DADOS DO CLIENTE:</div>
<div>MÃO DE OBRA:</div>
<table id="minhaTabela" class="table table-striped">
  <tr>
    <th>Servicos</th>
    <th>Quantidade</th>
    <th>Valor Total</th>
  </tr>
 <?php $idNumber = 0; ?>
 <?php  {  /*tem que ajeitar o banco de dados e pegar os dados certos, esses estao errados*/?>
 <?php echo form_open(' ', array('id' => 'formCadastro', 'data-wow-duration' => '2s')); ?>
 <?php $idNumber += 1; ?>
        <tr>
            <td><?php echo  "oi" ?></td>
            <td><?php echo "oi" ?></td>            
            <td><?php echo "oi" ?></td>
            <td><a href="<?php echo base_url('restrita')?>">Retirar</a></td>
        </tr>
<?php echo form_close(); ?>
<?php } ?>
</table>


<div>
<?php echo form_open(' ', array('id' => 'formCadastro')); ?>
VALIDADE:<input class="form-control" type="text" name="Validade" id="validade" value="" maxlength=2 required>
<?php echo form_close(); ?>
</div>

<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
