<?php
//VERIFICA A EXISTÊNCIA DE UM LOGIM, CASO SIM, ELE SEMPRE DIRECIONA PARA O PAINEL, CASO NÃO, DIRECIONA A TELA DE LOGIN
$checkLevel = new Login(1);
if (!$checkLevel->CheckLevel()):
    redireciona('painel.php?restrito=true'); // chama a função
endif;

if (!class_exists('Login')) :
    header('Location: ../../index.php');
    die;
endif;

$Data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!empty($Data['sendForm'])):
    unset($Data['sendForm']);

require_once ('../Controllers/parceriasController.class.php');
$controller = new parceriasController();

    $Data['parceria_empresa_id'] = $userlogin['usuario_empresa_id'];


    if (empty($Data['parceria_termo_fomento'])):
        $Data['parceria_termo_fomento'] = 'F';
    endif;
    if (empty($Data['parceria_termo_colaboracao'])):
        $Data['parceria_termo_colaboracao'] = 'F';
    endif;
    if (empty($Data['parceria_acordo_coperacao'])):
        $Data['parceria_acordo_coperacao'] = 'F';
    endif;
    if (empty($Data['parceria_manf_interesse_publico'])):
        $Data['parceria_manf_interesse_publico'] = 'F';
    endif;
    if (empty($Data['parceria_chamamento_publico'])):
        $Data['parceria_chamamento_publico'] = 'F';
    endif;

    $controller->create($Data);
endif;
$icone = "fas fa-users";
require_once 'includes/navegador.php';
?>

<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">
                    <i class="fas fa-info-circle"></i>&nbsp;Dados do Serviço </h3>
            </div>
            <form name="fileForm" action="" method="post" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Tipo</label>
                                <br>
                                <input type="checkbox" id="1" name="parceria_termo_fomento" value="T" <?php if (isset($Data) && $Data['parceria_termo_fomento'] == 'T') echo "checked" ?>><label for="1"> &nbsp; Parceria Termo de Fomento</label><br>
                                <input type="checkbox" id="2" name="parceria_termo_colaboracao" value="T" <?php if (isset($Data) && $Data['parceria_termo_colaboracao'] == 'T') echo "checked" ?>><label for="2"> &nbsp; Parceria Termo de colaboracao</label><br>
                                <input type="checkbox" id="3" name="parceria_acordo_coperacao" value="T" <?php if (isset($Data) && $Data['parceria_acordo_coperacao'] == 'T') echo "checked" ?>><label for="3"> &nbsp; Parceria de Acordo de Coperacao</label><br>
                                <input type="checkbox" id="4" name="parceria_manf_interesse_publico" value="T" <?php if (isset($Data) && $Data['parceria_manf_interesse_publico'] == 'T') echo "checked" ?>><label for="4"> &nbsp; Parceria Manf. Interesse Público</label><br>
                                <input type="checkbox" id="5" name="parceria_chamamento_publico" value="T" <?php if (isset($Data) && $Data['parceria_chamamento_publico'] == 'T') echo "checked" ?>><label for="5">&nbsp; Parceria de Chamamento Publico</label><br>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Parceria Número</label>
                                <input class="form-control  form-control-sm"  name="parceria_numero" id="parceria_numero" type="text" value="<?php if (isset($Data)) echo $Data['parceria_numero'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Processo</label>
                                <input class="form-control  form-control-sm"  name="parceria_processo" id="parceria_processo" type="text" value="<?php if (isset($Data)) echo $Data['parceria_processo'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Ano</label>
                                <input class="form-control  form-control-sm"  name="parceria_ano" id="parceria_ano" type="number" value="<?php if (isset($Data)) echo $Data['parceria_ano'] ?>">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Secretaria</label>
                                <input class="form-control  form-control-sm"  name="parceria_secretaria" id="parceria_secretaria" type="text" value="<?php if (isset($Data)) echo $Data['parceria_secretaria'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>OSC</label>
                                <input class="form-control  form-control-sm"  name="parceria_osc" id="parceria_osc" type="text" value="<?php if (isset($Data)) echo $Data['parceria_osc'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Valor</label>
                                <input class="form-control  form-control-sm"  name="parceria_valor" id="parceria_valor" type="text" value="<?php if (isset($Data)) echo $Data['parceria_valor'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Vigência</label>
                                <input class="form-control  form-control-sm"  name="parceria_vigencia" id="parceria_vigencia" type="text" value="<?php if (isset($Data)) echo $Data['parceria_vigencia'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Data</label>
                                <input class="form-control  form-control-sm"  name="parceria_data" id="parceria_data" type="text" value="<?php if (isset($Data)) echo $Data['parceria_data'] ?>">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label>Objeto</label>
                                <textarea class="textarea summernote" name="parceria_objeto" placeholder="Escreva aqui o conteúdo" 
                                          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?php if (isset($Data)) echo $Data['parceria_objeto'] ?>
                                </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4 justify-content-md-center">
                        <div class="col-md-2">
                            <a href="painel.php?exe=<?= $navegacao[0] ?>/index" class="btn btn-danger btn-block btn-sm btn-flat"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Cancelar / Voltar</a>
                        </div>
                        <div class="col-md-2">
                            <button type="reset" value="clear" name="clear" class="btn btn-warning btn-block btn-sm btn-flat"><i class="fas fa-eraser"></i>&nbsp;Limpar Formulário</button>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" value="Cadastrar" name="sendForm" class="btn btn-success btn-sm btn-block btn-flat"><i class="fas fa-save"></i>&nbsp;Cadastrar</button>
                        </div>
                    </div>
                </div>  
            </form>
        </div>
    </div>
</section>
