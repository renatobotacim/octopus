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
    IMDErro('<b>Sucesso :D</b><br>A notícia foi cadastrada com sucesso! Você agora está na tela de atualização, então, pode continuar editando a notícia!', IMD_ACCEPT);
endif;

require_once ('../Controllers/noticiasController.class.php');
$controller = new noticiasController();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$Data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!empty($Data['SendAlter'])):
    unset($Data['SendAlter']);
    $controller->updade($id, $Data);
else:
    $Data = $controller->read($id, $userlogin['usuario_empresa_id']);
endif;
if (empty($Data['noticia_data_publicacao'])):
    $Data['noticia_data_publicacao'] = date('d-m-Y');
endif;

$icone = "fas fa-users";
require_once 'includes/navegador.php';
?>
<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">
                    <i class="fas fa-info-circle"></i>&nbsp;Notícia</h3>
            </div>
            <form name="fileForm" action="" method="post" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">
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
                                    <option <?php if ($Data['noticia_destaque'] == 1) echo 'selected' ?> value="1">NÃO</option>
                                    <option <?php if ($Data['noticia_destaque'] == 2) echo 'selected' ?> value="2" >SIM</option>
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
                        <div class="col-md-2">
                            <a href="painel.php?exe=<?= $navegacao[0] ?>/index" class="btn btn-block btn-danger btn-flat btn-sm"><i class="fa fa-arrow-circle-left"></i>&nbsp;Cancelar / Voltar</a>
                        </div>
                        <div class="col-md-2">
                            <a href="painel.php?exe=<?= $navegacao[0] ?>/create" class="btn btn-info btn-block btn-flat btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Novo Serviço</a>
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
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">
                    <i class="fas fa-info-circle"></i>&nbsp;Anexos e Imagens</h3>
            </div>
            <div class="col-12 card-body p-2">
                <label><i class="fas fa-cloud-upload-alt"></i>&nbsp;&nbsp;Anexar Imagem</label>
                <div class="row">
                    <div class="col-md-4">
                        <label>Prioridade</label>
                        <input class="form-control form-control-sm my-2" type="number" min="1" value="1" id="imagem_prioridade" placeholder="Informe a Prioridade desta imagem">
                    </div>
                    <div class="col-md-4">
                        <label>Crédito</label>
                        <input class="form-control form-control-sm my-2" type="text" id="imagem_credito" placeholder="Informe de quem é o crédito da imagem">
                    </div>
                    <div class="col-md-4">
                        <label>Descrição</label>
                        <input class="form-control form-control-sm  my-2" type="text" id="imagem_descricao" placeholder="Informe a descrição da imagem">
                    </div>

                    <div class="area-upload" >
                        <label for="file" class="label-upload">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <div class="texto">Clique aqui ou arraste o(s) arquivo(s) :)</div>
                        </label>
                        <input type="file"  id="file" onchange="newInput(this)"/>
                        <ul id="dp-files"></ul>
                    </div>
                    <div class="col-12" >
                        <!--<button class="btn btn-block btn-sm btn-success btn-flat" type="button" id="btEnviar" ><i class="fas fa-save"></i>&nbsp;&nbsp;Anexar!</button>-->
                    </div>
                </div>
                <div class="row">
                    <link rel="stylesheet"  href="dist/css/style_upload_master.css"/>
                    <script src="dist/js/script_upload_master.js"></script>
                    <script type="text/javascript">

                    </script>
                    <table  class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Link</th>
                                <th>Prioridade</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody id="itens_imagens">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-12 card-body p-2">
                <label><i class="fas fa-cloud-upload-alt"></i>&nbsp;&nbsp;Anexar Arquivos</label>
                <div class="row">
                    <div class="col-md-9">
                        <label>Descrição</label>
                        <input class="form-control form-control-sm" type="text" id="arquivo_descricao" placeholder="Descrição do Arquivo">
                    </div>
                    <div class="col-md-3">
                        <label>Categoria do Arquivo</label>
                        <select class="form-control form-control-sm" id="arquivo_categoria_id">
                            <option disabled="disabled" selected="selected" value="null"> -- Selecione -- </option>   
                            <?php
                            $read = new Read;
                            $read->FullRead("select * from inf_categorias WHERE categoria_empresa_id = :id_emp AND categoria_status = 2", "id_emp={$userlogin['usuario_empresa_id']}");
                            foreach ($read->getResult() as $categoria_id):
                                extract($categoria_id);
                                echo"<option ";
                                echo "value=\"{$categoria_id}\">{$categoria_nome}</option>";
                            endforeach;
                            ?>
                        </select>
                    </div>
                    <div class="area-upload" >
                        <label for="fileArquivos" class="label-upload">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <div class="texto">Clique aqui ou arraste o(s) arquivo(s) :)</div>
                        </label>
                        <input type="file"  id="fileArquivos" onchange="newInputFile(this)"/>
                        <ul id="dp-arquivos"></ul>
                    </div>
                    <div class="col-12" >
                        <!--<button class="btn btn-block btn-sm btn-success btn-flat" type="button" id="btEnviar" ><i class="fas fa-save"></i>&nbsp;&nbsp;Anexar!</button>-->
                    </div>
                </div>
                <div class="row">
                    <link rel="stylesheet"  href="dist/css/style_upload_master.css"/>
                    <script src="dist/js/script_upload_master.js"></script>
                    <table  class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Identificador</th>
                                <th>Nome</th>
                                <th>Link</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody id="itens_arquivos">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>


