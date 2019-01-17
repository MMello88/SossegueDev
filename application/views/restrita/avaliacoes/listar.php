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
        <th>ID</th>
        <th>Status</th>
        <th>Profissional</th>
        <th>Usuário</th>
        <th>Nota</th>
        <th>Data</th>
        <th>Ação</th>
      </tr>
    </thead>
    <tbody>
        <?php foreach($avaliacoes as $avaliacao) { ?>
        <tr>
            <td><?php echo $avaliacao->id_nota; ?></td>
            <td>
                <select class="status" data-campo="id_nota" data-id="<?php echo $avaliacao->id_nota; ?>" data-tabela="nota">
                    <?php if($avaliacao->status === 'a') { ?>
                    <option value="a" selected>Ativo</option>
                    <option value="i">Inativo</option>
                    <?php } else { ?>
                    <option value="i" selected>Inativo</option>
                    <option value="a">Ativo</option>
                    <?php }?>
                </select>
            </td>
            <td><?php echo $avaliacao->profissional; ?></td>
            <td><?php echo $avaliacao->usuario; ?></td>
            <td class="color_primary">
               <?php $nota = intval($avaliacao->nota); ?>
               <?php $resto = substr($avaliacao->nota, 2,2); ?>
               <?php for($i = 1; $i <= 5; $i++) { ?>
                   <?php if($nota >= $i) { ?>
                   <i class="fa fa-star"></i>
                   <?php } else { ?>
                       <?php if($resto === '5') { ?>
                       <?php $resto = '';?>
                       <i class="fa fa-star-half-full"></i>
                       <?php } else { ?>
                       <i class="fa fa-star-o"></i>
                       <?php } ?>
                   <?php } ?>
               <?php } ?>
            </td>
            <td><?php echo $avaliacao->data; ?></td>
            <td>
                <a href="<?php echo base_url('restrita/avaliacoes/editar/' . $avaliacao->id_nota); ?>">Editar</a>
                <a href="#" data-toggle="modal" data-target="#modalDelete" class="deletar" data-campo="id_nota" data-id="<?php echo $avaliacao->id_nota; ?>" data-tabela="nota">Deletar</a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>


