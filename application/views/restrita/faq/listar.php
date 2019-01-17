<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>
<h2>
    <a href="<?php echo base_url('restrita/faq/cadastrar'); ?>">Cadastrar</a>
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
        <th>Pergunta</th>
        <th>Ação</th>
      </tr>
    </thead>
    <tbody>
        <?php foreach($faqs as $faq) { ?>
        <tr>
            <td><?php echo $faq->id; ?></td>
            <td>
                <select class="status" data-campo="id" data-id="<?php echo $faq->id; ?>" data-tabela="faq">
                    <?php if($faq->status === 'a') { ?>
                    <option value="a" selected>Ativo</option>
                    <option value="i">Inativo</option>
                    <?php } else { ?>
                    <option value="i" selected>Inativo</option>
                    <option value="a">Ativo</option>
                    <?php }?>
                </select>
            </td>
            <td><?php echo $faq->pergunta; ?></td>
            <td>
                <a href="<?php echo base_url('restrita/faq/editar/' . $faq->id); ?>">Editar</a>
                <a href="#" data-toggle="modal" data-target="#modalDelete" class="deletar" data-campo="id" data-id="<?php echo $faq->id; ?>" data-tabela="faq">Deletar</a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>

