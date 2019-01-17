<?php include_once(APPPATH . "views/restrita/includes/header.php"); ?>

<div id="msgError"></div>

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

<h3><?php echo $subcategoria; ?></h3>
<hr size="0.1" style="border:1px solid ;">
<br>
<h3><?php echo $enunciado->titulo; ?></h3><br>

<?php if (!empty($enunciado->informativo)) { ?>
<div class="w3-panel w3-yellow w3-display-container">
  <span onclick="this.parentElement.style.display="none""
  class="w3-button w3-yellow w3-small w3-display-topright">&times;</span>
  <h5>Informativo:</h5>
  <p><?php echo $enunciado->informativo; ?></p>
</div>
<?php } ?>

<?php if (!empty($enunciado->observacao)) { ?>
<div class="w3-panel w3-pale-red w3-display-container">
  <span onclick="this.parentElement.style.display="none""
  class="w3-button w3-pale-red w3-small w3-display-topright">&times;</span>
  <h5>ATENÇÃO:</h5>
  <p><?php echo $enunciado->observacao; ?></p>
</div>
<?php } ?>

<?php if (!empty($enunciado->exemplo)) { ?>
<div class="w3-panel w3-pale-green w3-display-container">
  <span onclick="this.parentElement.style.display="none""
  class="w3-button w3-pale-green w3-small w3-display-topright">&times;</span>
  <h5>Exemplo:</h5>
  <p><?php echo $enunciado->exemplo; ?></p>
</div>
<?php } ?>

<?php echo  form_open("restrita/prof/next/pergunta", array("id" => "formsubcatgPergunta")); ?>

<input type="hidden" name="id_prof_subcateg" value="<?php echo $id_prof_subcateg; ?>">
<input type="hidden" name="id_prof_enunciado" value="<?php echo $enunciado->id_prof_enunciado; ?>">
<input type="hidden" name="id_subcategoria" value="<?php echo $enunciado->id_subcategoria; ?>">

<br>
<?php 
  $vlr_primeiro = "";
  $vlr_adicional = "";
  $vlr_porcent = "";
  $qntd = "";
  $checkbox = "";
  $faz_servico = "";
  $respondido = "";
  $id_prof_pergunta_resposta = "";
  $sn_vlr_sinal_todos_categ = "";
  $tipo = "";
?>

