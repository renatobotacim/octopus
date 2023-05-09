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

if (empty($hash)):
    $hash = date("m") . '004' . rand(10000, 99999);
endif;

$Data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!empty($Data['sendForm'])):
    unset($Data['sendForm']);

    require_once ('../Controllers/noticiasController.class.php');
    $controller = new noticiasController();

    $Data['noticia_empresa_id'] = $userlogin['usuario_empresa_id'];

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
                        <input id="hash" name="noticia_hash" type="hidden" value="<?php if (isset($hash)) echo $hash ?>">

                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Assunto da Notícia</label>
                                <select class="form-control form-control-sm" id="noticia_assunto_id" name="noticia_assunto_id">
                                    <option disabled="disabled" selected="selected" value="null"> -- Selecione -- </option>   
                                    <?php
                                    $read = new Read;
                                    $read->FullRead("select a.*, c.categoria_nome from inf_noticias_assuntos as a INNER JOIN inf_categorias as c ON c.categoria_id = a.noticia_assunto_categoria_id where c.categoria_empresa_id = :id_emp AND c.categoria_status = 2", "id_emp={$userlogin['usuario_empresa_id']}");
                                    foreach ($read->getResult() as $noticia_assunto_id):
                                        extract($noticia_assunto_id);
                                        echo"<option ";
                                        if (isset($Data['noticia_assunto_id']) && $Data['noticia_assunto_id'] == $noticia_assunto_id):
                                            echo "selected=\"selected\"";
                                        endif;
                                        echo "value=\"{$noticia_assunto_id}\"> [Categ.: {$categoria_nome}] --- {$noticia_assunto_nome} </option>";
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Data de Publicação</label>
                                <input class="form-control  form-control-sm"  name="noticia_data_publicacao" id="noticia_data_publicacao" type="datetime" value="<?php if (isset($Data)) echo $Data['noticia_data_publicacao'] ?>">
                            </div>
                        </div>


                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Destaque</label>
                                <select class="form-control form-control-sm" id="noticia_destaque" name="noticia_destaque">
                                    <option value="1">NÃO</option>
                                    <option value="2">SIM</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control form-control-sm" id="noticia_status" name="noticia_status">
                                    <option <?php if ($Data['noticia_status'] == 1) echo 'selected' ?> value="1" >RASCUNHO</option>
                                    <option <?php if ($Data['noticia_status'] == 2) echo 'selected' ?> value="2" >PUBLICADA</option>
                                    <!--<option <?php if ($Data['noticia_status'] == 3) echo 'selected' ?> value="3" >AGENDADA</option>-->
                                    <option <?php if ($Data['noticia_status'] == 4) echo 'selected' ?> value="4" >OCULTA</option>
                                </select>
                            </div>
                        </div>


                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label>Título</label>
                                <input class="form-control form-control-sm" type="text" name="noticia_titulo" id="noticia_titulo" value="<?php if (isset($Data)) echo $Data['noticia_titulo'] ?>">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Resumo</label>
                                <input class="form-control form-control-sm" type="text" name="noticia_resumo" id="noticia_resumo" value="<?php if (isset($Data)) echo $Data['noticia_resumo'] ?>">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label>Conteúdo</label>
                                <textarea class="textarea summernote" name="noticia_conteudo" placeholder="Escreva aqui o conteúdo" 
                                          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?php if (isset($Data)) echo $Data['noticia_conteudo'] ?>
                                </textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Fonte</label>
                                <input class="form-control form-control-sm" type="text" name="noticia_fonte" id="noticia_fonte" value="<?php if (isset($Data)) echo $Data['noticia_fonte'] ?>">
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
