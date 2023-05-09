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

    require_once ('../Controllers/legislacaoController.class.php');
    $controller = new legislacaoController();

    $Data['infralegal_empresa_id'] = $userlogin['usuario_empresa_id'];

    if ($_FILES['infralegal_link']['size'] > 0):
        $Data['file'] = $_FILES['infralegal_link'];
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Número</label>
                                <input class="form-control  form-control-sm"  name="infralegal_numero" id="infralegal_numero" type="number" value="<?php if (isset($Data)) echo $Data['infralegal_numero'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Ano</label>
                                <input class="form-control  form-control-sm"  name="infralegal_ano" id="infralegal_ano" type="number" value="<?php if (isset($Data)) echo $Data['infralegal_ano'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tipo</label>
                                <select class="form-control form-control-sm" id="infralegal_tipo" name="infralegal_tipo">
                                    <option disabled="disabled" selected="selected" value="null"> -- Selecione -- </option>  
                                    <?php
                                    $read = new Read;
                                    $read->FullRead("select * from inf_legislacao_infralegal_tipos WHERE infralegal_tipo_empresa_id = :id_emp", "id_emp={$userlogin['usuario_empresa_id']}");
                                    foreach ($read->getResult() as $infralegal_tipo_id):
                                        extract($infralegal_tipo_id);
                                        echo"<option ";
                                        if (isset($Data['infralegal_tipo']) && $Data['infralegal_tipo'] == $infralegal_tipo_id):
                                            echo "selected=\"selected\"";
                                        endif;
                                        echo "value=\"{$infralegal_tipo_id}\">{$infralegal_tipo_nome}</option>";
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Sistema</label>
                                <select class="form-control form-control-sm" id="infralegal_legislacao_sistema" name="infralegal_legislacao_sistema">
                                    <option disabled="disabled" selected="selected" value="null"> -- Selecione -- </option>   
                                    <?php
                                    $read = new Read;
                                    $read->FullRead("select * from inf_legislacao_sistema WHERE legislacao_sistema_empresa_id = :id_emp", "id_emp={$userlogin['usuario_empresa_id']}");
                                    foreach ($read->getResult() as $legislacao_sistema_id):
                                        extract($legislacao_sistema_id);
                                        echo"<option ";
                                        if (isset($Data['infralegal_legislacao_sistema']) && $Data['infralegal_legislacao_sistema'] == $legislacao_sistema_id):
                                            echo "selected=\"selected\"";
                                        endif;
                                        echo "value=\"{$legislacao_sistema_id}\">{$legislacao_sistema_nome}</option>";
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Assunto</label>
                                <textarea class="textarea summernote" name="infralegal_assunto" placeholder="Escreva aqui o conteúdo" 
                                          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?php if (isset($Data)) echo $Data['infralegal_assunto'] ?>
                                </textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="">
                            <label>Anexo</label><br>
                            <input type="file" name="infralegal_link" id="infralegal_link" />
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
