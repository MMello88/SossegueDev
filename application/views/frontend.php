<!DOCTYPE html>
<html lang="pt-br">
<head>
<?php include_once('includes/metatags.php'); ?>
</head>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PBNS9N3"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

    <div class="layout-theme animated-css"  data-header="sticky" data-header-top="200">
      <div id="wrapper">

        <?php include_once('includes/header.php'); ?>
        <?php echo $content_for_layout; ?>
        <?php include_once('includes/footer.php'); ?>

        <input type="hidden" id="msg" value="<?php echo $msg;?>">
        <input type="hidden" id="siteUrl" value="<?php echo base_url();?>">

      </div>
    </div>
    <?php include_once('includes/scripts.php'); ?>
    <?php include_once('includes/scripts_restrito.php'); ?>
    <?php include_once("analyticstracking.php") ?>

</body>
</html>
