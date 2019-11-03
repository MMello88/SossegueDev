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
        <th>Data</th>
        <th>Setor</th>
        <th>Dados Empresa</th>
        <th>Pedido de Orçamento</th>
        <th>Prioridade</th>
        <th>Status do Orçamento</th>
    
      </tr>
    </thead>
    <tbody>
        <?php foreach($listaAgendamento as $agendamento) { //ajeitar  
           ?>
        <tr>
            <td><?php echo $agendamento->id_agendamento; ?></td>
            <td><?php echo $agendamento->nome_cli.'<br />'.$agendamento->celular_cli.'<br />'.$agendamento->email_cli; ?></td>
            <td><?php echo $agendamento->data_de; ?></td>
            <td><?php echo $agendamento->horario; ?></td>
            <td><?php echo $agendamento->cat_nome.'<br />'.$agendamento->sub_nome; ?></td>
            <td><?php echo $agendamento->bairro_cli; ?></td>
            <td><?php echo $agendamento->descricao; ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
