<?php 

if (isset($form)) {
  if ($form == "Categoria_prestada") { ?>



<script>
$('#btnVoltarPergunta').on('click', function(){
	window.location.href = "<?php echo base_url("restrita/prof/prev/pergunta"); ?>";
});	
</script>

<?php }
} ?>

<?php 
/*não uso mais descartar depois, tudo daqui para baixo*/

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

<script>
  $(".w3-check").change(function() {
		var recipient = $(this).data('whatever');
		if($(this).prop('checked')){
			$('#va'+recipient).prop('readonly',true);
			$('#va'+recipient).val('');
			$('#vp'+recipient).prop('readonly',true);
			$('#vp'+recipient).val('');
			$('#qtde'+recipient).prop('readonly',true);
			$('#qtde'+recipient).val('');
		} else {
			$('#va'+recipient).prop('readonly',false);
			$('#vp'+recipient).prop('readonly',false);
			$('#qtde'+recipient).prop('readonly',false);
		}
  });
</script>