<div class="col-sm-3 col-md-3">
    <div class="panel-group" id="accordion">

        <?php foreach($menuRestrita as $key => $menu) { ?>
        <?php if($menu->nome !== 'Relatório' || ($menu->nome === 'Relatório' && $this->session->userdata('tipo') === 'Profissional' && $this->session->userdata('plano') === 'Avançado')) { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <?php if(isset($menu->submenus)) { ?>
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $key + 1; ?>"><?php echo $menu->nome; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo base_url('restrita/' . $menu->url); ?>"><?php echo $menu->nome; ?></a>
                    <?php } ?>
                </h4>
            </div>
            <?php if(isset($menu->submenus)) { ?>
            <div id="collapse<?php echo $key + 1; ?>" class="panel-collapse collapse <?php echo $this->uri->segment(2) === $menu->url ? 'in' : 'out'; ?>">
                <div class="panel-body">
                    <table class="table">
                        <?php foreach($menu->submenus as $submenu) { ?>
                        <tr>
                            <td><a href="<?php echo base_url('restrita/' . $menu->url . '/' . $submenu->url); ?>"><?php echo $submenu->nome; ?></a></td>
                        </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
            <?php } ?>
        </div>
        <?php } ?>
        <?php } ?>

    </div>
</div>
