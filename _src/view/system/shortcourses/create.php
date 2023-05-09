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
if (isset($Data)):
    $Data['shortcourses_image'] = $_FILES['shortcourses_image'];
endif;

if (!empty($Data['sendShortcourses'])):
    unset($Data['sendShortcourses']);
    require ('_models/AdminShortcourses.class.php');
    $cadastra = new AdminShortcources();
    $cadastra->ExeCreate($Data);
    if (!$cadastra->getResult()):
        IMDErro($cadastra->getError()[0], $cadastra->getError()[1]);
    else:
        IMDErro($cadastra->getError()[0], $cadastra->getError()[1]);
        $link = 'painel.php?exe=shortcourses/update&create=true&id=' . $cadastra->getResult(); // especifica o endereço
        redireciona($link); // chama a função
    endif;
endif;
?>
<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header"><i class="fa fa-university"></i> &nbsp; Cadastro de Minicursos</h3>
        <ol class="breadcrumb">
            <li>
                <i class="fa fa-dashboard"></i>  <a href="painel">Dashboard</a>
            </li>
            <li>
                <i class="fa fa-user"></i> <a href="painel.php?exe=shortcourses/index">Minicursos</a>
            </li>
            <li class="active">
                <i class="fa fa-plus"></i> Cadastro
            </li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-green">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-plus"></i> Cadastro de Minicursos</h3>
            </div>
            <div class="panel-body">
                <div class="flot-chart">
                    <form name="fileForm" action="" method="post" enctype="multipart/form-data">
                        <div class="flot-chart-content" id="flot-moving-line-chart">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Título</label>
                                        <input type="text" name="shortcourses_title" id="shortcourses_title" class="form-control" placeholder="Título do Minicurso" value="<?php if (isset($Data)) echo $Data['shortcourses_title']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Subtítulo</label>
                                        <input type="text" name="shortcourses_subtitle" id="shortcourses_subtitle" class="form-control" placeholder="Subtítulo do Minicurso" value="<?php if (isset($Data)) echo $Data['shortcourses_subtitle']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Palestrante</label>
                                        <input type="text" name="shortcourses_responsible" id="shortcourses_responsible" class="form-control" placeholder="Nome de Orador" value="<?php if (isset($Data)) echo $Data['shortcourses_responsible']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Formação</label>
                                        <input type="text" name="shortcourses_formation" id="shortcourses_formation" class="form-control" placeholder="Formação do Orador" value="<?php if (isset($Data)) echo $Data['shortcourses_formation']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label>Data e horário</label>
                                        <input type="datetime-local" name="shortcourses_datetime" id="shortcourses_datetime" class="form-control" placeholder="Data e Hora do Minicurso" value="<?php if (isset($Data)) echo $Data['shortcourses_datetime']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label>Carga Horária</label>
                                        <input type="text" name="shortcourses_hours" id="shortcourses_hours" class="form-control" placeholder="Carga Horária do Minicurso" value="<?php if (isset($Data)) echo $Data['shortcourses_hours']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label>Texto do Botão</label>
                                        <input type="text" name="shortcourses_btntext" id="shortcourses_btntext" class="form-control" placeholder="Texto do Botão para acesso" value="<?php if (isset($Data)) echo $Data['shortcourses_btntext']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label>Link do Botão</label>
                                        <input type="text" name="shortcourses_link" id="shortcourses_link" class="form-control" placeholder="Link do Botão" value="<?php if (isset($Data)) echo $Data['shortcourses_link']; ?>">
                                    </div>
                                </div>         
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Banner do Minicurso</label><br>
                                        <input type="file" name="shortcourses_image" accept="image/*" id="shortcourses_image" class="inputfile" onchange="previewImage()"/>
                                        <label class="uploadArquivo" for="shortcourses_image">
                                            <?php
                                            if (!isset($Data['shortcourses_image']) || ($Data['shortcourses_image']['size'] == 0)):
                                                echo "<img src=\"https://www.w3schools.com/w3images/avatar2.png\" width=\"200px\" heigth=\"auto\"";
                                                echo "<img id=\"preview\" width=\"200px\" heigth=\"auto\" style=\"position: relative; top:0;left: 0;\">";
                                            else:
                                                echo "<img src=\"https://www.w3schools.com/w3images/avatar2.png\" width=\"200px\" heigth=\"auto\"";
                                                echo "<img id=\"preview\" width=\"200px\" heigth=\"auto\" style=\"position: relative; top:0;left: 0;\">";
                                            endif;
                                            ?>     
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-success" value="Cadastrar" name="sendShortcourses"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;Cadastrar</button>
                            <a type="submit" class="btn btn-info" value="" name="SendAlter" href="painel.php?exe=shortcourses/create"/><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Novo</a>
                            <button type="reset" class="btn btn-warning" value="" name="SendAlter"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Limpar Forumário</button>
                            <a type="submit" class="btn btn-danger" value="" name="SendAlter" href="painel.php?exe=shortcourses/index"/><i class="fa fa-arrow-circle-left" aria-hidden="true"></i>&nbsp;Voltar</a>
                        </div>
                    </form>                    
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
</div>
<style>
    .inputfile {
        width: 0.1px;
        height: 0.1px;
        opacity: 0;
        overflow: hidden;
        position: absolute;
        z-index: -1;
    }
    .uploadArquivo {text-align: center;cursor: pointer;}
    .uploadArquivo .mudarfoto {
        position: absolute;
        font-size: 1.5em;
        top: 50%;
        left: 40%;
        transform: translateY(-50%);
        display: none;        
    }
    .inputfile + label:hover, .inputfile + label img{opacity: 0.9}
</style>
<script>
    function previewImage() {
        var previewBox = document.getElementById("preview");
        previewBox.src = URL.createObjectURL(event.target.files[0]);
    }
</script>