<script type="text/javascript">

                            function newInput(input) {
                                this.cadastraImagem();
                            }
                            function cadastraImagem() {

                                //                        $("body").addClass("loading");
                                var form_data = new FormData();
                                form_data.append('file', $('#file').prop('files')[0]);
                                form_data.append('id', <?= $id ?>);
                                form_data.append('imagem_noticia_id', <?= $id ?>);
                                form_data.append('imagem_descricao', $('#imagem_descricao').val());
                                form_data.append('imagem_credito', $('#imagem_credito').val());
                                form_data.append('imagem_prioridade', $('#imagem_prioridade').val());
                                form_data.append('imagem_empresa_id', <?= $userlogin['usuario_empresa_id'] ?>);
                                form_data.append('type', 'noticias');
                                $.ajax({
                                    url: '../_request/uploadImgUpdate.php',
                                    dataType: 'text',
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    data: form_data,
                                    type: 'post',
                                    success: function (data) {
                                        const obj = JSON.parse(data);
                                        toastr.success('<b>Sucesso!</b><br>A imagem foi incluida com sucesso!');
                                        $('#itens_imagens').append("<tr><td>" + obj.nome + "</td><td>" + obj.link + "</td><td>" + obj.prioridade + "</td><td>\n\
                     <botton class=\"btn btn-sm btn-sm btn-flat btn-danger\" id=\"removeAnexo\" onclick=\"excluirImagem(" + obj.id + ")\"><i class=\"fas fa-trash\"></i></botton></td></tr>");
                                        $("#imagem_prioridade").val(1);
                                        $("#imagem_credito").val('');
                                        $("#imagem_descricao").val('');
                                        //                                                    $("body").removeClass("loading");
                                    },
                                    error: function () {
                                        toastr.warning('<b>OPSSS!</b><br>Não foi possível anexar este arquivo. Verifique o tamanho do arquivo a ser enviado. Tente Novamente!');
                                    }

                                });
                            }

                            function excluirImagem(id, link) {
                                var r = confirm("Deseja excluir esse documento?");
                                if (r == true) {
                                    var form_data = new FormData();
                                    form_data.append('id', id);
                                    form_data.append('link', link);
                                    $.ajax({
                                        url: '../_request/deleteImg.php',
                                        dataType: 'text',
                                        cache: false,
                                        contentType: false,
                                        processData: false,
                                        data: form_data,
                                        type: 'post',
                                        success: function (data) {
                                            if (data) {
                                                toastr.success('<b>Sucesso ao deletar!</b><br>O arquivo foi apagado com sucesso!');
                                                carregaImagens();
                                            } else {
                                                toastr.warning('<b>OPSSS!</b><br>Aconteceu algo de errado ao apagar o arquivo, tente novamente!');
                                            }
                                        }
                                    });
                                }
                            }
                            function carregaImagens() {
                                var filesStr = null;
                                document.getElementById("itens_imagens").innerHTML = filesStr;
                                var titulo = $.getJSON("../_request/getImgs.php", "id=" + <?= $id ?> + "&type=noticia", function (data) {
                                    var valor = data;
                                    valor.forEach(item => {
                                        $('#itens_imagens').append("<tr><td>" + item.imagem_nome + "</td><td>http://" + item.imagem_host + '/' + item.imagem_link + "</td><td>" + item.imagem_prioridade + "</td><td>\n\
                                 <botton class=\"btn btn-sm btn-sm btn-flat btn-danger\" id=\"removeAnexo\" onclick=\"excluirImagem(" + item.imagem_id + ")\"><i class=\"fas fa-trash\"></i></botton></td></tr>");
                                    });
                                });
                            }


                            function newInputFile(input) {
                                this.cadastraArquivos();
                            }
                            function cadastraArquivos() {
                                //                        $("body").addClass("loading");
                                var form_data = new FormData();



                                form_data.append('file', $('#fileArquivos').prop('files')[0]);
                                form_data.append('id', <?= $id ?>);
                                form_data.append('id_noticia', <?= $id ?>);
                                form_data.append('arquivo_descricao', $('#arquivo_descricao').val());
                                if ($('#arquivo_categoria_id').val() != null && $('#arquivo_categoria_id').val() !== undefined) {
                                    form_data.append('arquivo_categoria_id', $('#arquivo_categoria_id').val());
                                } else {
                                    form_data.append('arquivo_categoria_id', 15);
                                }
                                form_data.append('arquivo_identificador', 'Anexo de Noticia');
                                form_data.append('arquivo_empresa_id', <?= $userlogin['usuario_empresa_id'] ?>);
                                form_data.append('type', 'noticias');
                                $.ajax({
                                    url: '../_request/uploadFiles.php',
                                    dataType: 'text',
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    data: form_data,
                                    type: 'post',
                                    success: function (data) {
                                        const obj = JSON.parse(data);
                                        toastr.success('<b>Sucesso!</b><br>A imagem foi incluida com sucesso!');
                                        $('#itens_arquivos').append("<tr><td>" + obj.descricao + "</td><td>" + obj.nome + "</td><td>" + obj.link + "</td><td>\n\
                     <botton class=\"btn btn-sm btn-sm btn-flat btn-danger\" id=\"removeAnexo\" onclick=\"excluirArquivos(" + obj.id + ")\"><i class=\"fas fa-trash\"></i></botton></td></tr>");
                                        $("#arquivo_categoria_id").val(1);
                                        $("#arquivo_identificador").val('');
                                        $("#arquivo_descricao").val('');
                                        //                                                    $("body").removeClass("loading");
                                    },
                                    error: function () {
                                        toastr.warning('<b>OPSSS!</b><br>Não foi possível anexar este arquivo. Verifique o tamanho do arquivo a ser enviado. Tente Novamente!');
                                    }

                                });
                            }

                            function excluirArquivos(id) {
                                var r = confirm("Deseja excluir esse documento?");
                                if (r == true) {
                                    var form_data = new FormData();
                                    form_data.append('id', id);
                                    $.ajax({
                                        url: '../_request/deleteFile.php',
                                        dataType: 'text',
                                        cache: false,
                                        contentType: false,
                                        processData: false,
                                        data: form_data,
                                        type: 'post',
                                        success: function (data) {
                                            if (data) {
                                                toastr.success('<b>Sucesso ao deletar!</b><br>O arquivo foi apagado com sucesso!');
                                                carregaArquivos();
                                            } else {
                                                toastr.warning('<b>OPSSS!</b><br>Aconteceu algo de errado ao apagar o arquivo, tente novamente!');
                                            }
                                        }
                                    });
                                }
                            }
                            function carregaArquivos() {
                                var filesStr = null;
                                document.getElementById("itens_arquivos").innerHTML = filesStr;
                                var titulo = $.getJSON("../_request/getFiles.php", "id=" + <?= $id ?> + "&type=noticia", function (data) {
                                    var valor = data;
                                    valor.forEach(item => {
                                        $('#itens_arquivos').append("<tr><td>" + item.arquivo_descricao + "</td><td>" + item.arquivo_nome + "</td><td>http://" + item.arquivo_host + '/' + item.arquivo_link + "</td><td>\n\
                                 <botton class=\"btn btn-sm btn-sm btn-flat btn-danger\" id=\"removeAnexo\" onclick=\"excluirArquivos(" + item.arquivo_id + ")\"><i class=\"fas fa-trash\"></i></botton></td></tr>");
                                    });
                                });
                            }
                            $(document).ready(function () {
                                carregaImagens();
                                carregaArquivos();
                            });
</script>
