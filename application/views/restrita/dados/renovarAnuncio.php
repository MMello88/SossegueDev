<?php include_once(APPPATH . 'views/restrita/includes/header.php'); ?>
<?php echo form_open_multipart('restrita/dados/renovarAnuncio', array('id' => 'formCadastro')); ?>
<div class="col-sm-12">
    <select name="categoria" id="categoria" class="form-control" required>
        <option value="" class="option_cad">CATEGORIA</option>
        <?php foreach($listaCategorias as $categoria) { ?>
        <option value="<?php echo $categoria->id_categoria; ?>" class="option_cad"><?php echo $categoria->categoria; ?></option>
        <?php } ?>
    </select>
</div>
<div class="col-sm-12">
    <select name="subcategoria" id="subcategoria" class="form-control" required>
        <option value="" class="option_cad">SUB CATEGORIA</option>
    </select>
</div>
<div class="col-sm-12">
    <select name="plano" id="plano" class="form-control" required>
        <option value="" class="option_cad">PLANO E PERÍODO</option>
        <?php foreach($planos as $plano) { ?>
        <optgroup label="<?php echo $plano->plano; ?>">
            <?php foreach(array('Mensal', 'Trimestral', 'Semestral', 'Anual') as $tipo) {?>
            <?php switch($tipo) {
                case 'Trimestral':
                    $preco = $plano->trimestral;
                    break;
                case 'Semestral':
                    $preco = $plano->semestral;
                    break;
                case 'Anual':
                    $preco = $plano->anual;
                    break;
                default:
                    $preco = $plano->preco;
                    break;
            }
            ?>
            <option value="<?php echo $plano->id_plano . '_' . strtolower($tipo); ?>" class="option_cad"><?php echo $tipo . ($preco > 0 ? ' - R$' . $preco . '/mês' : ''); ?></option>
            <?php } ?>
        </optgroup>
        <?php } ?>
    </select>
</div>
<div class="col-sm-12">
    <button type="submit" class="btn btn-warning btn-block">Anunciar</button>
</div>
<?php echo form_close(); ?>
<div class="col-sm-12">
    <div class="row margin_g">
        <div class="col-xs-12 col-sm-offset-4 col-sm-8">
            <div class="row">
                <?php foreach($planos as $key => $plano) { ?>
                <div class="col-xs-4 my_planHeader my_plan<?php echo $key + 1; ?>">
                    <div class="my_planTitle"><?php echo $plano->plano; ?></div>
                    <div class="my_planPrice">R$<?php echo $plano->preco; ?></div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php foreach(array('Mensal', 'Trimestral', 'Semestral', 'Anual') as $tipo) { ?>
    <div class="row my_featureRow">
        <div class="col-xs-12 col-sm-4 my_feature"><?php echo $tipo; ?></div>
        <div class="col-xs-12 col-sm-8">
            <div class="row">
                <?php foreach($planos as $key => $plano) { ?>
                <?php
                switch($tipo) {
                    case 'Trimestral':
                        $preco = $plano->trimestral;
                        break;
                    case 'Semestral':
                        $preco = $plano->semestral;
                        break;
                    case 'Anual':
                        $preco = $plano->anual;
                        break;
                    default:
                        $preco = $plano->preco;
                        break;
                }
                ?>
                <div class="col-xs-4 col-sm-4 my_planFeature my_plan<?php echo $key + 1; ?>" style="padding-left: 5px !important;" >
                    <small class="txt_tabela">R$ <?php echo $preco . ($preco > 0 ? '/mês' : ''); ?></small>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php } ?>
    <?php foreach($planosOpcoes as $key => $opcao) { ?>
    <div class="row my_featureRow">
        <div class="col-xs-12 col-sm-4 my_feature"><?php echo $opcao->opcao; ?></div>
        <div class="col-xs-12 col-sm-8">
            <div class="row">
               <?php foreach($planos as $keyPlano => $plano) { ?>
                <div class="col-xs-4 col-sm-4 my_planFeature my_plan<?php echo $keyPlano + 1; ?>">
                   <?php if(!is_string($plano->opcoes[$opcao->id_planos_opcoes])) { ?>
                    <i class="fa <?php echo ($plano->opcoes[$opcao->id_planos_opcoes] ? 'fa-check my_check' : 'fa-close my_check_no'); ?>"></i>
                    <?php } else { ?>
                    <small class="txt_tabela"><?php echo $plano->opcoes[$opcao->id_planos_opcoes]; ?></small>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php } ?>
</div>
<?php include_once(APPPATH . 'views/restrita/includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
