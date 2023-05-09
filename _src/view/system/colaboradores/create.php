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
    $Data['colaborador_imagem'] = $_FILES['colaborador_imagem'];
endif;

if (!empty($Data['sendForm'])):
    unset($Data['sendForm']);
    require ('_models/AdminColaboradores.class.php');
    $cadastra = new AdminColaboradores();
    $Data['colaborador_empresa_id'] = $userlogin['usuario_empresa_id'];
    $cadastra->ExeCreate($Data);
    if (!$cadastra->getResult()):
        IMDErro($cadastra->getError()[0], $cadastra->getError()[1]);
    else:
        IMDErro($cadastra->getError()[0], $cadastra->getError()[1]);
        $link = 'painel.php?exe=colaboradores/update&create=true&id=' . $cadastra->getResult(); // especifica o endereço
        redireciona($link); // chama a função
    endif;
endif;
$navegacao = filter_input(INPUT_GET, 'exe', FILTER_DEFAULT);
$navegacao = explode("/", $navegacao);
$navegacaoRef = "Colaboradores";
$navegacaoIco = "fas fa-people-carry";
require_once 'includes/navegador.php';
?>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php
                        if ($navegacao[1] == "create"): echo "Cadastro de {$navegacaoRef}";
                        elseif ($navegacao[1] == "update"): echo"Alteração de {$navegacaoRef}";
                        else: echo "Listagem de {$navegacaoRef}";
                        endif;
                        ?></h3>
                </div>
                <div class="box-body">
                    <form name="fileForm" action="" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Imagem</label><br>
                                    <input type="file" name="colaborador_imagem" accept="image/*" id="colaborador_imagem" class="inputfile" onchange="previewImage()"/>
                                    <label class="uploadArquivo" for="colaborador_imagem">
                                        <?php
                                        if (!isset($Data['colaborador_imagem']) || ($Data['colaborador_imagem']['size'] == 0)):
                                            echo "<img src=\"https://www.w3schools.com/w3images/avatar2.png\" width=\"100%\" heigth=\"auto\" style=\" max-height: 350px;\"";
                                            echo "<img id=\"preview\" width=\"100%\" heigth=\"auto\" style=\"position: relative; top:0;left: 0;max-height: 350px;\">";
                                        else:
                                            echo "<img src=\"https://www.w3schools.com/w3images/avatar2.png\" width=\"100%\" heigth=\"auto\" style=\" max-height: 350px;\"";
                                            echo "<img id=\"preview\" width=\"100%\" heigth=\"auto\" style=\"position: relative; top:0;left: 0;max-height: 350px;\">";
                                        endif;
                                        ?>     
                                    </label>
                                </div>                               
                            </div>
                            <div class="col-md-9">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label>Nome</label>
                                        <input type="text" name="colaborador_nome" id="colaborador_nome" class="form-control" placeholder="Nome" value="<?php if (isset($Data)) echo $Data['colaborador_nome']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label>Função</label>
                                        <input type="text" name="colaborador_funcao" id="colaborador_funcao" class="form-control" placeholder="Função" value="<?php if (isset($Data)) echo $Data['colaborador_funcao']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label>Telefone</label>
                                        <input type="text" name="colaborador_telefone" id="colaborador_telefone" class="form-control" placeholder="Telefone" value="<?php if (isset($Data)) echo $Data['colaborador_telefone']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="text" name="colaborador_email" id="colaborador_email" class="form-control" placeholder="E-mail" value="<?php if (isset($Data)) echo $Data['colaborador_email']; ?>">
                                    </div>
                                </div>       
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label>Facebook</label>
                                        <input type="text" name="colaborador_facebook" id="colaborador_facebook" class="form-control" placeholder="Facebook" value="<?php if (isset($Data)) echo $Data['colaborador_facebook']; ?>">
                                    </div>
                                </div>      
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label>Twitter</label>
                                        <input type="text" name="colaborador_twitter" id="colaborador_twitter" class="form-control" placeholder="Twitter" value="<?php if (isset($Data)) echo $Data['colaborador_twitter']; ?>">
                                    </div>
                                </div>                                                                                                                           
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label>Instagram</label>
                                        <input type="text" name="colaborador_instagram" id="colaborador_instagram" class="form-control" placeholder="Instagram" value="<?php if (isset($Data)) echo $Data['colaborador_instagram']; ?>">
                                    </div>
                                </div>                                                                                                                           
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label>Google +</label>
                                        <input type="text" name="colaborador_google" id="colaborador_google" class="form-control" placeholder="Google" value="<?php if (isset($Data)) echo $Data['colaborador_google']; ?>">
                                    </div>
                                </div>   
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Comentário do Colaborador</label>
                                        <textarea type="text" rows="3" name="colaborador_informacoes" id="colaborador_informacoes" class="form-control" placeholder="Insira um comentário do colaborador" value="<?php if (isset($Data)) echo $Data['colaborador_informacoes']; ?>"></textarea>
                                    </div>
                                </div>   
                            </div>
                        </div>
                        <div class="row">
                            <section class="content">
                                <div class="col-md-12">
                                    <div class="box box-info">
                                        <div class="box-header">
                                            <h3 class="box-title">Conteúdo da Página
                                                <small>Desenvolva o conteúdo da página</small>
                                            </h3>
                                            <div class="pull-right box-tools">
                                                <button type="button" class="btn btn-info btn-sm" data-widget="collapse" data-toggle="tooltip" title="Esconder">
                                                    <i class="fa fa-minus"></i></button>                             
                                            </div>
                                        </div>
                                        <div class="box-body pad">
                                            <textarea id="colaborador_biografia" name="colaborador_biografia" rows="5" cols="80" >
                                                <?php if (isset($Data)) echo $Data['colaborador_biografia']; ?>
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                        <br>
                        <div class="text-right">
                            <button type="submit" value="Cadastrar" name="sendForm" class="btn btn-success btn-flat"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;Cadastrar</button>
                            <a href="painel.php?exe=<?= $navegacao[0] ?>/create" class="btn btn-info btn-flat"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Novo</a>
                            <button type="reset" class="btn btn-warning btn-flat"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Limpar Forumário</button>
                            <a href="painel.php?exe=<?= $navegacao[0] ?>/index" class="btn btn-danger btn-flat"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i>&nbsp;Voltar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
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
    };
    window.onload = function () {
        CKEDITOR.replace('colaborador_biografia');
    };
</script>
