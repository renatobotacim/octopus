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

//if (empty($hash)):
//    $hash = date("m") . '004' . rand(10000, 99999);
//endif;

$Data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!empty($Data['sendForm'])):
    unset($Data['sendForm']);

    require_once ('../Controllers/selecoesController.class.php');
    $controller = new selecoesController();

    $Data['processo_seletivo_empresa_id'] = $userlogin['usuario_empresa_id'];

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
                                <label>Tipo</label>
                                <select class="form-control form-control-sm" id="processo_seletivo_tipo" name="processo_seletivo_tipo">
                                    <option disabled="disabled" selected="selected" value="null"> -- Selecione -- </option>   
                                    <option <?php if ($Data['processo_seletivo_tipo'] == 1) echo 'selected' ?>  value="1">Processo Seletivo</option>   
                                    <option <?php if ($Data['processo_seletivo_tipo'] == 2) echo 'selected' ?>  value="2">Concurso Público</option>   
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Número</label>
                                <input class="form-control  form-control-sm"  name="processo_seletivo_numero" id="processo_seletivo_numero" type="number" value="<?php if (isset($Data)) echo $Data['processo_seletivo_numero'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Ano</label>
                                <input class="form-control  form-control-sm"  name="processo_seletivo_ano" id="processo_seletivo_ano" type="number" value="<?php if (isset($Data)) echo $Data['processo_seletivo_ano'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Categoria</label>
                                <select class="form-control form-control-sm" id="processo_seletivo_categoria_id" name="processo_seletivo_categoria_id">
                                    <?php
                                    $read = new Read;
                                    $read->FullRead("select * from inf_categorias as c where c.categoria_empresa_id = :id_emp AND c.categoria_status = 2", "id_emp={$userlogin['usuario_empresa_id']}");
                                    foreach ($read->getResult() as $categoria_id):
                                        extract($categoria_id);
                                        echo"<option ";
                                        if (isset($Data['processo_seletivo_categoria_id']) && $Data['processo_seletivo_categoria_id'] == $categoria_id):
                                            echo "selected=\"selected\"";
                                        endif;
                                        echo "value=\"{$categoria_id}\">{$categoria_nome} </option>";
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Link da Ferramenta</label>
                                <input class="form-control form-control-sm" type="text" name="processo_seletivo_link" id="processo_seletivo_link" value="<?php if (isset($Data)) echo $Data['processo_seletivo_link'] ?>">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label>Descrição</label>
                                <textarea class="textarea summernote" name="processo_seletivo_descricao" placeholder="Escreva aqui o conteúdo" 
                                          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?php if (isset($Data)) echo $Data['processo_seletivo_descricao'] ?>
                                </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-md-center">
                        <div class="col-md-3">
                            <a href="painel.php?exe=<?= $navegacao[0] ?>/index" class="btn btn-danger btn-block btn-sm btn-flat"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Cancelar / Voltar</a>
                        </div>
                        <div class="col-md-3">
                            <button type="reset" value="clear" name="clear" class="btn btn-warning btn-block btn-sm btn-flat"><i class="fas fa-eraser"></i>&nbsp;Limpar Formulário</button>
                        </div>
                        <div class="col-md-3">
                            <a href="painel.php?exe=<?= $navegacao[0] ?>/create" class="btn btn-info btn-sm btn-block btn-flat"><i class="fa fa-plus"></i>&nbsp;&nbsp;Novo Serviço</a>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" value="Cadastrar" name="sendForm" class="btn btn-success btn-sm btn-block btn-flat"><i class="fas fa-save"></i>&nbsp;Cadastrar</button>
                        </div>
                    </div>
                </div>  
            </form>
        </div>
    </div>
</section>
<!--<script>
    function previewImage() {
        var previewBox = document.getElementById("preview");
        previewBox.src = URL.createObjectURL(event.target.files[0]);
    }
</script>-->
