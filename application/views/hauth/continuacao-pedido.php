<style type="text/css">
    a.Button{
        color: #fff;
        background-color: #5cb85c;
        font-family:Lato;
        text-transform:none;
        text-decoration: none;
        font-weight:500;
        border:solid 2px;
        border-color: #4cae4c;
    }
    
    .Button:hover, .Button:focus, .Button.focus {
        text-decoration: none;
        color: #fff;
        background-color: #449d44;
        border-color: #398439;
    }
    
    .Button-ab {
        color:#999;
        border:solid 2px;
        font-family:Lato;
        text-transform:none;
        font-weight:500;
    }
    
    div.team-box {
        width: 260px;
        background: #FFF;
        color: #000;
        padding: 15px 13px 15px 13px;

        border: 1px solid #CCC;
        border-radius: 3px;
    }
    div.team-box a img{
        border: 1px solid #CCC;
        border-radius: 3px;
    }
    
    .img-responsive {
        border-radius: 3px;
    }
    option.selected{
        color: #ccc !important;
    }
    
    .top-down {
        margin-top: 72px;
    }
    .bottom-top {
        margin-bottom: 60px;
    }
    .bottom-right {
        text-align: right;
    }
</style>
<title>Tenha 3 Orçamentos de <?= $subcategoria->subcategoria; ?> de Ribeirão Preto | Sossegue</title>
<meta content="Encontre <?= $subcategoria->subcategoria; ?> confiáveis e qualificados, de forma rápida e prática! Na Sossegue você receberá 3 Orçamentos dos Melhores <?= $subcategoria->subcategoria; ?> da sua Região." name="description">
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
    
    
<!-- Button trigger modal -->
<!--
<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#infoModal">
  Como realizar um pedido.
</button>
-->

<!-- Modal -->
<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Como realizar um pedido.</h4>
      </div>
      <div class="modal-body">
        <iframe width="860" height="515" src="https://www.youtube.com/embed/tgVuIxv3jWw" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
      </div>
    </div>
  </div>
</div>

    <div class="container">
        <div class="row" style="padding-bottom:15px;">
            <div class="col-md-6" style="padding-left:30px;">
                <?php if ($subcategoria->subcategoria == 'Marido de Aluguel') { ?>
                <h1><strong><font color="#434345">Serviços de Fixação</font></strong></h1>
                <?php } else { ?>
                <h1><strong><font color="#434345">Serviços de <?php echo $subcategoria->subcategoria; ?></font></strong></h1>
                <?php } ?>
            </div>
            <div class="col-md-2">
            </div>

            <div class="col-md-4 yamm-scroll" style="padding-left:30px;">
                <div class="header-contacts">
                    <a class="btn btn-success btn-lg Button" href="<?php echo base_url('Carrinho'); ?>">
                        <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span>
                        <span class="glyphicon-class">Seu Carrinho (<?php echo $total_pedido->total_pedido; ?>)</span>
                    </a>
                </div>
            </div>

            <?php /*
            <div class="col-md-4" style="padding:0px 30px 0px 30px;">
                <?php echo form_open('Manutencao/actionPedido', array('id' => 'formCadastro', 'class' => 'bounceInLeft form-b', 'data-wow-duration' => '2s')); ?>
                    <div class="header-contacts">
                        <button class="Button" align="center" name="submit_type" value="finalizarPedido">Finalizar o Pedido</button>
                    </div>
                <?php echo form_close(); ?>
            </div>
             */?>
        </div>
    </div>

<?php if($this->session->flashdata('suss')) { ?>
<div class="modal fade" id="modalLogin" tabindex="-1" role="dialog" style="margin-top: 80px">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h2 class="modal-title"><?php echo $this->session->flashdata('suss'); ?></h2>
        <class="modal-body"><a href="http://www.localhost:8888/Sossegue/Sossegue/Carrinho">Ir para o carrinho</a>

      </div>
    </div>
  </div>
</div>
<?php } ?>
    <div class="container">
        <div class="row">
        <?php $idNumber = 0; ?>
        <?php foreach ($categoria_servicos as $categoria_servico) { ?>
        <?php if (($idNumber%4) == 0) { ?>
        </div>
        <div class="clearfix visible-xs-block"></div>
        <div class="row">
        <?php } ?>
        <?php $idNumber += 1; ?>
            <div class="col-sm-5 col-md-4 col-lg-3" style="padding-bottom:30px;">
                <div class="team-box">
                    <a><img src="<?php echo base_url('assets/media/categoria_servico/'.$categoria_servico->imagem); ?>" class="img-responsive" alt=""></a>
                    <div class="team-box-title">
                        <h5><font color="#434345"><?php echo $categoria_servico->descricao; ?></font></h5>
                    </div>
                    <?php echo form_open('Manutencao/actionPedido', array('id' => 'formCadastro', 'class' => 'bounceInLeft form-horizontal', 'data-wow-duration' => '1s')); ?>
                        <input name="id_orcamento" value="<?php echo $id_orcamento; ?>" type="hidden" required/>

                        <select class="form-control" id="<?php echo $idNumber; ?>" name="id_servico" required>
                            <option value="" selected disabled=""> <?php echo 'Selecione o(a) '.$categoria_servico->pergunta_1; ?> </option>
                            <?php foreach ($categoria_servico->Servicos as $servico) { ?>
                            <option value="<?php echo $servico->id_servico; ?>"><?php echo $servico->descricao; ?></option> 
                            <?php } ?>
                        </select>

                        <div class="result"></div>

                        <div class="form-group" style="margin:0px;">
                            <label for="inputQntde">Quantidade</label>
                            <input name="qntd" type="text" class="form-control" id="InputQtde" placeholder="* Quantidade" required>
                        </div>
                        <div>
                            <button class="header-contacts__info btn_1" id="btn8"  data-loading-text="Verificando os campos" style="margin-left: 21px;" name="submit_type" value="addCart" autocomplete="off">Adicionar ao Carrinho</button>
                        </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        <?php } ?>
    </div> 
    
</section>
<?php include_once("analyticstracking.php") ?>
