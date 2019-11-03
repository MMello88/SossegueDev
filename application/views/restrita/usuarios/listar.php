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
        <th>Tipo</th>
        <th>Nome</th>
        <th>Email</th>
        <th>Ação</th>
      </tr>
    </thead>
    <tbody>
        <?php foreach($empresas as $empresa) { ?>
        <tr>
            <td><?php echo $empresa->id_usuario; ?></td>
            <td>
                <select class="status" data-campo="id_usuario" data-id="<?php echo $usuario->id_usuario; ?>" data-tabela="usuario">
                    <?php if($empresa->status === 'a') { ?>
                    <option value="a" selected>Ativo</option>
                    <option value="i">Inativo</option>
                    <?php } else { ?>
                    <option value="i" selected>Inativo</option>
                    <option value="a">Ativo</option>
                    <?php }?>
                </select>
            </td>
            <td><?php echo $empresa->tipo; ?></td>
            <td><?php echo $empresa->nome; ?></td>
            <td><?php echo $empresa->email; ?></td>   
            <td>
                <a href="<?php echo base_url('restrita/usuarios/editar/' . $empresa->id_usuario); ?>">Editar</a></br>
                <a href="#" data-toggle="modal" data-target="#modalDelete" class="deletar" data-campo="id_usuario" data-id="<?php echo $empresa->id_usuario; ?>" data-tabela="usuario" data-definitivo="true">Deletar</a></br>
                <a href="<?php echo base_url('restrita/usuarios/listar_usuarios/' . $empresa->id_usuario); ?>" >Ver Usuarios dessa empresa</a>
            </td>
             </tr>
        
<?php } ?>
  </tbody>
</table>




<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
