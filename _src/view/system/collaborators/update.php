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
  IMDErro('<b>Sucesso :D</b><br>Registro cadastrado com sucesso! Você agora pode revisar os dados ou editar alguma informação necessária!', IMD_ACCEPT);
endif;

require_once('../Controllers/CollaboratorController.class.php');
$controller = new CollaboratorController();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$Data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (!empty($Data['SendAlter'])):
  unset($Data['SendAlter']);
  if ($_FILES['colaborador_imagem']['size']):
    $Data['colaborador_imagem'] = $_FILES['colaborador_imagem'];
  endif;
  $Data["colaborador_empresa_id"] = $userlogin['usuario_empresa_id'];
  if ($controller->updade($id, $Data)):
    $Data = $controller->read($id, $userlogin['usuario_empresa_id']);
  endif;

else:
  $Data = $controller->read($id, $userlogin['usuario_empresa_id']);
//    if (!empty($Data['licitacao_certame'])):
//        $date = new DateTime($Data['licitacao_certame']);
//        $Data['licitacao_certame'] = $date->format('Y-m-d\TH:i');
//    endif;
endif;

//$readPessoas = new Read;
//$readPessoas->FullRead("select colaborador_id, nome from inf_colaboradors where status = 2 and colaborador_empresa_id = :idEmp", "idEmp={$userlogin['usuario_empresa_id']}");

$icone = "fas fa-users";
require_once 'includes/navegador.php';
?>
<section class="content">
  <div class="container-fluid">
    <div class="card card-primary card-outline">
      <div class="card-header"><h3 class="card-title">
          <i class="fas fa-info-circle"></i>&nbsp;Dados da Pessoa</h3>
      </div>
      <form name="fileForm" method="post" enctype="multipart/form-data">
        <div class="card-body">
          <div>
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <input type="file" name="colaborador_imagem" accept="image/*" id="path_foto" class="inputfile"
                         onchange="previewImage()"/>
                  <label class="uploadArquivo" for="path_foto">
                    <?php
                    if (!isset($Data['imagem_link'])):
                      echo "<img src=\"https://www.w3schools.com/w3images/avatar2.png\" width=\"100%\" heigth=\"auto\" style=\" max-height: 300px;\"";
                      echo "<img id=\"preview\" width=\"100%\" heigth=\"auto\" style=\"position: relative; top:0;left: 0;max-height: 300px;\">";
                    else:
                      echo "<img src=\"../uploads{$Data['imagem_link']}\" width=\"100%\" heigth=\"auto\" style=\" max-height: 300px;\"";
                      echo "<img id=\"preview\" width=\"100%\" heigth=\"auto\" style=\"position: relative; top:0;left: 0;max-height: 300px;\">";
                    endif;
                    ?>
                  </label>
                </div>
              </div>
              <div class="col-md-9">
                <div class="row">
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>Nome</label>
                      <input type="text" name="colaborador_nome" id="colaborador_nome" class="form-control"
                             placeholder="Nome" value="<?php if (isset($Data)) echo $Data['colaborador_nome']; ?>">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>Função</label>
                      <input type="text" name="colaborador_funcao" id="colaborador_funcao" class="form-control"
                             placeholder="Função" value="<?php if (isset($Data)) echo $Data['colaborador_funcao']; ?>">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>Telefone</label>
                      <input type="text" name="colaborador_telefone" id="colaborador_telefone" class="form-control"
                             placeholder="Telefone"
                             value="<?php if (isset($Data)) echo $Data['colaborador_telefone']; ?>">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>Email</label>
                      <input type="text" name="colaborador_email" id="colaborador_email" class="form-control"
                             placeholder="E-mail" value="<?php if (isset($Data)) echo $Data['colaborador_email']; ?>">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>Facebook</label>
                      <input type="text" name="colaborador_facebook" id="colaborador_facebook" class="form-control"
                             placeholder="Facebook"
                             value="<?php if (isset($Data)) echo $Data['colaborador_facebook']; ?>">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>Twitter</label>
                      <input type="text" name="colaborador_twitter" id="colaborador_twitter" class="form-control"
                             placeholder="Twitter"
                             value="<?php if (isset($Data)) echo $Data['colaborador_twitter']; ?>">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>Instagram</label>
                      <input type="text" name="colaborador_instagram" id="colaborador_instagram" class="form-control"
                             placeholder="Instagram"
                             value="<?php if (isset($Data)) echo $Data['colaborador_instagram']; ?>">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>Google +</label>
                      <input type="text" name="colaborador_google" id="colaborador_google" class="form-control"
                             placeholder="Google" value="<?php if (isset($Data)) echo $Data['colaborador_google']; ?>">
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label>Resumo</label>
                      <textarea type="text" rows="3" name="colaborador_informacoes" id="colaborador_informacoes"
                                class="form-control" placeholder="Insira um comentário do colaborador"
                                value="<?php if (isset($Data)) echo $Data['colaborador_informacoes']; ?>"></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label>Biografia</label>
                  <textarea class="textarea summernote" name="colaborador_biografia"
                            placeholder="Escreva aqui o conteúdo"
                            style="width: 100%; height: 400px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?php if (isset($Data)) echo $Data['colaborador_biografia'] ?>
                                </textarea>
                </div>
              </div>
            </div>

          </div>
          <hr>
          <div class="row justify-content-md-center my-5">
            <div class="col-md-2">
              <a href="painel.php?exe=<?= $navegacao[0] ?>/index" class="btn btn-block btn-danger btn-flat btn-sm"><i
                  class="fa fa-arrow-circle-left"></i>&nbsp;Voltar</a>
            </div>
            <div class="col-md-2">
              <div class="text-center">
                <button type="submit" value="Cadastrar" name="SendAlter"
                        class="btn btn-block btn-success btn-flat btn-sm"><i class="fas fa-save"></i>&nbsp;&nbsp;Salvar
                </button>
              </div>
            </div>
          </div>
        </div>
      </form>
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

  .uploadArquivo {
    text-align: center;
    cursor: pointer;
  }

  .uploadArquivo .mudarfoto {
    position: absolute;
    font-size: 1.5em;
    top: 50%;
    left: 40%;
    transform: translateY(-50%);
    display: none;
  }

  .inputfile + label:hover, .inputfile + label img {
    opacity: 0.9
  }
