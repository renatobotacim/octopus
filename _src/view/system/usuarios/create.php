<?php
//VERIFICA A EXISTÊNCIA DE UM LOGIM, CASO SIM, ELE SEMPRE DIRECIONA PARA O PAINEL, CASO NÃO, DIRECIONA A TELA DE LOGIN
$checkLevel = new Login(3);
if (!$checkLevel->CheckLevel()):
    $link = 'painel.php?restrito=true'; // especifica o endereço
    redireciona($link); // chama a função
endif;

if (!class_exists('Login')) :
    header('Location: ../../index.php');
    die;
endif;

$Data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!empty($Data['sendForm'])):
    unset($Data['sendForm']);
    require_once ('../Controllers/usuarioController.class.php');
    $controller = new usuarioController();
    $Data['usuario_empresa_id'] = $userlogin['usuario_empresa_id'];
    var_dump($Data);
    $controller->create($Data);
endif;

$icone = "fas fa-users";
require_once 'includes/navegador.php';
?>
<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">
                    <i class="fas fa-info-circle"></i>&nbsp;User Data</h3>
            </div>
            <form name="fileForm" action="" method="post" enctype="multipart/form-data">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>CPF</label>
                                <input class="form-control form-control-sm" type="text" name="usuario_cpf" id="usuario_cpf" value="<?php if (isset($Data)) echo $Data['usuario_cpf'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nome</label>
                                <input class="form-control form-control-sm" type="text" name="usuario_nome" id="usuario_nome" value="<?php if (isset($Data)) echo $Data['usuario_nome'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Email</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input class="form-control form-control-sm" type="email" name="usuario_email" id="usuario_email" value="<?php if (isset($Data)) echo $Data['usuario_email'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Telefone</label>
                                <input class="form-control form-control-sm" type="text" name="usuario_telefone" id="usuario_telefone" value="<?php if (isset($Data)) echo $Data['usuario_telefone'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Usuário</label>
                                <input class="form-control form-control-sm" type="text" name="usuario_usuario" id="usuario_usuario" value="<?php if (isset($Data)) echo $Data['usuario_usuario'] ?>">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Permissão</label>
                                <select class="form-control form-control-sm" id="usuario_permissao" name="usuario_permissao">
                                    <option disabled="disabled" selected="selected" value="null">Selecione</option>                                     
                                    <option value="1"  <?php if (isset($Data['usuario_permissao']) && $Data['usuario_permissao'] == 1) echo 'selected' ?>>Operador</option>
                                    <option value="2"  <?php if (isset($Data['usuario_permissao']) && $Data['usuario_permissao'] == 2) echo 'selected' ?>>Gestor</option>
                                    <?php
                                    if ($userlogin['usuario_permissao'] >= 3):
                                        if (isset($Data['usuario_permissao']) && $Data['usuario_permissao'] == 3):
                                            $selecionado = "<option value=\"3\" selected >Administrador</option>";
                                        else:
                                            $selecionado = '<option value=\"3\">Administrador</option>';
                                        endif;
                                    endif;
                                    ?>                         
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Inicio das Atividades</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                    </div>
                                    <input class="form-control form-control-sm" disabled type="date" name="usuario_start" id="usuario_start" value="<?php if (isset($Data['usuario_start'])) echo $Data['usuario_start'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Término das Atividades</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                    </div>
                                    <input class="form-control form-control-sm" disabled type="date" name="usuario_end" id="usuario_end" value="<?php if (isset($Data['usuario_end'])) echo $Data['usuario_end'] ?>">
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="row row justify-content-md-center mt-3">
                        <div class="col-md-2">
                            <a href="painel.php?exe=<?= $navegacao[0] ?>/index" class="btn btn-danger btn-block btn-flat btn-sm"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Cancelar/Votlar</a>
                        </div>
                        <div class="col-md-2">
                            <button type="reset" value="clear" name="clear" class="btn btn-warning btn-block btn-flat btn-sm"><i class="fas fa-eraser"></i>&nbsp;&nbsp;Limpar Formulário</button>
                        </div>
                        <div class="col-md-2">
                            <a href="painel.php?exe=<?= $navegacao[0] ?>/create" class="btn btn-info btn-block btn-flat btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Novo Usuário</a>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" value="Cadastrar" name="sendForm" class="btn btn-success btn-block btn-flat btn-sm"><i class="fas fa-save"></i>&nbsp;&nbsp;Cadastrar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