<?php foreach ($perguntas as $key => $pergunta) { ?>

  <?php 
    if (isset($respostas)){

      foreach ($respostas as $key => $resposta) {
        if ($resposta->id_prof_pergunta == $pergunta->id_prof_pergunta){
            $id_prof_pergunta_resposta = $resposta->id_prof_pergunta_resposta;
            $vlr_primeiro = $resposta->vlr_primeiro;
            $vlr_adicional = $resposta->vlr_adicional;
            $vlr_porcent = $resposta->vlr_porcent;
            $qntd = $resposta->qntd;
            $checkbox = empty($resposta->checkbox) ? "" : $resposta->checkbox == "S" ? "checked" : "";
            $faz_servico = empty($resposta->faz_servico) ? "" : $resposta->faz_servico == "S" ? "checked" : "";
            $sn_vlr_sinal_todos_categ = empty($resposta->sn_vlr_sinal_todos_categ) ? "" : $resposta->sn_vlr_sinal_todos_categ == "S" ? "checked" : "";
            $respondido = $resposta->respondido; 
        }
      } 
    }
  ?>

<input type="hidden" name="id_prof_pergunta_resposta[<?php echo $pergunta->id_prof_pergunta; ?>]" value="<?php echo $id_prof_pergunta_resposta ?>">
<input type="hidden" name="id_prof_pergunta[]" value="<?php echo $pergunta->id_prof_pergunta; ?>">


<?php 
//print_r($pergunta);
if ($pergunta->sn_pula_proxima_pergunta == 'S')
  echo "<input type=\"hidden\" name=\"sn_pula_proxima_pergunta[{$pergunta->id_prof_pergunta}]\" value=\"S\">";
if ($pergunta->sn_pula_categoria == 'S')
  echo "<input type=\"hidden\" name=\"sn_pula_categoria[{$pergunta->id_prof_pergunta}]\" value=\"S\">";
if ($pergunta->sn_vlr_visita == 'S')
  echo "<input type=\"hidden\" name=\"sn_vlr_visita[{$pergunta->id_prof_pergunta}]\" value=\"S\">";
if ($pergunta->sn_preco_por_qntde == 'S')
  echo "<input type=\"hidden\" name=\"sn_preco_por_qntde[{$pergunta->id_prof_pergunta}]\" value=\"S\">";
if ($pergunta->sn_qntd_por_add == 'S')
  echo "<input type=\"hidden\" name=\"sn_qntd_por_add[{$pergunta->id_prof_pergunta}]\" value=\"S\">";
if (!empty($pergunta->sinal))
  echo "<input type=\"hidden\" name=\"sinal[{$pergunta->id_prof_pergunta}]\" value=\"{$pergunta->sinal}\">";
if ($pergunta->sn_vlr_sinal == 'S')
  echo "<input type=\"hidden\" name=\"sn_vlr_sinal[{$pergunta->id_prof_pergunta}]\" value=\"S\">";
if (!empty($pergunta->ds_servico))
  echo "<input type=\"hidden\" name=\"ds_servico[{$pergunta->id_prof_pergunta}]\" value=\"{$pergunta->ds_servico}\">";
if (!empty($pergunta->ds_servico_remetente))
  echo "<input type=\"hidden\" name=\"ds_servico_remetente[{$pergunta->id_prof_pergunta}]\" value=\"{$pergunta->ds_servico_remetente}\">";
if (!empty($pergunta->sn_vlr_sinal_todos_categ))
  echo "<input type=\"hidden\" name=\"sn_vlr_sinal_todos_categ[{$pergunta->id_prof_pergunta}]\" value=\"{$pergunta->sn_vlr_sinal_todos_categ}\">";
echo "<input type=\"hidden\" name=\"tipo[{$pergunta->tipo}]\" value=\"{$pergunta->tipo}\">";
?>


  <!-- pergunta inicial / não -->
  <?php if($pergunta->perg_ini == "N") { ?>


  <?php if (!empty(trim($pergunta->pergunta))) { ?>
    <div class="w3-panel w3-yellow">
      <h5 class="w3-opacity"><?php echo $pergunta->pergunta; ?></h5>
    </div>
  <?php } ?>


  <div class="w3-row-padding">


    <?php if($pergunta->tem_vlr_qntd == "S") { ?>
      <?php if($pergunta->tem_vlr_primeiro == "S") { ?>
      <div class="w3-third">
        <label>Primeiro</label>
        <input class="w3-input w3-border" type="text" placeholder="Ex. R$ 100.00" name="vlr_primeiro[<?php echo $pergunta->id_prof_pergunta; ?>]" value="<?php echo $vlr_primeiro; ?>">
      </div>
      <div class="w3-third">
        <label>Quantidade</label>
        <input class="w3-input w3-border" type="text" placeholder="Ex. 12" name="qntd[<?php echo $pergunta->id_prof_pergunta; ?>]" value="<?php echo $qntd; ?>">
      </div>
      <div class="w3-third">
        <label>Não faço esse serviço</label><br>
        <input class="w3-check" type="checkbox" name="faz_servico[<?php echo $pergunta->id_prof_pergunta; ?>]" <?php echo $faz_servico; ?>>
      </div>
      <?php } else { ?>
      <div class="w3-third">
        <label>Primeiro</label>
        <input class="w3-input w3-border" type="text" placeholder="Ex. R$ 100.00" name="vlr_primeiro[<?php echo $pergunta->id_prof_pergunta; ?>]" value="<?php echo $vlr_primeiro; ?>">
      </div>
      <div class="w3-third">
        <label>Quantidade</label>
        <input class="w3-input w3-border" type="text" placeholder="Ex. 12" name="qntd[<?php echo $pergunta->id_prof_pergunta; ?>]" value="<?php echo $qntd; ?>">
      </div>
      <div class="w3-third">
        <label>Valor Adicional</label>
        <input class="w3-input w3-border" type="text" placeholder="Ex. R$ 100.00" name="vlr_adicional[<?php echo $pergunta->id_prof_pergunta; ?>]" value="<?php echo $vlr_adicional; ?>" >
      </div>
      <div class="w3-third">
        <label>Não faço esse serviço</label><br>
        <input class="w3-check" type="checkbox" name="faz_servico[<?php echo $pergunta->id_prof_pergunta; ?>]" <?php echo $faz_servico; ?>>
      </div>
      <?php } ?>
    <?php } else { ?>

      <?php if($pergunta->tem_vlr_primeiro == "S") { ?>
        <div class="w3-third">
          <label>Primeiro</label>
          <input class="w3-input w3-border" type="text" placeholder="Ex. R$ 100.00" name="vlr_primeiro[<?php echo $pergunta->id_prof_pergunta; ?>]" value="<?php echo $vlr_primeiro; ?>">
        </div>
      <?php } ?>

      <?php if($pergunta->tem_vlr_adicional == "S") { ?>
        <div class="w3-third">
          <label>Adicional</label>
          <input class="w3-input w3-border" type="text" placeholder="Ex. R$ 100.00" name="vlr_adicional[<?php echo $pergunta->id_prof_pergunta; ?>]" value="<?php echo $vlr_adicional; ?>">
        </div>
      <?php } ?>

      <?php if($pergunta->tem_faz_servico == "S") { ?>
        <div class="w3-third">
          <label>Não faço esse serviço</label><br>
          <input class="w3-check" type="checkbox" name="faz_servico[<?php echo $pergunta->id_prof_pergunta; ?>]" <?php echo $faz_servico; ?>>
        </div>
      <?php } ?>

    <?php } ?>


  </div>

  <!--tem_vlr_primeiro, tem_vlr_adicional, tem_vlr_procent, tem_vlr_qntd, tem_faz_servico, ordem -->

  <!-- Pergunta inicial / Sim -->
  <?php } else { ?>
    pergunta inicial sim
  <div class="w3-row-padding">
    <?php if ($pergunta->sn_checkbox == "S") { ?>

      <?php if ($pergunta->tem_vlr_adicional == "S") { ?>
        <div class="form-check">
          <label class="form-check-label">
            <input type="checkbox" class="form-check-input" name="tipo_estimativa" <?php echo empty($vlr_adicional) ? "" : "checked"; ?> > Valor 
            <input type="number" placeholder="Ex. R$ 100.00" id="vlr_adicional" name="vlr_adicional[<?php echo $pergunta->id_prof_pergunta; ?>]" value="<?php echo $vlr_adicional; ?>">
          </label>
        </div>    
      <?php } else if ($pergunta->tem_vlr_primeiro == "S") { ?>
        <div class="form-check">
          <label class="form-check-label">
            <input type="checkbox" class="form-check-input" name="tipo_estimativa" <?php echo empty($vlr_primeiro) ? "" : "checked"; ?> > Valor 
            <input type="number" placeholder="Ex. R$ 100.00" id="vlr_primeiro" name="vlr_primeiro[<?php echo $pergunta->id_prof_pergunta; ?>]" value="<?php echo $vlr_primeiro; ?>">
          </label>
        </div>    
      <?php } else { ?>
        
        <div class="form-check">
          <label class="form-check-label">
            <input type="checkbox" class="form-check-input" name="checkbox[<?php echo $pergunta->id_prof_pergunta; ?>]" <?php echo $checkbox; ?>> <?php echo $pergunta->pergunta; ?>
          </label>
        </div>
      <?php } ?>

    <?php } else { ?>

    <?php if ($pergunta->tem_vlr_primeiro == "S") { ?>
    <div class="w3-third">
      <!--<label>Adicional</label>-->
      <input class="w3-input w3-border" type="text" placeholder="Ex. R$ 100.00" name="vlr_primeiro[<?php echo $pergunta->id_prof_pergunta; ?>]" value="<?php echo $vlr_primeiro; ?>">
    </div>
    <?php } ?>

    <?php if ($pergunta->tem_vlr_adicional == "S") { ?>
    <div class="w3-third">
      <label>Adicionar</label>
      <input class="w3-input w3-border" type="text" placeholder="Ex. R$ 100.00" name="vlr_adicional[<?php echo $pergunta->id_prof_pergunta; ?>]" value="<?php echo $vlr_adicional; ?>">
    </div>
    <?php } ?>

    <?php if ($pergunta->tem_vlr_procent == "S") { ?>
    <div class="w3-third">
      <label>Acrescentar / Retirar</label>
      <input class="w3-input w3-border" type="text" placeholder="Ex. 10%" name="vlr_porcent[<?php echo $pergunta->id_prof_pergunta; ?>]" value="<?php echo $vlr_porcent; ?>">
    </div>
    <?php } ?>

    <?php } ?>



  </div>
  <?php } ?>
  <br>
<?php } ?>


<br><br>
<div class="w3-bar">
  <a href="<?php echo base_url("restrita/prof/prev/pergunta"); ?>" class="w3-button w3-red" rule="button">Voltar</a>
  <button class="w3-button w3-black">Terminar Mais Tarde</button>
  <button type="submit" class="w3-button w3-yellow">Próxima</button>
</div>

</form>

</div>
</div>
</div>
</div>
</div>
</div>
</div>
</section>
