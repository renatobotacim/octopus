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

require_once ('../Controllers/arquivosController.class.php');
$controller = new arquivosController();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$Data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!empty($Data['SendAlter'])):
    unset($Data['SendAlter']);
    $controller->updade($id, $Data);
endif;
$Data = $controller->read($id, $userlogin['usuario_empresa_id']);

$icone = "fas fa-users";
require_once 'includes/navegador.php';
?>
<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">
                    <i class="fas fa-info-circle"></i>&nbsp;Dados do Arquivo</h3>
            </div>
            <form name="fileForm" action="" method="post" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Nome</label>
                                <input class="form-control  form-control-sm"  name="arquivo_nome" id="arquivo_nome" type="text" value="<?php if (isset($Data)) echo $Data['arquivo_nome'] ?>">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Identificador</label>
                                <input class="form-control  form-control-sm"  name="arquivo_identificador" id="arquivo_identificador" type="text" value="<?php if (isset($Data)) echo $Data['arquivo_identificador'] ?>">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Descrição</label>
                                <input class="form-control  form-control-sm"  name="arquivo_descricao" id="arquivo_descricao" type="text" value="<?php if (isset($Data)) echo $Data['arquivo_descricao'] ?>">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Categoria</label>
                                <select class="form-control form-control-sm" id="arquivo_categoria_id" name="arquivo_categoria_id">
                                    <option disabled="disabled" selected="selected" value="null"> -- Selecione -- </option>   
                                    <?php
                                    $read = new Read;
                                    $read->FullRead("select * from inf_categorias where categoria_empresa_id = :id_emp AND categoria_status = 2", "id_emp={$userlogin['usuario_empresa_id']}");
                                    foreach ($read->getResult() as $categoria_id):
                                        extract($categoria_id);
                                        echo"<option ";
                                        if (isset($Data['arquivo_categoria_id']) && $Data['arquivo_categoria_id'] == $categoria_id):
                                            echo "selected=\"selected\"";
                                        endif;
                                        echo "value=\"{$categoria_id}\">{$categoria_nome}</option>";
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Extensão</label>
                                <input class="form-control  form-control-sm"  readonly type="text" value="<?php if (isset($Data)) echo $Data['arquivo_extensao'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Data de Upload</label>
                                <input class="form-control  form-control-sm" readonly type="date" value="<?php if (isset($Data)) echo $Data['arquivo_data'] ?>">
                            </div>
                        </div>

                        <div class="col-md-12 my-4">
                            <a href="http://<?= $Data['arquivo_host'] ?>/<?= $Data['arquivo_link'] ?>" target="_blank" class="btn btn-block btn-secondary btn-flat btn-sm"><i class="fa fa-eye"></i>&nbsp;Ver Anexo</a>
                        </div>

                    </div>
                    <div class="row justify-content-md-center">
                        <div class="col-md-2">
                            <a href="painel.php?exe=<?= $navegacao[0] ?>/index" class="btn btn-block btn-danger btn-flat btn-sm"><i class="fa fa-arrow-circle-left"></i>&nbsp;Cancelar / Voltar</a>
                        </div>
                        <div class="col-md-2">
                            <a href="painel.php?exe=<?= $navegacao[0] ?>/create" class="btn btn-info btn-block btn-flat btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Novo Anexo</a>
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
