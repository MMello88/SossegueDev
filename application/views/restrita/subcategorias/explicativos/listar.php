<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>
<h2>
    <a href="<?php echo base_url("restrita/categorias/cadastrarExplicativo/$id_subcategoria"); ?>">Cadastrar</a>
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
        <th>Titulo</th>
        <th>Status</th>
        <th>Ação</th>
      </tr>
    </thead>
    <tbody>
        <?php foreach($listaExplicativos as $explicativo) { ?>
        <tr>
            <td><?php echo $explicativo->id_explicativos; ?></td>
            <td><?php echo $explicativo->titulo; ?></td>
            <td>
                <select class="status" data-campo="id_explicativos" data-id="<?php echo $explicativo->id_explicativos; ?>" data-tabela="explicativos">
                    <?php if($explicativo->status === 'a') { ?>
                    <option value="a" selected>Ativo</option>
                    <option value="i">Inativo</option>
                    <?php } else { ?>
                    <option value="i" selected>Inativo</option>
                    <option value="a">Ativo</option>
                    <?php }?>
                </select>
            </td>
            <td>
                <a href="<?php echo base_url('restrita/categorias/editarExplicativo/' . $explicativo->id_explicativos); ?>">Editar</a>
                <a href="#" data-toggle="modal" data-target="#modalDelete" class="deletar" data-campo="id_explicativos" data-id="<?php echo $explicativo->id_explicativos; ?>" data-tabela="explicativos">Deletar</a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
