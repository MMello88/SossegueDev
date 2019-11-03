<!-- SCRIPTS -->
<?php foreach($js as $script) {?>
<script src="<?php echo $script; ?>"></script>
<?php }?>

<?php /* =========================== Script do formulario de login ===================================== */ ?>
<script>
$(function() {
    $('#login-form-link').click(function(e) {
        $("#login-form").delay(100).fadeIn(100);
        $("#register-form").fadeOut(100);
        $('#register-form-link').removeClass('active');
        $(this).addClass('active');
        e.preventDefault();
    });
    $('#register-form-link').click(function(e) {
        $("#register-form").delay(100).fadeIn(100);
        $("#login-form").fadeOut(100);
        $('#login-form-link').removeClass('active');
        $(this).addClass('active');
        e.preventDefault();
    });

});
</script>

<?php if (isset($script_filtro)) { ?>
<?php   if ($script_filtro == 1) { ?>
<script>

 $(window).scroll(function() {
    var tela = $(document).width();
    var scroll = $(document).scrollTop();
    if (tela > 1299) {
        if(scroll > 200){
            $(".yamm-scroll").removeClass("col-md-4");
            $(".yamm-scroll").addClass("col-md-12 navbar-fixed-top container");
            $(".yamm-scroll").addClass("top-down");
        } else {
            $(".yamm-scroll").addClass("col-md-4");
            $(".yamm-scroll").removeClass("col-md-12 navbar-fixed-top container");
            $(".yamm-scroll").removeClass("top-down");
        }
    } else if ((tela < 1300) && (tela > 767)) {
        $(".yamm-scroll").removeClass("navbar-fixed-top bottom-right");
        if(scroll > 200){
            $(".yamm-scroll").removeClass("col-md-4");
            $(".yamm-scroll").addClass("col-md-12 navbar-fixed-bottom container");
            $(".yamm-scroll").addClass("bottom-top");
        } else {
            $(".yamm-scroll").addClass("col-md-4");
            $(".yamm-scroll").removeClass("col-md-12 navbar-fixed-bottom container");
            $(".yamm-scroll").removeClass("bottom-top");
        }
    } else {
        $(".yamm-scroll").removeClass("navbar-fixed-top");
        if(scroll > 200){
            $(".yamm-scroll").removeClass("col-md-4");
            $(".yamm-scroll").addClass("col-md-12 navbar-fixed-bottom container");
            $(".yamm-scroll").addClass("bottom-top bottom-right");
        } else {
            $(".yamm-scroll").addClass("col-md-4 bottom-right");
            $(".yamm-scroll").removeClass("col-md-12 navbar-fixed-bottom container");
            $(".yamm-scroll").removeClass("bottom-top bottom-right");
        }
    }
});

$(document).on('change', 'select',function(){
    var atr = $(this).attr( "id" );
    
    if (atr !== undefined){
        var val = $("select#"+atr+" option:selected").val();
        var texto = $("select#"+atr+" option:selected").text();
        if (texto.length > 15) {
            $(this).addClass("word-wrap-select");
        } else {
            $(this).removeClass("word-wrap-select");
        }

        var objSelect = this;
        $.getJSON("<?php echo base_url("/Manutencao/getFiltrosOrcamento/"); ?>/" + val , function(result){
          $(objSelect).siblings(".result").empty();


            var Select =  "";
            $.each(result, function(i, Field) {
                Select = Select.concat( 
                  "<input name=\"id_pergunta\" value=\"" + Field.id_pergunta + "\" type=\"hidden\" required>");
              if (Field.input_type == 's'){

                if (Field.pergunta.length > 10) {
                    Select = Select.concat( 
                      "<select title=\"Selecione o(a) " + Field.pergunta + "\" class=\"form-control word-wrap-select\" name=\"id_filtros["+i+"][id_filtro]\" required>");
                } else {
                    Select = Select.concat( 
                      "<select title=\"Selecione o(a) " + Field.pergunta + "\" class=\"form-control\" name=\"id_filtros["+i+"][id_filtro]\" required>");
                }
                Select = Select.concat( 
                  "  <option value=\"\" selected=\"\" disabled=\"\"> Selecione o(a) " + Field.pergunta + ": </option>");
                $.each(Field.Filtros, function(x,option){
                  Select = Select.concat("  <option value=\"" + option.id_filtro + "\">" + option.filtro + "</option>");
                });
                Select = Select.concat("</select>");
              } else if (Field.input_type == 't') {
                $.each(Field.Filtros, function(x,option){
                    Select = Select.concat(         
                        "<textarea name=\"id_filtros["+option.id_filtro+"][value]\" value=\"\" placeholder=\"" + Field.pergunta + "\" class=\"form-control\" id=\"DescFiltro\"  required ></textarea>");
                });
              }
            });
            $(objSelect).siblings(".result").append(Select);
        });
    }
});

$(document).on('mouseup', '#btn8',function(){
    var $btn = $(this).button('loading');
    setTimeout(function(){
      $btn.button('reset');
    }, 3000);  
});

$(document).on('keypress', '#DescFiltro',function(){
    if ($(this).val().length > 26){
        $(this).addClass("word-wrap-select");
    } else {
        $(this).removeClass("word-wrap-select");
    }
});

</script>
<?php   } ?>
<?php } ?>

