<style type="text/css">
        .Buttom-Link{
            background-color: #fff;
            border: none;
        }
        .Button {
        -moz-box-shadow:inset 0px -3px 7px 0px #3dc21b;
        -webkit-box-shadow:inset 0px -3px 7px 0px #3dc21b;
        box-shadow:inset 0px -3px 7px 0px #3dc21b;
        background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #44c767), color-stop(1, #5cbf2a));
        background:-moz-linear-gradient(top, #44c767 5%, #5cbf2a 100%);
        background:-webkit-linear-gradient(top, #44c767 5%, #5cbf2a 100%);
        background:-o-linear-gradient(top, #44c767 5%, #5cbf2a 100%);
        background:-ms-linear-gradient(top, #44c767 5%, #5cbf2a 100%);
        background:linear-gradient(to bottom, #44c767 5%, #5cbf2a 100%);
        filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#44c767', endColorstr='#5cbf2a',GradientType=0);
        background-color:#44c767;
        -moz-border-radius:3px;
        -webkit-border-radius:3px;
        border-radius:3px;
        border:1px solid #18ab29;
        display:inline-block;
        cursor:pointer;
        color:#ffffff;
        font-family:Arial;
        font-size:17px;
        padding:9px 23px;
        text-decoration:none;
        text-shadow:0px 1px 0px #2f6627;
        }
        .Button:hover {
            background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #5cbf2a), color-stop(1, #44c767));
            background:-moz-linear-gradient(top, #5cbf2a 5%, #44c767 100%);
            background:-webkit-linear-gradient(top, #5cbf2a 5%, #44c767 100%);
            background:-o-linear-gradient(top, #5cbf2a 5%, #44c767 100%);
            background:-ms-linear-gradient(top, #5cbf2a 5%, #44c767 100%);
            background:linear-gradient(to bottom, #5cbf2a 5%, #44c767 100%);
            filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#5cbf2a', endColorstr='#44c767',GradientType=0);
            background-color:#5cbf2a;
        }
        .Button:active {
            position:relative;
            top:1px;
        }
        
        .list-unstyled, .list-inline {
            padding-top: 15px;
        }
        .list-unstyled > li{
            padding-bottom: 5px;
        }
        
        .img-responsive {
            border-radius: 3px;
            padding-right: 5px;
        }
        
        .team-box-cont {
            border-bottom: 1px solid #CCC;
        }
        .team-box {
            background: #FFF;
            color: #000;
            border: 1px solid #CCC;
            border-radius: 3px;
            padding-bottom: 15px;
            padding-top: 15px;
            margin-bottom: 15px;
        }
        .bottom-box {
            background: #FFF;
            color: #000;
            border-top: 1px solid #CCC;
            border-radius: 3px;
            padding-bottom: 10px;
            padding-top: 10px;
            margin-bottom: 10px;
        }
    </style>

<meta name="robots" content="index, follow">
<meta name=viewport content="width=device-width, initial-scale=1">
<script type='application/ld+json'> 
    {
        "@context": "http://www.schema.org",
        "@type": "WebSite",
        "name": "Sossegue",
        "url": "http://www.sossegue.com.br"
    }
 </script>
 
<section class="section_mod-d border-top">
<?= form_open('Carrinho/Finalizar'); /*Carrinho/Finalizar*/ ?>    
    <div class="container">
        <div class="row" style="padding:30px 0px 30px 0px;">
            <div class="col-md-4"></div>
            <div class="col-md-4"></div>
            <div class="col-md-4" style="padding:0px 30px 0px 30px;">
                <div class="header-contacts">
                    <?php if (empty($mixs)) { /*aqui vamos mudar, vai ser hablitado quando selecionar qual mix desejar*/ ?>
                    <button type="submit" class="btn btn-danger btn-lg disabled" align="center" id="btn8"  data-loading-text="Finalizando o Pedido" rule="button" >Finalizar o Pedido</button>
                    <?php } else { ?>
                    <button type="submit" class="Button btn btn-default" align="center" id="btn8"  data-loading-text="Finalizando o Pedido" rule="button" >Finalizar o Pedido</button>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

<?php foreach($mixs as $key_mix => $mix) { ?> <!-- forecah -->
    <!-- Barra com as categorias -->
    <div class="container">
        <div class="row">
            
            <div class="col-xs-12 col-md-12">
                <div class="col-xs-1 col-md-1">
                </div>
                <div class="col-xs-12 col-md-5">
                    <h3><font color="#aaa">Descrição do Produto</font></h3>
                </div>
                <div class="col-xs-12 col-md-3">
                    <h3><font color="#aaa">Quantidade</font></h3>
                </div>
                <div class="col-xs-12 col-md-3">
                    <h3><font color="#aaa"></font></h3>
                </div>
            </div>
        </div>
    </div>
        
    <div class="container">
        <div class="row team-box" style="padding-left: 15px; padding-right: 15px;">
            <div class="container">
                <div class="row">

                    <?php foreach($mix->profs as $prof) { ?> <!-- forecah profs -->
                    
                    <div class="col-xs-12 col-md-12">
					<!--<input type="radio" class="chb<?= $key_mix; ?>" name="selected[<?= $key_mix; ?>]" value="<?= $prof->id_profissional; ?>">-->
                        <h5><?= $prof->nome ?></h5>
                    <?php 
                        if(isset($prof->prof_subcategs)){

                            $total_visita = 0;
                            $total_resposta = 0;
                            $total = 0;
                            foreach ($prof->respostas as $key => $valueResposta) {
                                echo "<input type='radio' class='chb$key_mix' name='selected[id_subcategoria][$valueResposta->id_subcategoria]' value='$prof->id_profissional' required>";
                                echo "<input type='hidden' name='id_orcamento' value='$prof->id_orcamento'>";
                                
                                $sub_total_resposta = 0;

                                echo '<br/>';
                                echo 'id_prof_pergunta_resposta: ' . $valueResposta->id_prof_pergunta_resposta. '<br/>';
                                echo 'id_prof_enunciado: ' . $valueResposta->id_prof_enunciado. '<br/>';
                                echo 'id_prof_pergunta: ' . $valueResposta->id_prof_pergunta. '<br/>';
                                echo 'vlr_primeiro: ' . $valueResposta->vlr_primeiro. '<br/>';
                                echo 'vlr_adicional: ' . $valueResposta->vlr_adicional. '<br/>';
                                echo 'vlr_porcent: ' . $valueResposta->vlr_porcent. '<br/>';
                                echo 'qntd: ' . $valueResposta->vlr_qntd. '<br/>';
                                echo 'key: ' . $key;
                                

                                if ($key === 0){
                                    if ($valueResposta->vlr_qntd === 0 && $valueResposta->qntd === 1) {
                                        $sub_total_resposta = $sub_total_resposta + $valueResposta->vlr_primeiro;
                                    } else {
                                        $sub_total_resposta = $sub_total_resposta + $valueResposta->vlr_primeiro;
                                        if ($valueResposta->qntd > $valueResposta->vlr_qntd && $valueResposta->qntd > 1)
                                            $sub_total_resposta = $sub_total_resposta + ($valueResposta->vlr_adicional*($valueResposta->qntd-$valueResposta->vlr_qntd-1));
                                    }
                                } else {
                                    $sub_total_resposta = $sub_total_resposta + ($valueResposta->vlr_adicional*($valueResposta->qntd));
                                }

                                echo "<br/>Valor Respondido $sub_total_resposta <br/>";
                                $total_resposta = $total_resposta + $sub_total_resposta;

                                
                                $valueResposta->id_subcategoria = $valueResposta->id_subcategoria;
                                $valueResposta->id_orcamento = $prof->id_orcamento;
                                $valueResposta->id_profissional = $prof->id_profissional;
                                if(isset($valueResposta->Pedido)){
                                    $valueResposta->id_pedido = $valueResposta->id_pedido;
                                    unset($valueResposta->Pedido);
                                }
                                $value = json_encode($valueResposta);
                                echo "<input type='hidden' name='resposta[$valueResposta->id_subcategoria][$prof->id_profissional][]' value='$value'>";
                            
                                    
                            }

                                echo "</br>";
                            
                            echo "Total: $total_resposta <br/>";
                        } ?>
                    </div>
                    <?php } ?> <!-- endforecah profs -->
                </div>
            </div>
            <?php foreach($mix->pedidos as $key_pedido => $pedido) { ?> <!-- forecah pedidos -->
                <div class="col-xs-12 col-md-12 bottom-box">

                    <div class="col-xs-12 col-md-6">
                        <div class="row">
                            <div class="col-xs-5 col-md-3">
                                <img src="<?php echo base_url('assets/media/categoria_servico/'.$pedido->imagem); ?>" class="img-responsive" alt="Rack" height="100" width="100" align="left">
                            </div>
                            <div class="col-xs-7 col-md-9">
                                <ul class="list-unstyled">
                                    <li><strong><?php echo $pedido->descricao; ?></strong></li>
                                    <li>Categoria <?php echo $pedido->descricao; ?></li>
                                    <li>Id Servico <?php echo $pedido->id_servico; ?></li>
                                    <li>Servico de <?php echo $pedido->descricao; ?></li>
                                    <?php foreach($pedido->filtros as $key => $filtro) { ?> <!-- forecah -->
                                    <?php if (!empty($filtro->filtro)) { ?>
                                        <input type="hidden" name="perg_descr[<?= $prof->id_profissional; ?>]" value="<?= $filtro->pergunta; ?>">
                                        <input type="hidden" name="id_pergunta[<?= $prof->id_profissional; ?>]" value="<?= $filtro->id_pergunta; ?>">
                                        <input type="hidden" name="filtro_descr[<?= $prof->id_profissional; ?>]" value="<?= $filtro->filtro; ?>">
                                        <input type="hidden" name="id_filtro[<?= $prof->id_profissional; ?>]" value="<?= $filtro->id_filtro; ?>">
                                        <input type="hidden" name="valor[<?= $prof->id_profissional; ?>]" value="<?= $filtro->valor; ?>">
                                        <li><?php echo $filtro->pergunta. ': ' .$filtro->filtro; ?></li>
                                    <?php } else { ?>
                                        <li><?php echo $filtro->pergunta. ': ' .$filtro->valor; ?></li>
                                    <?php }
                                        } ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-2" style="padding-top: 15px;">
                        <div class="form-group">
                            <div class="input-group">
                              <div class="input-group-addon btn" id="menos"><font style="font-size:20px;"><strong> - </strong></font></div>
                              <input type="hidden" id="id_pedido" name="id_pedido" value="<?php echo $pedido->id_pedido; ?>" required>
                              <input type="hidden" id="id_servico" name="id_servico" value="<?php echo $pedido->id_servico; ?>" required>
                              <input type="hidden" id="id_orcamento" name="id_orcamento" value="<?php echo $pedido->id_orcamento; ?>" required>
                              <input type="text" id="qntd" name="qntd" class="form-control"  placeholder="Qtde" style="text-align: center;" value="<?php echo $pedido->qntd; ?>" maxlength="2" required>
                              <div class="input-group-addon btn" id="mais"><font style="font-size:20px;"><strong> + </strong></font></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-4" style="padding-top: 15px;">
                        <ul class="list-inline text-right">
                            <li><a href="<?php echo base_url('Carrinho/Remover/'.$pedido->id_pedido); ?>"><font face="Arial" size="3" color="#046C00">Remover Pedido</font></a></li>
                        </ul>
                    </div>

                </div>
                
            
            <?php } ?> <!-- forecah pedidos -->
        </div>
    </div>
	

<?php } ?> <!-- mix forecah -->
<?php echo form_close(); /*Carrinho/Alterar*/ ?>

    <div class="container">
        <div class="row" style="padding:0px 15px 15px 15px;">  
            <div class="team-box-cont">
                <h5><a href="<?php echo base_url('Manutencao/'.$url); ?>"><font color="#333">Adicionar mais serviços</font></a></h5>
            </div>
        </div>
    </div>
    
</section>
 
   <?php include_once("analyticstracking.php") ?>