<title>Cadastre-se - Lote sua Agenda e Aumente seu Lucro | Sossegue</title>
<meta content="Cadastre-se e faça parte do maior canal de serviços online!" name="description">
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
      <div class="container">
        <div class="row">
          <div class="col-md-6 col-md-offset-3 wow bounceInLeft txt-center" data-wow-duration="1s">
            <h1 class="ui-title-block">Cadastre-se</h1>
            <h5>Faça da Sossegue seu principal meio de anunciar seu serviço.</h5>
          </div>
          <!-- end col -->     
          
            <div class="container">
                <div class="row">
                    
                    <div class="col-md-4">
                    </div><!-- end col -->



                    <div class="col-md-4 col-md-offset-1 margin_g form_cadastro" align="center">                        
                        <?php echo form_open('cadastro/cadastrar', array('id' => 'formCadastro', 'class' => 'bounceInLeft form-b', 'data-wow-duration' => '2s')); ?>
                            <div class="form-request__text form-b col-sm-12">Cadastre-se agora</div>
                            <div class="col-sm-12"><h6>* campos obrigatórios</h6></div><br clear=all />
                            <div class="col-sm-12">
                                <select name="tipoCadastro" id="tipoCadastro" class="form-control form-b">
                                    <!--<option value="f" class="option_cad">* PESSOA FÍSICA</option>-->
                                    <option value="j" class="option_cad">* PESSOA JURÍDICA</option>
                                </select>
                            </div>
                            <div class="col-sm-12">
                                <!--<input class="form-control form-b hide" type="text" id="cpf" placeholder="* CPF" name="cpf" required>-->
                                <input class="form-control form-b" type="text" id="cnpj" placeholder="* CNPJ" name="cnpj">
                            </div>
                            <div class="col-sm-12">
                                <input class="form-control form-b" type="text" placeholder="* Nome Fantasia" name="nome" id="nome" required>
                            </div>
                            <div class="col-sm-12">
                                <input class="form-control form-b" type="mail" placeholder="* email" name="email" required>
                            </div>
                            <div class="col-sm-6">
                                <input class="form-control form-b" type="text" id="telefone" placeholder="* Telefone" name="telefone">
                            </div>
                            <div class="col-sm-6">
                                <input class="form-control form-b" type="text" id="celular" placeholder="* Celular" name="celular">
                            </div>

                            <div class="col-sm-6">
                                <select name="categoria" id="categoria" class="form-control form-b" required>
                                    <option value="" class="option_cad">* CATEGORIA</option>
                                    <?php foreach($listaCategorias as $categoria) { ?>
                                    <option value="<?php echo $categoria->id_categoria; ?>" class="option_cad"><?php echo $categoria->categoria; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <select name="subcategoria" id="subcategoria" class="form-control form-b" required>
                                    <option value="" class="option_cad">* SUB CATEGORIA</option>
                                </select>
                            </div>

                            <div class="col-sm-6">
                                <select name="estado" id="estado" class="form-control form-b" required>
                                    <option value="" class="option_cad">* ESTADO</option>
                                    <?php foreach($estados as $estado) { ?>
                                    <option value="<?php echo $estado->id; ?>" class="option_cad"><?php echo $estado->nome; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <select name="cidade" id="cidade" class="form-control form-b" required>
                                    <option value="" class="option_cad">* CIDADE</option>
                                </select>
                            </div>
                            
                            <div class="col-sm-12">
                                <input class="form-control form-b" type="password" placeholder="* Senha (min seis caracteres)" name="senha" id="senha" minlength=6 required >
                            </div>
                            <div class="col-sm-12">
                                <input class="form-control form-b" type="password" placeholder="* Confirmar senha" id="confSenha" minlength=6 required>
                            </div> 

                            <div class="col-sm-12">
                                <select name="plano" id="plano" class="form-control form-b" required>
                                    <option value="" class="option_cad">* PLANO E PERÍODO</option>
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
                                        <option value="<?php echo $plano->id_plano . '_' . strtolower($tipo); ?>" class="option_cad">
                                            <?php 
                                                if ($plano->plano != 'Avançado'){
                                                    if($preco == 30){
                                                        echo $tipo . ' - R$ '.$preco . '/mês';
                                                    } else {
                                                        echo $tipo . ' - R$ '.$preco . '/mês';
                                                    }
                                                } 
                                                if ($plano->plano == 'Grátis'){
                                                    echo $tipo . ' - R$ '.$preco;
                                                }
                                                if ($plano->plano == 'Avançado'){
                                                
                                                if ($tipo =='Mensal'){
                                                        echo $tipo . ' - R$ '.$preco . '/mês';
                                                } 
                                                if ($tipo == 'Trimestral'){
                                                    echo $tipo . ' - De <strike>R$ 35.00/mês</strike>, por R$ 0.00 no primeiro trimestre' ;
                                                }
                                                if ($tipo == 'Semestral'){
                                                        echo $tipo . ' - R$ '.$preco . '/mês';

                                                }
                                                if ($tipo == 'Anual'){
                                                        echo $tipo . ' - R$ '.$preco . '/mês';
                                                }
                                            } 
                                            ?>
                                                    


                                                </option>
                                        <?php } ?>
                                    </optgroup>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="checkbox-inline col-sm-12">                                 
                                <input type="checkbox" value="" class="check-concordo" required>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span style="font-family: Helvetica, Arial, sans-serif;">* CONCORDO COM OS <a data-toggle="modal" data-target=".bs-example-modal-lg">TERMOS E CONDIÇÕES</a></span><br /><br />
                            </div>

                            <?php if($termos) { ?>
                            <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                                <div class="modal-dialog modal-lg modal-termos">
                                    <div class="modal-content txt-cinza">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        <div class="modal-body"><?php echo $termos->texto; ?></div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>

                            <div class="col-md-8 col-md-offset-2">
                                <button class="btn btn-default btn-block btn-second btn-effect">Cadastrar</button>
                            </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
                
                <div class="row margin-g">
                    <div class="section-default  border-bottom_thin wow zoomIn" data-wow-duration="1s">
                        <div class="container">
                            <div class="row">
                                <div class="col-xs-12 txt-center">
                                    <h2 class="ui-title-block">Por que anunciar na sossegue</h2>
                                    <div class="decor-1 decor-1_mod-a decor-1_mod-d"></div>
                                </div>

                                <div class="col-xs-12">
                                    <ul class="advantages advantages_mod-a list-unstyled">
                                        <li class="advantages__item clearfix">
                                            <i class="icon flaticon-rocketship4"></i>
                                            <div class="advantages__inner">
                                                <div class="advantages__name">CONSIGA AINDA MAIS CLIENTES COM A SOSSEGUE</div>
                                                <div class="advantages__text">Experimente a plataforma, <a href="<?php echo base_url('cadastro')?>">cadastre-se</a> e Sossegue.</div>
                                            </div>
                                        </li>
                                        <li class="advantages__item clearfix">
                                            <i class="icon flaticon-badges3"></i>
                                            <div class="advantages__inner">
                                                <div class="advantages__name">CADASTRO GRATUITO</div>
                                                <div class="advantages__text">Você aumenta sua renda e não paga nada para fazer parte da nossa equipe de Sossegados!</div>
                                            </div>
                                        </li>
                                        <li class="advantages__item clearfix">
                                            <i class="icon flaticon-house158"></i>
                                            <div class="advantages__inner">
                                                <div class="advantages__name">AUMENTE SUA EXPOSIÇÃO NA INTERNET</div>
                                                <div class="advantages__text">Nosso site está entre os primeiros nas buscas do Google! Tenha ainda mais visibilidade na internet!</div>
                                            </div>
                                        </li>
                                    </ul>
                                </div><!-- end col -->
                            </div><!-- end row -->
                        </div><!-- end container -->
                    </div><!-- end section-default -->

                </div>
            </div>


        </div>
      </div>
    </section>


    <?php if($this->session->flashdata('msg')) { ?>
    <div class="modal fade" id="modalCadastro" tabindex="-1" role="dialog" style="margin-top: 80px">
      <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?php echo $this->session->flashdata('msg'); ?></h4>
          </div>
          <div class="modal-body">
            <button type="button" class="btn btn-info" data-dismiss="modal">Cancelar</button>
            <a href="<?php echo base_url('login'); ?>" type="button" class="btn btn-primary">Login</a>
          </div>
        </div>
      </div>
    </div>
    <?php } ?>
<?php include_once("analyticstracking.php") ?>
