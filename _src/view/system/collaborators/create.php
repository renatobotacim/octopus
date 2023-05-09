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

$readPessoas = new Read;
$readPessoas->FullRead("select colaborador_id, nome from inf_colaboradors where status = 2 and colaborador_empresa_id = :idEmp", "idEmp={$userlogin['usuario_empresa_id']}");

$Data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!empty($Data['sendForm'])):
  unset($Data['sendForm']);

  require_once('../Controllers/CollaboratorController.class.php');
  $controller = new CollaboratorController();

  if ($_FILES['colaborador_imagem']['size']):
    $Data['colaborador_imagem'] = $_FILES['colaborador_imagem'];
  endif;
  $Data["colaborador_empresa_id"] = $userlogin['usuario_empresa_id'];

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
          <div class="row justify-content-md-center">
            <div class="col-md-2">
              <a href="painel.php?exe=<?= $navegacao[0] ?>/index" class="btn btn-danger btn-block btn-sm btn-flat"><i
                  class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Cancelar / Voltar</a>
            </div>
            <div class="col-md-2">
              <button type="reset" value="clear" name="clear" class="btn btn-warning btn-block btn-sm btn-flat"><i
                  class="fas fa-eraser"></i>&nbsp;Limpar Formulário
              </button>
            </div>
            <div class="col-md-2">
              <button type="submit" value="Cadastrar" name="sendForm" class="btn btn-success btn-sm btn-block btn-flat">
                <i class="fas fa-save"></i>&nbsp;Cadastrar
              </button>
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
