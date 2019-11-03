<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>
<?php if(isset($cadastroIncompleto)) { ?>
<div class="alert alert-danger alert-dismissible" role="alert">
    Por favor, complete o cadastro com o endereço completo!
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php } ?>
<table id="minhaTabela" class="table table-striped">
 <h4>Orcamentos Disponiveis</h4>
    </br>
    <thead>
      <tr>
        <th>ID</th>
        <th>Dados da Empresa Prestadora</th>
        <th>Data do Orcamento</th>
        <th>Categoria</th>
        <th>Descrição</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach($listaAgendamento as $agendamento) {  /*tem que ajeitar o banco de dados e pegar os dados certos, esses estao errados*/
           ?>
        <tr>
            <td><?php echo $agendamento->id_agendamento; ?></td>
            <td><?php echo $agendamento->nome_cli.'<br />'.$agendamento->celular_cli.'<br />'.$agendamento->email_cli; ?></td>
            <td><?php echo $agendamento->data_de; ?></td>
            <td><?php echo $agendamento->horario; ?></td>
            <td><?php echo $agendamento->cat_nome.'<br />'.$agendamento->sub_nome; ?></td>
            <td><?php echo $agendamento->bairro_cli; ?></td>
            <td><?php echo $agendamento->descricao; ?></td>
            <td><?php echo $agendamento->data_agendamento; ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<a href="<?php echo base_url('restrita/imprimir'); ?>"><button class="btn btn-warning btn-block" >Imprimir Orcamento</button></a>


<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
