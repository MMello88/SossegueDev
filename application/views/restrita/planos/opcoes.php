<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>
<h2>
    <?php
    $url = $this->uri->uri_string();
    $url = explode('/', $url);
    array_pop($url);
    $url = implode('/', $url) . '/cadastrarOpcao';
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
        <th>Opção</th>
        <th>Ordem de exibição</th>
        <th>Ação</th>
      </tr>
    </thead>
    <tbody>
        <?php foreach($opcoes as $opcao) { ?>
        <tr>
            <td><?php echo $opcao->id_planos_opcoes; ?></td>
            <td>
                <select class="status" data-campo="id_planos_opcoes" data-id="<?php echo $opcao->id_planos_opcoes; ?>" data-tabela="planos_opcoes">
                    <?php if($opcao->status === 'a') { ?>
                    <option value="a" selected>Ativo</option>
                    <option value="i">Inativo</option>
                    <?php } else { ?>
                    <option value="i" selected>Inativo</option>
                    <option value="a">Ativo</option>
                    <?php }?>
                </select>
            </td>
            <td><?php echo $opcao->opcao; ?></td>
            <td><?php echo $opcao->ordem; ?></td>
            <td>
                <a href="<?php echo base_url('restrita/planos/editarOpcao/' . $opcao->id_planos_opcoes); ?>">Editar</a>
                <a href="#" data-toggle="modal" data-target="#modalDelete" class="deletar" data-campo="id_planos_opcoes" data-id="<?php echo $opcao->id_planos_opcoes; ?>" data-tabela="planos_opcoes">Deletar</a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
