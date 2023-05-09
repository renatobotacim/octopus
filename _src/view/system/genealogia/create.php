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
$readPessoas->FullRead("select genealogia_id, nome from inf_genealogias where status = 2 and genealogia_empresa_id = :idEmp", "idEmp={$userlogin['usuario_empresa_id']}");


$Data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!empty($Data['sendForm'])):
    unset($Data['sendForm']);

  require_once('../Controllers/genealogiaController.class.php');
  $controller = new genealogiaController();

  if (!$Data['data_nascimento']):
    $Data['data_nascimento'] = null;
  endif;

  if ($_FILES['file']['size']):
    $Data['file'] = $_FILES['file'];
  endif;

    $Data["status"] = 2;
    $Data["genealogia_empresa_id"] = $userlogin['usuario_empresa_id'];

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
                              <input class="form-control  form-control-sm" name="nome" id="nome" type="text" required
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
                              <input class="form-control  form-control-sm" name="obito" id="obito" type="date"
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
                          <input class="form-control form-control-sm" name="ramal" type="text" value="<?php if (isset($Data)) echo $Data['ramal'] ?>">
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

                    </div>
                  </div>


                    <div class="row justify-content-md-center">
                        <div class="col-md-2">
                            <a href="painel.php?exe=<?= $navegacao[0] ?>/index" class="btn btn-danger btn-block btn-sm btn-flat"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Cancelar / Voltar</a>
                        </div>
                        <div class="col-md-2">
                            <button type="reset" value="clear" name="clear" class="btn btn-warning btn-block btn-sm btn-flat"><i class="fas fa-eraser"></i>&nbsp;Limpar Formulário</button>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" value="Cadastrar" name="sendForm" class="btn btn-success btn-sm btn-block btn-flat"><i class="fas fa-save"></i>&nbsp;Cadastrar</button>
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
