
<div class="col-sm-3 col-md-2">
   <div class=" panel-group" id="accordion">
 
     
        <?php foreach($menuRestrita as $key => $menu) { ?>
        <div class=" panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title" >
                    <?php if(isset($menu->submenus)) { ?>
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $key + 1; ?>" ><?php echo $menu->nome; ?></a>
                    <?php } else { ?>
                    <a  onclick="myFunction()" ><?php echo $menu->nome; ?></a>
                    <?php } ?>
                </h4>
            </div>
            <?php if(isset($menu->submenus)) { ?>
            <div id="collapse<?php echo $key + 1; ?>" class="panel-collapse collapse <?php echo $this->uri->segment(2) === $menu->url ? 'in' : 'out'; ?>">
                <div class="panel-body">
                    <table class="table">
                        <?php foreach($menu->submenus as $submenu) { ?>
                        <tr>
                            <td><a  onclick="myFunction()"></a></td>
                        </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
            <?php } ?>
        </div>
        <?php } ?> 
      
</div>
</div>


