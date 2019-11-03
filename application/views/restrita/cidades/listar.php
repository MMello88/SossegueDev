<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>
<h2>
    <?php
    $url = $this->uri->uri_string();
    $url = explode('/', $url);
    array_pop($url);
    $url = implode('/', $url) . '/cadastrar';
    ?>
    <a href="<?php echo base_url($url); ?>">Cadastrar</a>
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
        <th>Cidade</th>
        <th>Link</th>
        <th>Estado</th>
        <th>Ação</th>
      </tr>
    </thead>
    <tbody>
        <?php foreach($cidades as $cidade) { ?>
        <tr>
            <td><?php echo $cidade->id; ?></td>
            <td>
                <select class="status" data-campo="id" data-id="<?php echo $cidade->id; ?>" data-tabela="cidades">
                    <option value="a" <?php if($cidade->status === 'a') echo 'selected' ?>>Ativo</option>
                    <option value="d" <?php if($cidade->status === 'd') echo 'selected' ?>>Desativado</option>
                </select>
            </td>
            <td><?php echo $cidade->nome; ?></td>
            <td><?php echo $cidade->link; ?></td>
            <td><?php echo $cidade->estado; ?></td>
            <td>
                <a href="<?php echo base_url('restrita/cidades/editar/' . $cidade->id); ?>">Editar</a>
                <a href="#" data-toggle="modal" data-target="#modalDelete" class="deletar" data-campo="id" data-id="<?php echo $cidade->id; ?>" data-tabela="cidades">Deletar</a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