<?php if (isset($script_increment)) { ?>
<?php   if ($script_increment == 1) { ?>
<script>
$('.input-group').on('click', '#menos', function(event){
  var idOrcamento = $(this).next().next().next().val();
  var idServico = $(this).next().next().val();
  var idPedido = $(this).next().val();
  
  var atr = $(this).next().next().next().next().val();
  atr = atr - 1;
  if (atr <= '1')
    atr = 1;
  $(this).next().next().next().next().val(atr);
  
  
  saveQtde(idPedido, idServico, idOrcamento, atr);
});

$('.input-group').on('click', '#mais', function(event){
  var idOrcamento = $(this).prev().prev().val();
  var idServico = $(this).prev().prev().prev().val();
  var idPedido = $(this).prev().prev().prev().prev().val();        
  
  var atr = $(this).prev().val();
  var result = Number(atr) + 1;
  $(this).prev().val(result);  
  
  saveQtde(idPedido, idServico, idOrcamento, result);
});

$(document).on('mouseup', '#btn8',function(){
  var $btn = $(this).button('loading');
  $btn.css("disabled");
});

function saveQtde(idPedido, idServico, idOrcamento, Qtde){
    var security = $("input[name='csrf_test_name']").val();
    $.ajax({
        url : "<?php echo base_url("/Carrinho/Alterar"); ?>",
        type : "POST",
        dataType : "json",
        data : {"id_pedido": idPedido, "id_servico": idServico, "id_orcamento": idOrcamento, "qntd": Qtde, "csrf_test_name": security },
        success : function(data) {
            if (data.qntd !=0){
               
            } else {
               
            }
        },
        error : function(data) {
            return false;
        }
    });
}
</script>
<?php   } ?>
<?php } ?>

<?php if (isset($script_listar_pedido)) { ?>
<?php   if ($script_listar_pedido == 1) { ?>
<script>
$('#myModalPedido').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget); 
  var recipientId = button.data('whatever'); 
  var modal = $(this);
  $.getJSON("<?php echo base_url("restrita/pedidos/lista_pedidos"); ?>/" + recipientId , function(result){
    
    modal.find('.modal-title').text('Id para listar o orçamento ' + recipientId);
    
    var texto = "";
    $.each(result, function(i,pedido) {
        texto = texto.concat(
            "<div class=\"row team-box\"> " +
            "   <div class=\"col-xs-5 col-md-3\"> " +
            "       <img src=\"<?php echo base_url("assets/media/categoria_servico"); ?>/"+pedido.Servico.categoria.imagem+" \" class=\"img-responsive\" alt=\"Rack\" height=\"100\" width=\"100\" align=\"left\"> " +
            "   </div> " +
            "   <div class=\"col-xs-7 col-md-9\"> " +
            "       <ul class=\"list-unstyled\"> " +
            "           <li><strong>"+pedido.Servico.categoria.descricao+"</strong></li>" +
            "           <li>Categoria "+pedido.Servico.categoria.subcategoria.descricao+"</li>" +
            "           <li>Servico de "+pedido.Servico.descricao+"</li> " +
            "           <li>Qntde: "+pedido.qntd+"</li> " +
            "           <li class="+pedido.status+">Status do item: "+pedido.status+"</li> " );
        
        $.each(pedido.filtros, function(x,filtro){
            if (filtro.filtro === ""){
                texto = texto.concat( "<li>"+filtro.pergunta + ": " + filtro.valor + "</li>" );
            } else {
                texto = texto.concat( "<li>"+filtro.pergunta + ": " + filtro.filtro + "</li>" );
            }
        });
        texto = texto.concat(
            "       </ul>" +
            "   </div> " +
            "</div> " );
    
    });
    modal.find('.modal-body').empty();    
    if (texto === ""){
        modal.find('.modal-body').text('Não foi selecionado nenhum serviço!');
    } else {
        modal.find('.modal-body').append(texto);
    }
  });
});

