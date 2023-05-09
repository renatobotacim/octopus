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

require_once('../Controllers/genealogiaController.class.php');
$controller = new genealogiaController();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$Data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (!empty($Data['SendAlter'])):
  unset($Data['SendAlter']);

  if (empty($Data['data_nascimento'])) {
    $Data['data_nascimento'] = null;
  }

  if ($_FILES['file']['size']):
    $Data['file'] = $_FILES['file'];
  endif;

  $Data["genealogia_empresa_id"] = $userlogin['usuario_empresa_id'];

  $controller->updade($id, $Data);
else:
  $Data = $controller->read($id, $userlogin['usuario_empresa_id']);
//    if (!empty($Data['licitacao_certame'])):
//        $date = new DateTime($Data['licitacao_certame']);
//        $Data['licitacao_certame'] = $date->format('Y-m-d\TH:i');
//    endif;
endif;

$readPessoas = new Read;
$readPessoas->FullRead("select genealogia_id, nome from inf_genealogias where status = 2 and genealogia_empresa_id = :idEmp", "idEmp={$userlogin['usuario_empresa_id']}");

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
                <input type="file" name="file" accept="image/*" id="path_foto" class="inputfile"
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




            <div class="col-md-4">
              <div class="form-group">
                <label>Nome</label>
                <input class="form-control  form-control-sm" name="nome" id="nome" type="text"
                       value="<?php if (isset($Data)) echo $Data['nome'] ?>">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Cônjuge</label>
                <select class="form-control select2 form-control-sm" id="id_conjuge" name="id_conjuge">
                  <option selected="selected" value="0"> -- Selecione --</option>
                  <?php
                  foreach ($readPessoas->getResult() as $genealogia_id):
                    extract($genealogia_id);
                    echo "<option ";
                    if (isset($Data['id_conjuge']) && $Data['id_conjuge'] == $genealogia_id):
                      echo "selected=\"selected\"";
                    endif;
                    echo "value=\"{$genealogia_id}\">{$nome}</option>";
                  endforeach;
                  ?>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Nome Cônjuge</label>
                <input class="form-control form-control-sm" name="conjuge" id="conjuge" type="text"
                       value="<?php if (isset($Data)) echo $Data['conjuge'] ?>">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Pai</label>
                <select class="form-control select2 form-control-sm" id="id_pai" name="id_pai">
                  <option selected="selected" value="0"> -- Selecione --</option>
                  <?php
                  foreach ($readPessoas->getResult() as $genealogia_id):
                    extract($genealogia_id);
                    echo "<option ";
                    if (isset($Data['id_pai']) && $Data['id_pai'] == $genealogia_id):
                      echo "selected=\"selected\"";
                    endif;
                    echo "value=\"{$genealogia_id}\">{$nome}</option>";
                  endforeach;
                  ?>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Nome Pai</label>
                <input class="form-control form-control-sm" name="pai" id="pai" type="text"
                       value="<?php if (isset($Data)) echo $Data['pai'] ?>">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Mãe</label>
                <select class="form-control select2 form-control-sm" id="id_mae" name="id_mae">
                  <option selected="selected" value="0"> -- Selecione --</option>
                  <?php
                  foreach ($readPessoas->getResult() as $genealogia_id):
                    extract($genealogia_id);
                    echo "<option ";
                    if (isset($Data['id_mae']) && $Data['id_mae'] == $genealogia_id):
                      echo "selected=\"selected\"";
                    endif;
                    echo "value=\"{$genealogia_id}\">{$nome}</option>";
                  endforeach;
                  ?>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Nome Mãe</label>
                <input class="form-control form-control-sm" name="mae" id="mae" type="text"
                       value="<?php if (isset($Data)) echo $Data['mae'] ?>">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Site</label>
                <input class="form-control  form-control-sm" name="site" id="site" type="text"
                       value="<?php if (isset($Data)) echo $Data['site'] ?>">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Apelido</label>
                <input class="form-control  form-control-sm" name="apelido" id="apelido" type="text"
                       value="<?php if (isset($Data)) echo $Data['apelido'] ?>">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Nacionalidade</label>
                <input class="form-control  form-control-sm" name="nacionalidade" id="nacionalidade" type="text"
                       value="<?php if (isset($Data)) echo $Data['nacionalidade'] ?>">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Data de Nascimento</label>
                <input class="form-control  form-control-sm" name="data_nascimento" id="data_nascimento" type="date"
                       value="<?php if (isset($Data)) echo $Data['data_nascimento'] ?>">
              </div>
            </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Data de Óbito</label>
                    <input class="form-control form-control-sm" name="obito" id="obito" type="date"
                           value="<?php if (isset($Data['obito'])) echo $Data['obito'] ?>">
                  </div>
                </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Profissão</label>
                <input class="form-control  form-control-sm" name="profissao" id="profissao" type="text"
                       value="<?php if (isset($Data)) echo $Data['profissao'] ?>">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Home Page</label>
                <input class="form-control  form-control-sm" name="home_page" id="home_page" type="text"
                       value="<?php if (isset($Data)) echo $Data['home_page'] ?>">
              </div>
            </div>


            </div>
            </div>



          </div>
          <hr>
          <p><b>Endereço</b></p>
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>rua</label>
                <input class="form-control  form-control-sm" name="rua" id="rua" type="text"
                       value="<?php if (isset($Data)) echo $Data['rua'] ?>">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Número</label>
                <input class="form-control  form-control-sm" name="numero" id="numero" type="number"
                       value="<?php if (isset($Data)) echo $Data['numero'] ?>">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Bairro</label>
                <input class="form-control  form-control-sm" name="bairro" id="bairro" type="text"
                       value="<?php if (isset($Data)) echo $Data['bairro'] ?>">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Complemento</label>
                <input class="form-control  form-control-sm" name="complemento" id="complemento" type="text"
                       value="<?php if (isset($Data)) echo $Data['complemento'] ?>">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Referência</label>
                <input class="form-control  form-control-sm" name="referencia" id="referencia" type="text"
                       value="<?php if (isset($Data)) echo $Data['referencia'] ?>">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Cep</label>
                <input class="form-control  form-control-sm" name="cep" id="cep" type="text"
                       value="<?php if (isset($Data)) echo $Data['cep'] ?>">
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label>Cidade</label>
                <select class="form-control select2 form-control-sm" id="id_cidade" name="id_cidade">
                  <option disabled="disabled" selected="selected" value="null"> -- Selecione --</option>
                  <?php
                  $read = new Read;
                  $read->FullRead("select cidade_nome, cidade_id from inf_cidades");
                  foreach ($read->getResult() as $cidade_id):
                    extract($cidade_id);
                    echo "<option ";
                    if (isset($Data['id_cidade']) && $Data['id_cidade'] == $cidade_id):
                      echo "selected=\"selected\"";
                    endif;
                    echo "value=\"{$cidade_id}\">{$cidade_nome}</option>";
                  endforeach;
                  ?>
                </select>
              </div>
            </div>
          </div>
          <hr>
          <p><b>Contato</b></p>
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Telefone</label>
                <input class="form-control  form-control-sm" name="telefone" id="telefone" type="tel"
                       value="<?php if (isset($Data)) echo $Data['telefone'] ?>">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Ramal</label>
                <input class="form-control  form-control-sm" name="ramal" id="ramal" type="number"
                       value="<?php if (isset($Data)) echo $Data['ramal'] ?>">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Celular</label>
                <input class="form-control  form-control-sm" name="celular" id="celular" type="tel"
                       value="<?php if (isset($Data)) echo $Data['celular'] ?>">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Fax</label>
                <input class="form-control  form-control-sm" name="fax" id="fax" type="number"
                       value="<?php if (isset($Data)) echo $Data['fax'] ?>">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Email</label>
                <input class="form-control  form-control-sm" name="email" id="email" type="email"
                       value="<?php if (isset($Data)) echo $Data['email'] ?>">
              </div>
            </div>


            <div class="col-md-3">
              <div class="form-group">
                <label>Status</label>
                <select class="form-control form-control-sm" id="status" name="status">
                  <option disabled="disabled" selected="selected" value="null"> -- Selecione --</option>
                  <option <?php if (isset($Data['status']) && $Data['status'] == 2) echo 'selected' ?> value="2">ATIVA
                  </option>
                  <option <?php if (isset($Data['status']) && $Data['status'] == 1) echo 'selected' ?> value="1">
                    INATIVA
                  </option>
                </select>
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



