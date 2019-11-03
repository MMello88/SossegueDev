<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>
<?php echo form_open_multipart('restrita/parceiros/alterar'); ?>
<input type="hidden" name="id_parceiro" value="<?php echo $parceiro->id_parceiro; ?>">
<input type="hidden" name="fotoAntiga" value="<?php echo $parceiro->foto; ?>">
<div class="col-sm-12">
    <input class="form-control" type="text" placeholder="Nome do parceiro" name="parceiro" value="<?php echo $parceiro->parceiro; ?>" required>
</div>
<?php if($parceiro->foto) { ?>
<div class="col-sm-12">
    <img class="pop" style="cursor: pointer;" title="Visualizar" src="<?php echo base_url('uploads/parceiros/' . $parceiro->foto); ?>" alt="">
    <a href="#" class="excluirFoto" data-campo="id_parceiro" data-id="<?php echo $parceiro->id_parceiro; ?>" data-tabela="parceiros" data-foto="<?php echo DIR_PARCEIRO . $parceiro->foto; ?>" data-toggle="modal" data-target="#modalDelete">Excluir</a>
</div>
<?php } ?>
<div class="col-sm-12">
    <input class="form-control" type="file" placeholder="Foto" name="fotoparceiro">
</div>
<div class="col-sm-12">
    <input class="form-control" type="text" placeholder="Link" name="link" value="<?php echo $parceiro->link; ?>">
</div>
<div class="col-sm-3">
    <a href="<?php echo base_url('restrita/parceiros'); ?>" class="btn btn-success btn-block">Cancelar</a>
</div>
<div class="col-sm-3">
    <button type="submit" class="btn btn-warning btn-block">Alterar</button>
</div>
<?php echo form_close(); ?>
<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
