<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
   </head>  
   <body>
          <div id="popup" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                 </div>
                  <div class="modal-body">
                    <h2 class="modal-title"><?php echo $this->session->flashdata('sucesso');?></h2>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar/button>
                    <button type="button" class="btn btn-primary" src="#">Ir para carrinho</button>
                  </div>

            </div><!-- /.modal -->
  </body>
</html>