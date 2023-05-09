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
  require_once('../Controllers/CategoryController.class.php');
  $controller = new CategoryController();
  $Data['categoria_empresa_id'] = $userlogin['usuario_empresa_id'];
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
            <div class="col-md-3">
              <a href="painel.php?exe=<?= $navegacao[0] ?>/index" class="btn btn-danger btn-block btn-sm btn-flat"><i
                  class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Cancelar / Voltar</a>
            </div>
            <div class="col-md-3">
              <button type="reset" value="clear" name="clear" class="btn btn-warning btn-block btn-sm btn-flat"><i
                  class="fas fa-eraser"></i>&nbsp;Limpar Formulário
              </button>
            </div>
            <div class="col-md-3">
              <a href="painel.php?exe=<?= $navegacao[0] ?>/create" class="btn btn-info btn-sm btn-block btn-flat"><i
                  class="fa fa-plus"></i>&nbsp;&nbsp;Novo Conteúdo</a>
            </div>
            <div class="col-md-3">
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
<!--<script>
    function previewImage() {
        var previewBox = document.getElementById("preview");
        previewBox.src = URL.createObjectURL(event.target.files[0]);
    }
</script>-->
