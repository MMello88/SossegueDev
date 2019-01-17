<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>
<h2>
    <?php
    $url = $this->uri->uri_string();
    $url = explode('/', $url);
    array_pop($url);
    $url = implode('/', $url) . '/cadastrarProfissional';
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
        <th>Nome</th>
        <th>Plano</th>
        <th>Situação</th>
        <th>Expira em</th>
        <th>Ação</th>
      </tr>
    </thead>
    <tbody>
        <?php foreach($usuarios as $usuario) { ?>
        <tr>
            <td><?php echo $usuario->id_profissional; ?></td>
            <td>
                <select class="status" data-campo="id_profissional_fatura" data-id="<?php echo $usuario->id_profissional_fatura; ?>" data-tabela="profissional_fatura">
                    <?php if($usuario->status === 'a') { ?>
                    <option value="a" selected>Ativo</option>
                    <option value="i">Inativo</option>
                    <?php } else { ?>
                    <option value="i" selected>Inativo</option>
                    <option value="a">Ativo</option>
                    <?php }?>
                </select>
            </td>
            <td><?php echo $usuario->nome; ?></td>
            <td><?php echo $usuario->plano; ?></td>
            <td>
                <select class="situacao" data-campo="id_profissional_fatura" data-id="<?php echo $usuario->id_profissional_fatura; ?>" data-tabela="profissional_fatura">
                    <option value="a" <?php if($usuario->situacao === 'a') echo 'selected'; ?>>Aprovado</option>
                    <option value="p" <?php if($usuario->situacao === 'p') echo 'selected'; ?>>Pendente</option>
                    <option value="c" <?php if($usuario->situacao === 'c') echo 'selected'; ?>>Cancelado</option>
                </select>
            </td>
            <td><?php echo $usuario->expira; ?></td>
            <td>
                <a href="<?php echo base_url('restrita/usuarios/editarProfissional/' . $usuario->id_usuario); ?>">Editar</a>
                <a href="<?php echo base_url('restrita/usuarios/galeria/' . $usuario->id_usuario); ?>">Galeria</a>
                <a href="#" data-toggle="modal" data-target="#modalDelete" class="deletar" data-campo="id_usuario" data-id="<?php echo $usuario->id_usuario; ?>" data-tabela="usuario" data-definitivo="true">Deletar</a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
