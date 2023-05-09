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
//    if ($_FILES['media']['size']):
//        $Data['media'] = $_FILES['media'];
//    endif;

    require_once ('../Controllers/conteudosController.class.php');
    $controller = new conteudosController();

    $Data['conteudo_empresa_id'] = $userlogin['usuario_empresa_id'];

    $controller->create($Data);
endif;

$icone = "fas fa-users";
require_once 'includes/navegador.php';
?>

<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">
                    <i class="fas fa-info-circle"></i>&nbsp;Conteúdo </h3>
            </div>
            <form name="fileForm" action="" method="post" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label>Tipo de Conteúdo</label>
                                <select class="form-control form-control-sm" id="conteudo_tipo_conteudo_id" name="conteudo_tipo_conteudo_id">
                                    <option disabled="disabled" selected="selected" value="null"> -- Selecione -- </option>   
                                    <?php
                                    $read = new Read;
                                    $read->FullRead("select * from inf_tipos_conteudos where tipo_conteudo_empresa_id = :id_emp", "id_emp={$userlogin['usuario_empresa_id']}");
                                    foreach ($read->getResult() as $tipo_conteudo_id):
                                        extract($tipo_conteudo_id);
                                        echo"<option ";
                                        if (isset($Data['conteudo_tipo_conteudo_id']) && $Data['conteudo_tipo_conteudo_id'] == $tipo_conteudo_id):
                                            echo "selected=\"selected\"";
                                        endif;
                                        echo "value=\"{$tipo_conteudo_id}\"> {$tipo_conteudo_nome} </option>";
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-9">
                            <div class="form-group">
                                <label>Conteúdo Pai</label>
                                <select class="form-control form-control-sm" id="conteudo_pai" name="conteudo_pai">
                                    <option disabled="disabled" selected="selected" value="null"> -- Selecione -- </option>   
                                    <?php
                                    $read = new Read;
                                    $read->FullRead("select * from inf_conteudos where conteudo_empresa_id = :id_emp", "id_emp={$userlogin['usuario_empresa_id']}");
                                    foreach ($read->getResult() as $conteudo_id):
                                        extract($conteudo_id);
                                        echo"<option ";
                                        if (isset($Data['conteudo_pai']) && $Data['conteudo_pai'] == $conteudo_id):
                                            echo "selected=\"selected\"";
                                        endif;
                                        echo "value=\"{$conteudo_id}\"> {$conteudo_id} - {$conteudo_titulo} </option>";
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>


                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label>Título</label>
                                <input class="form-control form-control-sm" type="text" name="conteudo_titulo" id="conteudo_titulo" value="<?php if (isset($Data)) echo $Data['conteudo_titulo'] ?>">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Subtítulo</label>
                                <input class="form-control form-control-sm" type="text" name="conteudo_subtitulo" id="conteudo_subtitulo" value="<?php if (isset($Data)) echo $Data['conteudo_subtitulo'] ?>">
                            </div>
                        </div>


                        <div class="col-12">
                            <div class="form-group">
                                <label>Documentação</label>
                                <textarea class="textarea summernote" name="conteudo_conteudo" placeholder="Escreva aqui o conteúdo" 
                                          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?php if (isset($Data)) echo $Data['conteudo_conteudo'] ?>
                                </textarea>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Responsável</label>
                                <input class="form-control form-control-sm" type="text" name="conteudo_responsavel" id="conteudo_responsavel" value="<?php if (isset($Data)) echo $Data['conteudo_responsavel'] ?>">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Biografia</label>
                                <input class="form-control form-control-sm" type="text" name="conteudo_bio" id="conteudo_bio" value="<?php if (isset($Data)) echo $Data['conteudo_bio'] ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pixel</label>
                                <input class="form-control form-control-sm" type="text" name="conteudo_pixel" id="conteudo_pixel" value="<?php if (isset($Data)) echo $Data['conteudo_pixel'] ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Analytics</label>
                                <input class="form-control form-control-sm" type="text" name="conteudo_analytics" id="conteudo_analytics" value="<?php if (isset($Data)) echo $Data['conteudo_analytics'] ?>">
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
                            <a href="painel.php?exe=<?= $navegacao[0] ?>/create" class="btn btn-info btn-sm btn-block btn-flat"><i class="fa fa-plus"></i>&nbsp;&nbsp;Novo Conteúdo</a>
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
