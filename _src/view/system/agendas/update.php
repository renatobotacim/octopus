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

if (filter_input(INPUT_GET, 'create', FILTER_VALIDATE_BOOLEAN)):
    IMDErro('<b>Sucesso :D</b><br>O registro foi cadastrado na Agenda!', IMD_ACCEPT);
endif;

require_once ('../Controllers/agendasController.class.php');
$controller = new agendasController();

//carrega a lista de categorias existentes
$categorias = $controller->getCategorias($userlogin['usuario_empresa_id']);

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$Data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!empty($Data['SendAlter'])):
    unset($Data['SendAlter']);
    if ($controller->updade($id, $Data, $userlogin['usuario_empresa_id'])):
        IMDErro('<b>Sucesso :D</b><br>Registro Atualizado com sucesso!', IMD_ACCEPT);
    endif;
else:
    $Data = $controller->read($id, $userlogin['usuario_empresa_id']);
endif;



$icone = "fas fa-users";
require_once 'includes/navegador.php';
?>
<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">
                    <i class="fas fa-info-circle"></i>&nbsp;Dados da Retificação</h3>
            </div>
            <form name="fileForm" action="" method="post" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tipo</label>
                                <select class="form-control form-control-sm" id="agenda_tipo" name="agenda_tipo">
                                    <option selected="selected" value=""> -- TODOS -- </option>   
                                    <option value="r" <?php if (isset($Data['agenda_tipo']) && $Data['agenda_tipo'] == 'r') echo 'selected' ?>>Reunião</option>
                                    <option value="v" <?php if (isset($Data['agenda_tipo']) && $Data['agenda_tipo'] == 'v') echo 'selected' ?>>Viagem</option>
                                    <option value="e" <?php if (isset($Data['agenda_tipo']) && $Data['agenda_tipo'] == 'e') echo 'selected' ?>>Evento</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Secretaria</label>
                                <select class="form-control form-control-sm" id="agenda_categoria_id" name="agenda_categoria_id">
                                    <option  selected="selected" value=""> -- TODOS -- </option> 
                                    <?php
                                    foreach ($categorias as $a):
                                        extract($a);
                                        echo "<option value=\"{$categoria_id}\"";
                                        if (isset($Data['agenda_categoria_id']) && $Data['agenda_categoria_id'] == $categoria_id):
                                            echo 'selected';
                                        endif;
                                        echo ">{$categoria_nome}</option>";
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Ano</label>
                                <input class="form-control  form-control-sm"  name="agenda_ano" id="agenda_ano" type="text" value="<?php if (isset($Data)) echo $Data['agenda_ano'] ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Data</label>
                                <input class="form-control  form-control-sm"  name="agenda_data" id="agenda_data" type="date" value="<?php if (isset($Data)) echo $Data['agenda_data'] ?>">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Hora</label>
                                <input class="form-control  form-control-sm"  name="agenda_hora" id="agenda_hora" type="time" value="<?php if (isset($Data)) echo $Data['agenda_hora'] ?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Local</label>
                                <input class="form-control  form-control-sm"  name="agenda_local" id="agenda_local" type="text" value="<?php if (isset($Data)) echo $Data['agenda_local'] ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Assunto</label>
                                <input class="form-control  form-control-sm"  name="agenda_assunto" id="agenda_assunto" type="text" value="<?php if (isset($Data)) echo $Data['agenda_assunto'] ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Autoridades</label>
                                <input class="form-control  form-control-sm"  name="agenda_autoridades" id="agenda_autoridades" type="text" value="<?php if (isset($Data)) echo $Data['agenda_autoridades'] ?>">
                            </div>
                        </div>



                    </div>
                    <div class="row justify-content-md-center">
                        <div class="col-md-2">
                            <a href="painel.php?exe=<?= $navegacao[0] ?>/index" class="btn btn-block btn-danger btn-flat btn-sm"><i class="fa fa-arrow-circle-left"></i>&nbsp;Cancelar / Voltar</a>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center">
                                <button type="submit" value="Cadastrar" name="SendAlter" class="btn btn-block btn-success btn-flat btn-sm"><i class="fas fa-save"></i>&nbsp;Salvar</button>
                            </div>
                        </div>
                    </div>
                </div>  
            </form>
        </div>
    </div>
</section>
<link rel="stylesheet"  href="dist/css/style_upload_master.css"/>
<script src="dist/js/script_upload_master.js"></script>
