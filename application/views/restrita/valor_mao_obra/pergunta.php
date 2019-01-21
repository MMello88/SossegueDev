<?php include_once(APPPATH . "views/restrita/includes/header.php"); ?>

<div id="msgError"></div>

<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<?php //print_r($ProfSubCatg); ?>
<h3><?php echo $ProfSubCatg->SubCategoria->subcategoria; ?></h3>
<hr size="0.1" style="border:1px solid ;">
<br>
<h3><?php echo $ProfSubCatg->Enunciado->titulo; ?></h3><br>

<?php if (!empty($ProfSubCatg->Enunciado->informativo)) { ?>
<div class="w3-panel w3-yellow w3-display-container">
  <span onclick="this.parentElement.style.display="none""
  class="w3-button w3-yellow w3-small w3-display-topright">&times;</span>
  <h5>Informativo:</h5>
  <p><?php echo $ProfSubCatg->Enunciado->informativo; ?></p>
</div>
<?php } ?>

<?php if (!empty($ProfSubCatg->Enunciado->observacao)) { ?>
<div class="w3-panel w3-pale-red w3-display-container">
  <span onclick="this.parentElement.style.display="none""
  class="w3-button w3-pale-red w3-small w3-display-topright">&times;</span>
  <h5>ATENÇÃO:</h5>
  <p><?php echo $ProfSubCatg->Enunciado->observacao; ?></p>
</div>
<?php } ?>

<?php if (!empty($ProfSubCatg->Enunciado->exemplo)) { ?>
<div class="w3-panel w3-pale-green w3-display-container">
  <span onclick="this.parentElement.style.display="none""
  class="w3-button w3-pale-green w3-small w3-display-topright">&times;</span>
  <h5>Exemplo:</h5>
  <p><?php echo $ProfSubCatg->Enunciado->exemplo; ?></p>
</div>
<?php } ?>

<?php echo  form_open("restrita/prof/pergunta/$idSubcategoria/", array("id" => "formsubcatgPergunta")); ?>
<input type="hidden" name="id_prof_subcateg" value="<?php echo $ProfSubCatg->id_prof_subcateg; ?>">
<input type="hidden" name="id_prof_enunciado" value="<?php echo $ProfSubCatg->Enunciado->id_prof_enunciado; ?>">
<input type="hidden" name="id_subcategoria" value="<?php echo $ProfSubCatg->Enunciado->id_subcategoria; ?>">

<br>

