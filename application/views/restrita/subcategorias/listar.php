<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>
<h2>
    <?php
    $url = $this->uri->uri_string();
    $url = explode('/', $url);
    array_pop($url);
    $url = implode('/', $url) . '/cadastrarSubcategoria';
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
        <th>Categoria</th>
        <th>Subcategoria</th>
        <th>Ação</th>
      </tr>
    </thead>
    <tbody>
        <?php foreach($listaSubcategorias as $subcategoria) { ?>
        <tr>
            <td><?php echo $subcategoria->id_subcategoria; ?></td>
            <td>
                <select class="status" data-campo="id_subcategoria" data-id="<?php echo $subcategoria->id_subcategoria; ?>" data-tabela="subcategoria">
                    <?php if($subcategoria->status === 'a') { ?>
                    <option value="a" selected>Ativo</option>
                    <option value="i">Inativo</option>
                    <?php } else { ?>
                    <option value="i" selected>Inativo</option>
                    <option value="a">Ativo</option>
                    <?php }?>
                </select>
            </td>
            <td><?php echo $subcategoria->categoria; ?></td>
            <td><?php echo $subcategoria->subcategoria; ?></td>
            <td>
                <a href="<?php echo base_url('restrita/categorias/editarSubcategoria/' . $subcategoria->id_subcategoria); ?>">Editar</a>
                <a href="#" data-toggle="modal" data-target="#modalDelete" class="deletar" data-campo="id_subcategoria" data-id="<?php echo $subcategoria->id_subcategoria; ?>" data-tabela="subcategoria">Deletar</a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
