<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>
<h2>
    <a href="<?php echo base_url('restrita/parceiros/cadastrar'); ?>">Cadastrar</a>
</h2>
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
        <th>Status</th>
        <th>Nome</th>
        <th>Link</th>
        <th>Ação</th>
      </tr>
    </thead>
    <tbody>
        <?php foreach($parceiros as $parceiro) { ?>
        <tr>
            <td><?php echo $parceiro->id_parceiro; ?></td>
            <td>
                <select class="status" data-campo="id_parceiro" data-id="<?php echo $parceiro->id_parceiro; ?>" data-tabela="parceiros">
                    <?php if($parceiro->status === 'a') { ?>
                    <option value="a" selected>Ativo</option>
                    <option value="i">Inativo</option>
                    <?php } else { ?>
                    <option value="i" selected>Inativo</option>
                    <option value="a">Ativo</option>
                    <?php }?>
                </select>
            </td>
            <td><?php echo $parceiro->parceiro; ?></td>
            <td><?php echo $parceiro->link; ?></td>
            <td>
                <a href="<?php echo base_url('restrita/parceiros/editar/' . $parceiro->id_parceiro); ?>">Editar</a>
                <a href="#" data-toggle="modal" data-target="#modalDelete" class="deletar" data-campo="id_parceiro" data-id="<?php echo $parceiro->id_parceiro; ?>" data-tabela="parceiros">Deletar</a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
