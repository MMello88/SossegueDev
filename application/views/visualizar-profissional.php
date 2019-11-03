<meta name="robots" content="noindex, nofollow">
<section class="section_mod-d border-top" id="busca">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h1 class="ui-title-block"><?php echo $profissional->nome; ?></h1>
                <div class="decor-1 decor-1_mod-a"></div>
            </div>
            <?php
            echo("<pre>");
            print_r($profissional);
            echo("</pre>");
            ?>
        </div>
    </div>
</section>
<?php include_once("analyticstracking.php") ?>
