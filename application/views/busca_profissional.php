<meta name="robots" content="noindex, nofollow">
    <section class="section_mod-d border-top">
      <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3 wow bounceInLeft txt-center" data-wow-duration="1s">
                <h1 class="ui-title-block">FAÇA SEU PEDIDO DE ORÇAMENTO RÁPIDO</h1>
            </div>
                <div class="col-md-6 txt-center txt-banner">
            <h1>ENCONTRE PROFISSIONAIS PARA <br />FACILITAR SUA VIDA</h1><br />
            <?php echo form_open('busca/profissionais', array('method' => 'get')) ?>
                <div class="form-group col-md-4">    
                    <input type="text" name="profissao" value="<?php echo set_value('profissao') ?>" id="buscaProfissao" class="form-control" placeholder="Profissão" required>
                </div>
                <div class="form-group col-md-4">    
                    <input type="text" name="cidade" id="buscaCidade" value="<?php echo set_value('cidade') ?>" class="form-control" placeholder="Cidade" required>
                </div>
                <div class="form-group col-md-4">    
                    <button type="submit" class="btn btn-default btn-default-cinza btn-second btn-effect btn-banner-a">Buscar</button>
                </div>
            <?php echo form_close() ?>
        </div>
    </div>
</div>
</section>

