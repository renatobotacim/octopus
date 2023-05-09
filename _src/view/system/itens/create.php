<?php
//VERIFICA A EXISTÊNCIA DE UM LOGIM, CASO SIM, ELE SEMPRE DIRECIONA PARA O PAINEL, CASO NÃO, DIRECIONA A TELA DE LOGIN
$checkLevel = new Login(2);
if (!$checkLevel->CheckLevel()):
    $link = 'painel.php?restrito=true'; // especifica o endereço
    redireciona($link); // chama a função
endif;

if (!class_exists('Login')) :
    header('Location: ../../index.php');
    die;
endif;

$Data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!empty($Data['sendForm'])):
    unset($Data['sendForm']);
    $Data['produto_empresa_id'] = $userlogin['usuario_empresa_id'];
    $Data['produto_status'] = 2;

    require_once ('../Controllers/produtosController.class.php');
    $controller = new produtosController();
    $controller->create($Data);

endif;

$icone = "fas fa-users";
require_once 'includes/navegador.php';
?>
<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">
                    <i class="fas fa-info-circle"></i>&nbsp;Dados do Item</h3>
            </div>
            <form name="fileForm" action="" method="post" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Código Interno</label>
                                <input class="form-control form-control-sm" type="text" name="produto_codigo_interno" id="produto_codigo_interno"  value="<?php if (isset($Data)) echo $Data['produto_codigo_interno'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3"> 
                            <div class="form-group">
                                <label>Código Externo</label>
                                <input class="form-control form-control-sm" type="text"  name="produto_codigo_externo" id="produto_codigo_externo" value="<?php if (isset($Data)) echo $Data['produto_codigo_externo'] ?>">
                            </div>
                        </div>
                        <div class="col-md-6"> 
                            <div class="form-group">
                                <label>Nome do produto</label>
                                <input class="form-control form-control-sm" type="text"  name="produto_nome" id="produto_nome" value="<?php if (isset($Data)) echo $Data['produto_nome'] ?>">
                            </div>
                        </div>

                        <div class="col-md-6"> 
                            <div class="form-group">
                                <label>Descricao</label>
                                <input class="form-control form-control-sm" type="text"  name="produto_descricao" id="produto_nome" value="<?php if (isset($Data)) echo $Data['produto_descricao'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Valor de Compra</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                    </div>
                                    <input class="form-control form-control-sm real"  name="produto_valor_compra" id="produto_valor_compra" type="text" value="<?php if (isset($Data)) echo $Data['produto_valor_compra'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Valor de Venda</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                    </div>
                                    <input class="form-control form-control-sm real"  name="produto_valor_venda" id="produto_valor_venda" type="text" value="<?php if (isset($Data)) echo $Data['produto_valor_venda'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3"> 
                            <div class="form-group">
                                <label>Fabricante</label>
                                <input class="form-control form-control-sm" type="text"  name="produto_fabricante" id="produto_fabricante" value="<?php if (isset($Data)) echo $Data['produto_fabricante'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Unidade de Medida</label>
                                <select class="form-control form-control-sm" id="produto_unidade" name="produto_unidade">
                                    <option <?php if (isset($Data['produto_unidade']) && $Data['produto_unidade'] == 'kg') echo 'selected' ?> value="kg">KG</option>
                                    <option <?php if (isset($Data['produto_unidade']) && $Data['produto_unidade'] == 'g') echo 'selected' ?> value="g">Gramas</option>
                                    <option <?php if (isset($Data['produto_unidade']) && $Data['produto_unidade'] == 'mg') echo 'selected' ?> value="mg">Miligrama </option>
                                    <option <?php if (isset($Data['produto_unidade']) && $Data['produto_unidade'] == 'und') echo 'selected' ?> value="und">Unidade</option>
                                    <option <?php if (isset($Data['produto_unidade']) && $Data['produto_unidade'] == 'cx') echo 'selected' ?> value="cx">Caixa</option>
                                    <option <?php if (isset($Data['produto_unidade']) && $Data['produto_unidade'] == 'L') echo 'selected' ?> value="L">Litro</option>
                                    <option <?php if (isset($Data['produto_unidade']) && $Data['produto_unidade'] == 'mL') echo 'selected' ?> value="mL">Mililitro</option>
                                    <option <?php if (isset($Data['produto_unidade']) && $Data['produto_unidade'] == 'm') echo 'selected' ?> value="m">Metro</option>
                                    <option <?php if (isset($Data['produto_unidade']) && $Data['produto_unidade'] == 'cm') echo 'selected' ?> value="cm">Centímetro</option>
                                    <option <?php if (isset($Data['produto_unidade']) && $Data['produto_unidade'] == 'mm') echo 'selected' ?> value="mm">Milímetro </option>
                                    <option <?php if (isset($Data['produto_unidade']) && $Data['produto_unidade'] == 'm2') echo 'selected' ?> value="m2">Metros Quadrados</option>
                                    <option <?php if (isset($Data['produto_unidade']) && $Data['produto_unidade'] == 'cm2') echo 'selected' ?> value="cm2">Centímetros Quadrados</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4 justify-content-md-center">
                        <div class="col-md-2">
                            <a href="painel.php?exe=<?= $navegacao[0] ?>/index" class="btn btn-danger btn-block btn-flat btn-sm"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Cancelar e Voltar</a>
                        </div>
                        <div class="col-md-2">
                            <button type="reset" value="clear" name="clear" class="btn btn-warning btn-block btn-flat btn-sm"><i class="fas fa-eraser"></i>&nbsp;&nbsp;Limpar Formulário</button>
                        </div>
                        <div class="col-md-2">
                            <a href="painel.php?exe=<?= $navegacao[0] ?>/create" class="btn btn-info btn-block btn-flat btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Novo</a>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" value="Cadastrar" name="sendForm" class="btn btn-success btn-block btn-flat btn-sm"><i class="fas fa-save"></i>&nbsp;&nbsp;Cadastrar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
