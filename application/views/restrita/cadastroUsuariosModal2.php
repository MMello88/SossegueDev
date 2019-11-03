<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>

<div class="modal fade" id="modalLogin" tabindex="-1" role="dialog" aria-hidden="true" style="margin-top: 80px">
  <div class="modal-dialog bd-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title">Cadastro de Usuarios</h1></div>
        <div class="modal-body">
 <?php echo form_open('restrita/usuario/mensagem' ,array('id' => 'formUsuario', 'data-wow-duration' => '2s')); ?>
<h5>Esse Tipo de Usuário pode :</br>
         <input type="radio" name="tipo" id="tipo" onclick="myFunction2()" form ="formUsuario" value="6">Aprovador
         <input type="radio" name="tipo" id="tipo" onclick="myFunction2()" form ="formUsuario"  value="4">Requerente
</br></br>

<div id="myDIV1" style="display:none" >
  Esse Tipo de Usuário Pode:</br> 
  <ul>
  <li>Adicionar Novos Usuários</li>
   <li>Requisitar Pedidos de Orçamento</li>
  <li> Visualizar Pedidos de Orçamento</li>
   <li>Aprovar Pedidos de Orçamento</li>
</ul>
</div>
<div id="myDIV2" style="display:none">
Esse Tipo de Usuário Pode:</br>
<ul>
<li> Requisitar Pedidos de Orçamento</li>
<li> Visualizar Pedidos de Orçamento</li>
</ul>
</div></br>

Insira o E-mail do Usuário que deseja cadastrar: </br></br>
<input class="form-control" type="email" id="email" name="email" form ="formUsuario" value="" >
</h4></br></br>
<button form="formUsuario" class="btn btn-success btn-block">Enviar</button>
 <?php echo form_close(); ?>
</div>
</div>
</div>
</div>

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
            <td><?php echo 'teste' ?></td>
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
            <a class="btn btn-success btn-sm " href="<?php echo base_url('restrita/usuario/popup/'.$usuarioR->id_usuario); ?>">
            <span class="glyphicon-class">Excluir</span>

            </td>
            <td><?php echo $usuarioR->email;?></td>
            <?php switch ($usuarioR->id_tipo_usuario){
               case '4':
                  $tipo ='Requerente';
                   break;
                case '6':
                  $tipo ='Aprovador';
                  break;
               }?>
            <td><?php echo $tipo; ?></td>
             <td><?php echo $usuarioR->setor; ?></td>
            <?php switch ($usuarioSessao[0]->status) {
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
        <?php foreach($usuariosAprov as $usuarioA) {     ?>
        <tr>
            <td><?php echo $usuarioA->nome ;?>
            <a class="btn btn-success btn-sm " href="<?php echo base_url('restrita/usuario/popup/'.$usuarioA->id_usuario); ?>">
            <span class="glyphicon-class">Excluir</span>
            </td>
            <td><?php echo $usuarioA->email;?></td>
          <?php switch ($usuarioA->id_tipo_usuario){
               case '4':
                  $tipo ='Requerente';
                   break;
                case '6':
                  $tipo ='Aprovador';
                  break;
               }?>
            <td><?php echo $tipo; ?></td>
             <td><?php echo $usuarioA->setor ;?></td>
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
<script>

function myFunction2() {
   var div1 = document.getElementById("myDIV1");
   var div2 = document.getElementById("myDIV2");
   var checkBox = document.getElementById("tipo");
   if (checkBox.checked ){
    div1.style.display = "block"; 
    div2.style.display = "none";
  }else{
     div2.style.display = "block"; 
    div1.style.display = "none";
}

}
</script>
<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>