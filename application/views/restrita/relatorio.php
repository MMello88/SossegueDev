<?php include_once('includes/header.php'); ?>
<div style="overflow: hidden;">
    <h2><?php echo $titulo; ?></h2>
    <div class="col-sm-6">
        <select name="mes" id="mes" class="form-control">
            <option value="" class="option_cad">Mês</option>
            <?php foreach($meses as $key => $mes) { ?>
            <option <?php if($key + 1 === (int) date('m')) echo 'selected'; ?> value="<?php echo ($key + 1 < 10 ? '0' . $key + 1 : $key + 1); ?>"><?php echo $mes; ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="col-sm-6">
        <select name="ano" id="ano" class="form-control">
            <option value="" class="option_cad">Ano</option>
            <?php foreach(range(2016, date('Y')) as $ano) { ?>
            <option <?php if($ano === (int) date('Y')) echo 'selected'; ?> value="<?php echo $ano; ?>"><?php echo $ano; ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="col-sm-12">
          <script type="text/javascript" src="//www.gstatic.com/charts/loader.js"></script>
          <div id="chart_div"></div>
    </div>
</div>
<?php include_once('includes/footer.php'); ?>
<?php include_once("analyticstracking.php") ?>
