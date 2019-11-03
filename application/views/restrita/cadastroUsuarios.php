<?php include_once(APPPATH .'views/restrita/includes/header.php');?>

<a href="<?php echo base_url('restrita/usuario/adicionar'); ?>">
     <div class="btn" >
     <strong> Adicionar Usuário + </strong>
 </div></a></br></br>

<table id="minhaTabela" class="table table-striped">
    <thead>
      <tr>
        <th>Nome</th>
        <th>Email</th>
        <th>Tipo</th>
        <th>Setor</th>
        <th>Status</th>

     </tr>
    </thead>
    <tbody>
      <tr>
       <td><?php echo $usuarioSessao[0]->nome ;?>
          </td>
            <td><?php echo $usuarioSessao[0]->email;?></td>
            <?php switch ($usuarioSessao[0]->id_tipo_usuario){
               case '4':
                  $tipo ='Requerente';
                   break;
                case '5':
                  $tipo ='Usuario Empresa';
                  break;
                case '6':
                  $tipo ='Aprovador';
                  break;
               }?>
            <td><?php echo $tipo; ?></td>
            <td><?php echo $usuarioSessao->setor; ?></td>
           <?php switch ($usuarioSessao[0]->status) {
               case 'a':
                  $status='Ativo';
                   break;
                case 'r':
                  $status='Removido';
                  break;
               }?>
               <td><?php echo $status; ?></td>
         </tr>

        <?php foreach($usuariosReq as $usuarioR) { ?>
        
         <tr>
            <td><?php echo $usuarioR->nome ;?>
            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModalRemoverUsuario" data-whatever="<?php echo $usuarioR->id_usuario;?>">Excluir</button>
            </td>

            <td><?php echo $usuarioR->email;?></td>
            <td><?php echo 'Requerente'; ?></td>
             <td><?php echo $usuarioR->setor; ?></td>
           	<?php switch ($usuarioR->status) {
               case 'a':
                  $status='Ativo';
                   break;
                case 'r':
                  $status='Removido';
                  break;
               }?>
               <td><?php echo $status; ?></td>
        <?php } ?>
        </tr>
        <?php foreach($usuariosAprov as $usuarioA) {  ?>
        <tr>
            <td><?php echo $usuarioA->nome ;?>
            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModalRemoverUsuario" data-whatever="<?php echo $usuarioA->id_usuario;?>">Excluir</button>
            </td>
            <td><?php echo $usuarioA->email;?></td>
            <td><?php echo 'Aprovador'; ?></td>
             <td><?php echo $usuarioA->setor ;?></td>
             <?php switch ($usuarioA->status) { 
               case 'a':
                  $status='Ativo';
                   break;
                case 'r':
                  $status='Removido';
                  break;
               }?>
               <td><?php echo $status; ?></td>
        </tr>
        <?php } ?>
     
    </tbody>
</table>

<?php if($this->session->userdata('mensagem')){ ?>
<div class="modal fade" id="modalLogin" tabindex="-1" role="dialog" aria-hidden="true" style="margin-top: 80px">
  <div class="modal-dialog bd-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title">Cadastro de Usuarios</h1></div>
        <div class="modal-body">
<h3><?php echo $this->session->userdata('mensagem');?></h3>
       
</div>
</div>
</div>
</div>
<?php } ?>

<!-- myModalRemoverUsuario -->
<div class="modal fade" id="myModalRemoverUsuario" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php echo form_open('restrita/usuario/remover', array('id' => 'formCadastro', 'class' => 'bounceInLeft form-horizontal', 'data-wow-duration' => '2s')); ?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Deletar Usuario</h4>
        </div>
        <div class="modal-body">
          Deseja realmente deletar esta Usuario?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-info btn-sm" data-dismiss="modal">Não</button>
          <button type="submit" class="btn btn-warning btn-sm">Sim</button>
        </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>
      


<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
