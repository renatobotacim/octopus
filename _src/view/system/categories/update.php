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
    IMDErro('<b>Sucesso</b><br>O conteúdo foi cadastrdo!', IMD_ACCEPT);
endif;

require_once ('../Controllers/CategoryController.class.php');
$controller = new CategoryController();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$Data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!empty($Data['SendAlter'])):
    unset($Data['SendAlter']);
//    if ($_FILES['media']['size']):
//        $Data['media'] = $_FILES['media'];
//    endif;
    $controller->updade($id, $Data);
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
                    <i class="fas fa-info-circle"></i>&nbsp;Dados do Serviço </h3>
            </div>
            <form name="fileForm" action="" method="post" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="row">

                    <div class="col-md-12 col-sm-12">
                      <div class="form-group">
                        <label>Nome</label>
                        <input class="form-control form-control-sm" type="text" name="categoria_nome" id="categoria_nome"
                               value="<?php if (isset($Data)) echo $Data['categoria_nome'] ?>">
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Subtítulo</label>

                        <select class="form-control form-control-sm" id="categoria_tipo_categoria_id"
                                name="categoria_status">
                          <option selected="selected"
                                  value="1" <?php if (isset($data) && $data['categoria_status'] == 1) : echo "selected"; endif; ?>>
                            Inativo
                          </option>
                          <option
                            value="2" <?php if (isset($data) && $data['categoria_status'] == 2) : echo "selected"; endif; ?>>
                            Ativo
                          </option>
                        </select>

                      </div>
                    </div>


                  </div>

                  <div class="row justify-content-md-center">
                        <div class="col-md-2">
                            <a href="painel.php?exe=<?= $navegacao[0] ?>/index" class="btn btn-block btn-danger btn-flat btn-sm"><i class="fa fa-arrow-circle-left"></i>&nbsp;Cancelar / Voltar</a>
                        </div>
                        <div class="col-md-2">
                            <a href="painel.php?exe=<?= $navegacao[0] ?>/create" class="btn btn-info btn-block btn-flat btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Novo Conteúdo</a>
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

<script type="text/javascript">

                            function newInput(input) {
                                this.cadastraImagem();
                            }
                            function cadastraImagem() {

                                //                        $("body").addClass("loading");
                                var form_data = new FormData();
                                form_data.append('file', $('#file').prop('files')[0]);
                                form_data.append('id', <?= $id ?>);
                                form_data.append('imagem_conteudo_id', <?= $id ?>);
                                form_data.append('imagem_descricao', $('#imagem_descricao').val());
                                form_data.append('imagem_credito', $('#imagem_credito').val());
                                form_data.append('imagem_prioridade', $('#imagem_prioridade').val());
                                form_data.append('imagem_empresa_id', <?= $userlogin['usuario_empresa_id'] ?>);
                                form_data.append('type', 'conteudos');
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
                                var titulo = $.getJSON("../_request/getImgs.php", "id=" + <?= $id ?> + "&type=conteudo", function (data) {
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
                                form_data.append('id_arquivo', <?= $id ?>);
                                form_data.append('type', 'conteudos');
                                form_data.append('arquivo_descricao', $('#arquivo_descricao').val());
                                form_data.append('arquivo_identificador', $('#arquivo_identificador').val());
                                form_data.append('arquivo_empresa_id', <?= $userlogin['usuario_empresa_id'] ?>);
                                if ($('#arquivo_categoria_id').val() != null && $('#arquivo_categoria_id').val() !== undefined) {
                                    form_data.append('arquivo_categoria_id', $('#arquivo_categoria_id').val());
                                } else {
                                    form_data.append('arquivo_categoria_id', <?= $id ?>);
                                }
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
                                        $("#arquivo_categoria_id").val( <?= $id ?>);
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
                                var titulo = $.getJSON("../_request/getFilesByCategory.php", "id=" + <?= $id ?> + "&type=arquivo_categoria_id", function (data) {
                                    var valor = data;
                                    valor.forEach(item => {
                                        $('#itens_arquivos').append("<tr><td>" + item.arquivo_descricao + "</td><td>" + item.arquivo_nome + "</td><td>http://" + item.arquivo_host + '/' + item.arquivo_link + "</td><td>\n\
                                 <botton class=\"btn btn-sm btn-sm btn-flat btn-danger\" id=\"removeAnexo\" onclick=\"excluirArquivos(" + item.arquivo_id + ")\"><i class=\"fas fa-trash\"></i></botton></td></tr>");
                                    });
                                });
                            }
                            $(document).ready(function () {
                                $("#arquivo_categoria_id").val(<?= $id ?>);
                                carregaImagens();
                                carregaArquivos();
                            });
</script>
