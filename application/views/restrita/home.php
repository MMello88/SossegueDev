<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>
<style>
div.d{
    padding-bottom: 100px;
    padding-top: 100px;
    padding-left: 500px;
}
</style>
<div class='d'>
<h2>Bem vindo!</h2>
</div>

<?php if($this->session->flashdata('msg')) { ?>
<div class="modal fade" id="modalLogin" tabindex="-1" role="dialog" style="margin-top: 80px">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo $this->session->flashdata('msg'); ?></h4>
      </div>
    </div>
  </div>
</div>
<?php } ?>

<?php if($this->session->userdata('msgOs')) { ?>
<div class="modal fade" id="modalLogin" tabindex="-1" role="dialog" style="margin-top: 80px">
  <div class="modal-dialog " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo $this->session->userdata('msgOs'); ?></h4>
      </div>
    </div>
  </div>
</div>
<?php } ?>
<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>