<?php 
	foreach ($ProfSubCatg->Enunciado->Perguntas as $key => $pergunta) { 
    $checkbox    = empty($pergunta->checkbox)    ? "" : $pergunta->checkbox    == "S" ? "checked"  : "";
    $faz_servico = empty($pergunta->faz_servico) ? "" : $pergunta->faz_servico == "S" ? "checked"  : "";
    $readonly    = empty($pergunta->faz_servico) ? "" : $pergunta->faz_servico == "S" ? "readonly" : ""; 

    $class_check = "c".$pergunta->id_prof_pergunta;
    $class_vp    = "vp".$pergunta->id_prof_pergunta;
    $class_va    = "va".$pergunta->id_prof_pergunta;
    $class_qtde  = "qtde".$pergunta->id_prof_pergunta;    
  ?>

<input type="hidden" name="id_prof_pergunta_resposta[<?= $pergunta->id_prof_pergunta; ?>]" value="<?= $pergunta->id_prof_pergunta_resposta ?>">
<input type="hidden" name="id_prof_pergunta[]" value="<?= $pergunta->id_prof_pergunta; ?>">


  <!-- pergunta inicial / não -->
  <?php if($pergunta->perg_ini == "N") { ?>


  <?php if (!empty($pergunta->pergunta)) { ?>
    <div class="w3-panel w3-yellow">
      <h5 class="w3-opacity"><?= $pergunta->pergunta ?></h5>
    </div>
  <?php } ?>

  <div class="w3-row-padding">
    <?php if($pergunta->tem_vlr_qntd == "S") { ?>
      <?php if($pergunta->tem_vlr_primeiro == "S") { ?>

      <div class="w3-third">
        <label>Primeiro</label>
        <input class="w3-input w3-border" id="<?= $class_vp; ?>" type="text" placeholder="Ex. R$ 100.00" name="vlr_primeiro[<?= $pergunta->id_prof_pergunta ?>]" value="<?= $pergunta->vlr_primeiro ?>" <?= $readonly ?> >
      </div>
      <div class="w3-third">
        <label>Quantidade</label>
        <input class="w3-input w3-border" id="<?= $class_qtde ?>" type="text" placeholder="Ex. 12" name="qntd[<?= $pergunta->id_prof_pergunta ?>]" value="<?= $pergunta->vlr_qntd ?>" <?= $readonly ?>>
      </div>
      <div class="w3-third">
        <label>Não faço esse serviço</label><br>
        <input class="w3-check" id="<?= $class_check; ?>" data-whatever="<?= $pergunta->id_prof_pergunta; ?>" type="checkbox" name="faz_servico[<?= $pergunta->id_prof_pergunta; ?>]" <?= $faz_servico ?>>
      </div>
      <?php } else { ?>
      <div class="w3-third">
        <label>Primeiro</label>
        <input class="w3-input w3-border" id="<?= $class_vp ?>" type="text" placeholder="Ex. R$ 100.00" name="vlr_primeiro[<?= $pergunta->id_prof_pergunta ?>]" value="<?= $pergunta->vlr_primeiro; ?>" <?= $readonly ?>>
      </div>
      <div class="w3-third">
        <label>Quantidade</label>
        <input class="w3-input w3-border" id="<?= $class_qtde ?>" type="text" placeholder="Ex. 12" name="qntd[<?= $pergunta->id_prof_pergunta ?>]" value="<?= $pergunta->vlr_qntd; ?>" <?= $readonly ?>>
      </div>
      <div class="w3-third">
        <label>Valor Adicional</label>
        <input class="w3-input w3-border" id="<?= $class_va ?>" type="text" placeholder="Ex. R$ 100.00" name="vlr_adicional[<?= $pergunta->id_prof_pergunta ?>]" value="<?= $pergunta->vlr_adicional ?>" <?= $readonly ?>>
      </div>
      <div class="w3-third">
        <label>Não faço esse serviço</label><br>
        <input class="w3-check" id="<?= $class_check ?>" data-whatever="<?= $pergunta->id_prof_pergunta; ?>" type="checkbox" name="faz_servico[<?= $pergunta->id_prof_pergunta ?>]" <?= $faz_servico ?>>
      </div>
      <?php } ?>
    <?php } else { ?>

      <?php if($pergunta->tem_vlr_primeiro == "S") { ?>
        <div class="w3-third">
          <label>Primeiro</label>
          <input class="w3-input w3-border" id="<?= $class_vp ?>" type="text" placeholder="Ex. R$ 100.00" name="vlr_primeiro[<?= $pergunta->id_prof_pergunta ?>]" value="<?= $pergunta->vlr_primeiro ?>" <?= $readonly ?>>
        </div>
      <?php } ?>

      <?php if($pergunta->tem_vlr_adicional == "S") { ?>
        <div class="w3-third">
          <label>Adicional</label>
          <input class="w3-input w3-border" id="<?= $class_va ?>" type="text" placeholder="Ex. R$ 100.00" name="vlr_adicional[<?= $pergunta->id_prof_pergunta ?>]" value="<?= $pergunta->vlr_adicional ?>" <?= $readonly ?>>
        </div>
      <?php } ?>

      <?php if($pergunta->tem_faz_servico == "S") { ?>
        <div class="w3-third">
          <label>Não faço esse serviço</label><br>
          <input class="w3-check" id="<?= $class_check ?>" data-whatever="<?= $pergunta->id_prof_pergunta; ?>" type="checkbox" name="faz_servico[<?= $pergunta->id_prof_pergunta ?>]" <?= $faz_servico ?>>
        </div>
      <?php } ?>

    <?php } ?>


  </div>

  <!--tem_vlr_primeiro, tem_vlr_adicional, tem_vlr_procent, tem_vlr_qntd, tem_faz_servico, ordem -->

  <!-- Pergunta inicial / Sim -->
  <?php } else { ?>
    
  <div class="w3-row-padding">
    <?php if ($pergunta->sn_checkbox == "S") { ?>

      <?php if ($pergunta->tem_vlr_adicional == "S") { ?>
        <div class="form-check">
          <label class="form-check-label">
            <input type="checkbox" class="form-check-input" name="tipo_estimativa" <?= empty($pergunta->vlr_adicional) ? "" : "checked" ?> > Valor 
            <input type="number" placeholder="Ex. R$ 100.00" id="vlr_adicional" name="vlr_adicional[<?= $pergunta->id_prof_pergunta ?>]" value="<?= $pergunta->vlr_adicional ?>">
          </label>
        </div>    
      <?php } else if ($pergunta->tem_vlr_primeiro == "S") { ?>
        <div class="form-check">
          <label class="form-check-label">
            <input type="checkbox" class="form-check-input" name="tipo_estimativa" <?= empty($pergunta->vlr_primeiro) ? "" : "checked" ?> > Valor 
            <input type="number" placeholder="Ex. R$ 100.00" id="vlr_primeiro" name="vlr_primeiro[<?= $pergunta->id_prof_pergunta ?>]" value="<?= $pergunta->vlr_primeiro ?>">
          </label>
        </div>    
      <?php } else { ?>
        
        <div class="form-check">
          <label class="form-check-label">
            <input type="radio" class="form-check-input cbx" name="checkbox[<?= $pergunta->id_prof_pergunta ?>]" <?= $checkbox ?>> <?= $pergunta->pergunta ?>
          </label>
        </div>
      <?php } ?>

    <?php } else { ?>

    <?php if ($pergunta->tem_vlr_primeiro == "S") { ?>
    <div class="w3-third">
      <!--<label>Adicional</label>-->
      <input class="w3-input w3-border" type="text" placeholder="Ex. R$ 100.00" name="vlr_primeiro[<?= $pergunta->id_prof_pergunta ?>]" value="<?= $pergunta->vlr_primeiro; ?>">
    </div>
    <?php } ?>

    <?php if ($pergunta->tem_vlr_adicional == "S") { ?>
    <div class="w3-third">
      <label>Adicionar</label>
      <input class="w3-input w3-border" type="text" placeholder="Ex. R$ 100.00" name="vlr_adicional[<?= $pergunta->id_prof_pergunta ?>]" value="<?= $pergunta->vlr_adicional; ?>">
    </div>
    <?php } ?>

    <?php if ($pergunta->tem_vlr_procent == "S") { ?>
    <div class="w3-third">
      <label>Acrescentar / Retirar</label>
      <input class="w3-input w3-border" type="text" placeholder="Ex. 10%" name="vlr_porcent[<?= $pergunta->id_prof_pergunta ?>]" value="<?= $pergunta->vlr_porcent ?>">
    </div>
    <?php } ?>

    <?php } ?>

  </div>
  <?php } ?>
  <br>
<?php } ?>



<br><br>
<div class="w3-bar">
  <?php if(isset($prev_ordem)) : ?>    
    <button type="submit" name="btnPrev" value="<?= $prev_ordem ?>" class="w3-button w3-red" id="btnPost">Anterior</button>
  <?php else: ?>
    <a href="<?php echo base_url("restrita/prof/mensagem/aviso"); ?>" class="w3-button w3-red" rule="button">Aviso Inicial</a>
  <?php endif; ?>

  <?php if(isset($cur_ordem)) : ?>
    <button type="submit" name="btnCur" value="<?= $cur_ordem ?>" class="w3-button w3-black" id="btnPost">Terminar Mais Tarde</button>
  <?php endif; ?>

  <?php if(isset($next_ordem)) : ?>
    <button type="submit" name="btnNext" value="<?= $next_ordem ?>" class="w3-button w3-yellow" id="btnPost">Próxima</button>
  <?php endif; ?>
</div>
<br><br>

<?= $pagination_enunciado ?>

<?= $pagination_subcatego ?>

</form>

</div>
</div>
</div>
</div>
</div>
</div>
</div>
</section>