</style>
<script>
  function previewImage() {
    var previewBox = document.getElementById("preview");
    previewBox.src = URL.createObjectURL(event.target.files[0]);
  }
  ;
  window.onload = function () {
    CKEDITOR.replace('colaborador_biografia');
  };
</script>

<link rel="stylesheet" href="dist/css/style_upload_master.css"/>
<script src="dist/js/script_upload_master.js"></script>
<script type="text/javascript">


  //                                function newInputFile(input) {
  //                                    this.cadastraArquivos('id_licitacao');
  //
  //                                }
  //
  //                                function cadastraArquivos(tipo, index) {
  //                                    $("body").addClass("loading");
  //                                    var form_data = new FormData();
  //                                    form_data.append('id', <?= $id ?>);
  //                                    form_data.append(tipo, <?= $id ?>);
  //                                    form_data.append('arquivo_descricao', $('#arquivo_descricao').val());
  //                                    form_data.append('arquivo_categoria_id', 8);
  //                                    form_data.append('arquivo_identificador', 'Licitação');
  //                                    form_data.append('arquivo_ordem', $('#arquivo_ordem').val());
  //                                    form_data.append('arquivo_empresa_id', <?= $userlogin['usuario_empresa_id'] ?>);
  //                                    form_data.append('type', 'licitacao/' +<?= $id ?>);
  //                                    form_data.append('file', $('#fileArquivos').prop('files')[0]);
  //                                    $.ajax({
  //                                        url: '../_request/uploadFiles.php',
  //                                        dataType: 'text',
  //                                        cache: false,
  //                                        contentType: false,
  //                                        processData: false,
  //                                        data: form_data,
  //                                        type: 'post',
  //                                        success: function (data) {
  //                                            const obj = JSON.parse(data);
  //                                            console.log(obj);
  //                                            toastr.success('<b>Sucesso!</b><br>O Upload do arquivo foi realizado com sucesso! Se o arquivo não aparecer na listagem abaixo, salve as instruições e atualize a página.');
  //                                            $("#arquivo_identificador").val('');
  //                                            $("#arquivo_descricao").val('');
  //                                            $("#arquivo_ordem").val('');
  //                                            $("#fileArquivos").val('');
  //                                            $("body").removeClass("loading");
  //                                            carregaArquivos();
  //                                        },
  //                                        error: function () {
  //                                            toastr.warning('<b>OPSSS!</b><br>Não foi possível realizar o upload do arquivo. Verfique se as informações estão corretas');
  //                                        }
  //                                    });
  //                                }
  //
  //                                function excluirArquivos(id) {
  //                                    var r = confirm("Deseja excluir esse documento?");
  //                                    if (r == true) {
  //                                        var form_data = new FormData();
  //                                        form_data.append('id', id);
  //                                        $.ajax({
  //                                            url: '../_request/deleteFile.php',
  //                                            dataType: 'text',
  //                                            cache: false,
  //                                            contentType: false,
  //                                            processData: false,
  //                                            data: form_data,
  //                                            type: 'post',
  //                                            success: function (data) {
  //                                                if (data) {
  //                                                    toastr.success('<b>Sucesso ao deletar!</b><br>O arquivo foi apagado com sucesso!');
  //                                                    carregaArquivos();
  //                                                } else {
  //                                                    toastr.warning('<b>OPSSS!</b><br>Aconteceu algo de errado ao apagar o arquivo, tente novamente!');
  //                                                }
  //                                            }
  //                                        });
  //                                    }
  //                                }
  //
  //                                function editarArquivos(id) {
  //                                    var titulo = $.getJSON("../_request/getFile.php", "id=" + id, function (data) {
  //                                        var valor = data;
  //                                        valor.forEach(item => {
  //                                            $("#arquivo_ordem_up").val(item.arquivo_ordem);
  //                                            $("#arquivo_id_up").val(item.arquivo_id);
  //                                            $("#arquivo_descricao_up").val(item.arquivo_descricao);
  //                                            $("#arquivo_identificador_up").val(item.arquivo_identificador);
  //                                            $("#arquivo_nome_up").val(item.arquivo_nome);
  ////
  //                                        });
  //                                    });
  //                                }
  //
  //                                function carregaArquivos() {
  //                                    var filesStr = null;
  //                                    document.getElementById("itens_arquivos").innerHTML = filesStr;
  //                                    var titulo = $.getJSON("../_request/getFiles.php", "id=" + <?= $id ?> + '&type=licitacao', function (data) {
  //                                        var valor = data;
  //                                        valor.forEach(item => {
  //                                            $('#itens_arquivos').append("<tr><td>" + item.arquivo_nome + "</td><td>" + item.arquivo_identificador + "</td><td>" + item.arquivo_descricao + "</td><td>" + item.arquivo_ordem + "</td><td><a href=\"http://" + item.arquivo_host + "/" + item.arquivo_link + "\" target=\"_black\" class=\"btn btn-block btn-info btn-flat btn-sm\"><i class=\"fas fa-eye\"></i></a></td>\n\
  //                                                <td>\n\
  //                                                <botton class=\"btn btn-sm btn-sm btn-flat btn-action  btn-primary\" id=\"removeAnexo\" data-toggle=\"modal\" data-target=\"#update-file\" onclick=\"editarArquivos(" + item.arquivo_id + ")\"><i class=\"fas fa-edit\"></i></botton>\n\
  //                                                <botton class=\"btn btn-sm btn-sm btn-flat btn-action  btn-danger\" id=\"removeAnexo\" onclick=\"excluirArquivos(" + item.arquivo_id + ")\"><i class=\"fas fa-trash\"></i></botton>\n\
  //                                                </td></tr>");
  //                                        });
  //                                    });
  //                                }
  //                                $(document).ready(function () {
  //                                    carregaArquivos();
  //                                });
</script>