$('#myModalRemoverPedido').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget); 
  var recipientId = button.data('whatever'); 
  var modal = $(this);
  modal.find('.modal-body').text('Deseja remover o pedido de numero de Id => ' + recipientId);
  modal.find('.modal-body').append("<input name=\"id_orcamento\" value=\""+recipientId+"\" type=\"hidden\" required />");
});

</script>
<?php   } ?>
<?php } ?>


<?php if (isset($script_listar_os)) { ?>
<?php   if ($script_listar_os == 1) { ?>
<script>


$('#myModalOs').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget); 
  var recipientId = button.data('whatever'); 
  var modal = $(this);

  $.getJSON("<?php echo base_url("restrita/pedidos_orcamentos/listarPedidos"); ?>/" + recipientId , function(result){
    
    modal.find('.modal-title').text('Id para listar Pedido de Orçamento ' + recipientId);
    var texto = "";
    
    if(result.os.prioridade == 'u') {
      prioridade = 'Urgente';
    }   
    if(result.os.prioridade == 'h') {
      prioridade ='Para Hoje';
    }
    if(result.os.prioridade == 's') {
      prioridade = 'Para essa Semana';
    }
    if(result.os.prioridade == 'm') {
      prioridade = 'Para esse Mes';
    }
    

    texto = texto.concat(
    
           "<div class=\"form-control\">NRO PEDIDO DE ORÇAMENTO: "+result.os.nro_pedido_de_orcamento+
           "</div>"+

          "<div class=\"form-control\">NOME APROVADOR: "+result.os.nome_aprov+
           "</div>"+


          "<div class=\"form-control\">NOME REQUERENTE: "+result.os.nome_sol+
           "</div>"+

           "<div class=\"form-control\">PRIORIDADE: "+prioridade+
           "</div>"+

   
           "<div class=\"form-control\">SETOR EMPRESA: "+result.os.localidade+
           "</div>"+

           
            "<div class=\"form-control\">NORMAS: "+result.os.normas+
            "</div>"+
          
  
            "<div class=\"form-control\">QUALIFICAÇÃO: "+result.os.qualificacoes+
            "</div>"+ 
      
          "<div class=\"form-control\">DESCRIÇÃO: "+
            "</div>");
      
      $.each(result.pedidos, function(i, pedido) {
        texto = texto.concat(
            
            "<div class=\"row team-box\"> " +
            "   <div class=\"col-xs-5 col-md-3\"> " +
            "       <img src=\"<?php echo base_url("assets/media/categoria_servico"); ?>/"+pedido.Servico.categoria.imagem+" \" class=\"img-responsive\" alt=\"Rack\" height=\"100\" width=\"100\" align=\"left\"> " +
            "   </div> " +
            "   <div class=\"col-xs-7 col-md-9\"> " +
            "       <ul class=\"list-unstyled\"> " +
            "           <li><strong>"+pedido.Servico.categoria.descricao+"</strong></li>" +
            "           <li>Categoria "+pedido.Servico.categoria.subcategoria.descricao+"</li>" +
            "           <li>Servico de "+pedido.Servico.descricao+"</li> " +
            "           <li>Qntde: "+pedido.qntd+"</li> " +
            "           <li class="+pedido.status+">Status do item: "+pedido.status+"</li> " );
        $.each(pedido.filtros, function(x,filtro){
            if (filtro.filtro === ""){
                texto = texto.concat( "<li>"+filtro.pergunta + ": " + filtro.valor + "</li>" );
            } else {
                texto = texto.concat( "<li>"+filtro.pergunta + ": " + filtro.filtro + "</li>" );
            }
        });
        texto = texto.concat(
            "       </ul>" +
            "   </div> " +
            "</div> " );
    
    });
    modal.find('.modal-body').empty();    
    if (texto == ""){
        modal.find('.modal-body').text('Não foi selecionado nenhum serviço!');
    } else {
        modal.find('.modal-body').append(texto);
    }
  });
});


$('#myModalRemoverOs').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget); 
  var recipientId = button.data('whatever'); 
  var modal = $(this);
  modal.find('.modal-body').text('Deseja remover o pedido de numero de Id => ' + recipientId);
  modal.find('.modal-body').append("<input name=\"id_pedido_de_orcamento\" value=\""+recipientId+"\" type=\"hidden\" required />");
});

$('#myModalRemoverUsuario').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget); 
  var recipientId = button.data('whatever');
  var modal = $(this);
  modal.find('.modal-body').text('Deseja realmente excluir este Usuario?');
  modal.find('.modal-body').append("<input name=\"id_usuario\" value=\""+recipientId+"\" type=\"hidden\" required />");
});
</script>

<?php } ?>
<?php } ?>


<?php /* =========================== Script do filtro de busca da listagem do admin ===================================== */ ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
