<?php 

if (isset($form)) {
  if ($form == "Categoria_prestada") { ?>

<script>
$('form#formSubCatUsuario').on('submit', function(){
  var dados = $( this ).serialize();
  var form = this;
  console.log(dados);
	$.ajax({
		type: "POST",
		url: "<?php echo base_url("restrita/prof/categ/inserir"); ?>",
		data: dados,
		success: function( data )
		{
			/*console.log(data);
			$("#msgError").html(data);
			$("#message-danger").removeAttr("style");*/
	      window.location.href = "<?php echo base_url("restrita/prof/mensagem/aviso"); ?>";
		},
	    error : function(data) {
	      console.log(data);
	      //$("#msgError").html(data.responseText);
	      $("#msgError").html("<strong>Desculpe!</strong> Erro ao receber seu pedido. Em breve tente novamente!");
	      $("#message-danger").removeAttr("style");
	    }
	});
	return false;
});
</script>

<script>
$('#btnVoltarPergunta').on('click', function(){
	window.location.href = "<?php echo base_url("restrita/prof/prev/pergunta"); ?>";
});	
</script>

<?php }
} ?>

<?php 
/*não uso mais descartar depois, tudo daqui para baixo*/
echo $form;
if (isset($form)) {
  if ($form == "salvar os dados dos valores") { ?>

<script>
$('form#formConfigVlr').on('submit', function(){
  var dados = $( this ).serialize();
  var form = this;
  console.log(dados);
	$.ajax({
		type: "POST",
		url: "<?php echo base_url("restrita/salvar/configVlr"); ?>",
		data: dados,
		success: function( data )
		{
			/*console.log(data);
			$("#msgError").html(data);
			$("#message-danger").removeAttr("style");*/
	      window.location.href = "<?php echo base_url("restrita/ctrl/next/pageQuest"); ?>";
		},
	    error : function(data) {
	      console.log(data);
	      $("#msgError").html(data.responseText);
	      //$("#msgError").html("<strong>Desculpe!</strong> Erro ao receber seu pedido. Em breve tente novamente!");
	      $("#message-danger").removeAttr("style");
	    }
	});
	return false;
});
</script>

<?php }
} ?>