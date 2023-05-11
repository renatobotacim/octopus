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

require_once ('../Controllers/usuarioController.class.php');
$controller = new usuarioController();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$Data = filter_input_array(INPUT_POST, FILTER_DEFAULT);



if (!empty($Data['SendAlter'])):
    unset($Data['SendAlter']);
    $controller->updade($id, $Data);
    $Data = $controller->read($id,$userlogin['usuario_empresa_id']);
    
elseif (!empty($Data['status'])):
    if ($Data['status'] == 'ativar'):
        unset($Data);
        $Data['usuario_status'] = 1;
        $Data['usuario_start'] = date('Y-m-d');
        $Data['usuario_end'] = null;
        var_dump($Data);
    else:
        unset($Data);
        $Data['usuario_status'] = 2;
        $Data['usuario_end'] = date('Y-m-d');
        var_dump($Data);
    endif;
    $controller->updade($id, $Data);
   $Data = $controller->read($id,$userlogin['usuario_empresa_id']);
   var_dump($Data);
else:
    $Data = $controller->read($id,$userlogin['usuario_empresa_id']);
    var_dump($Data);
endif;


$AlterPassword = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!empty($AlterPassword['alterPassword'])):
    if ($AlterPassword['alterPassword'] == 'default'):
        $controller->password($id, 'default');
    else:
        if ($AlterPassword['usuario_senha'] == $AlterPassword['usuario_senha_confirm']):
            $controller->password($id, $AlterPassword['usuario_senha']);
            ;
        else:
            IMDErro('<b>Atenção!</b><br>As senhas informadas não são iguais, tente novamente!', IMD_ALERT);
        endif;
    endif;
endif;

$icone = "fas fa-users";
require_once 'includes/navegador.php';
?>
<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">
                    <i class="fas fa-info-circle"></i>&nbsp;&nbsp;Dados do Usuário</h3>
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

                        <!--<input type="hidden" id="usuario_prefeitura_id"  name="usuario_prefeitura_id" value="<?php if (isset($Data)) echo $Data['usuario_prefeitura_id'] ?>">-->

                        <?php
                        if ($userlogin['usuario_permissao'] >= 3):
                            ?>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Permissão</label>
                                    <select class="form-control form-control-sm" id="usuario_permissao" name="usuario_permissao">
                                        <option disabled="disabled" selected="selected" value="null">Selecione</option>                                     
                                        <option value="1"  <?php if (isset($Data['usuario_permissao']) && $Data['usuario_permissao'] == 1) echo 'selected' ?>>Operador</option>
                                        <option value="2"  <?php if (isset($Data['usuario_permissao']) && $Data['usuario_permissao'] == 2) echo 'selected' ?>>Gestor</option>
                                        <option value="3"  <?php if (isset($Data['usuario_permissao']) && $Data['usuario_permissao'] == 3) echo 'selected' ?>>Administrador</option>
                                    </select>
                                </div>
                            </div>
                            <?php
                        endif;
                        ?>
                        <?php
                        if ($userlogin['usuario_permissao'] == 4):
                            ?>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Empresa</label>
                                    <select class="form-control form-control-sm" id="usuario_empresa_id" name="usuario_empresa_id">
                                        <option disabled="disabled" selected="selected" value="null">Selecione</option>                                     
                                        <option value="2"  <?php if (isset($Data['usuario_empresa_id']) && $Data['usuario_empresa_id'] == 2) echo 'selected' ?>>Climed</option>
                                        <option value="5"  <?php if (isset($Data['usuario_empresa_id']) && $Data['usuario_empresa_id'] == 5) echo 'selected' ?>>Advocavia Caliman</option>
                                        <option value="6"  <?php if (isset($Data['usuario_empresa_id']) && $Data['usuario_empresa_id'] == 6) echo 'selected' ?>>Sindjetibá</option>
                                        <option value="10"  <?php if (isset($Data['usuario_empresa_id']) && $Data['usuario_empresa_id'] == 10) echo 'selected' ?>>Familia Falquetto</option>
                                    </select>
                                </div>
                            </div>
                            <?php
                        endif;
                        ?>


                        <!--                        <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Status</label>
                                                        <select class="form-control form-control-sm" id="usuario_status" name="usuario_status">
                                                            <option disabled="disabled" selected="selected" value="null">Selecione</option>                                     
                                                            <option value="1"  <?php if (isset($Data['usuario_status']) && $Data['usuario_status'] == 1) echo 'selected' ?>>Ativo</option>                                   
                                                            <option value="2"  <?php if (isset($Data['usuario_status']) && $Data['usuario_status'] == 2) echo 'selected' ?>>Inativo</option>                                   
                                                        </select>
                                                    </div>
                                                </div>-->
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
                    <?php
                    if ($userlogin['usuario_permissao'] >= 3):
                        ?>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <a href="painel.php?exe=<?= $navegacao[0] ?>/index" class="btn btn-block btn-secondary btn-sm btn-flat"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Cancelar/Voltar</a>
                            </div>
                            <div class="col-md-3">
                                <?php
                                if ($Data['usuario_status'] == 2):
                                    echo ' <button type="submit" value="ativar" name="status" class="btn btn-danger btn-block btn-sm btn-flat"><i class="fa fa-power-off"></i>&nbsp;&nbsp;Ativar Usuário</button>';
                                else:
                                    echo ' <button type="submit" value="desativar" name="status" class="btn btn-danger btn-block btn-sm btn-flat"><i class="fa fa-power-off"></i>&nbsp;&nbsp;Desativar Usuário</button>';
                                endif;
                                ?>

                            </div>
                            <div class="col-md-3">
                                <a href="painel.php?exe=<?= $navegacao[0] ?>/create" class="btn btn-info btn-block btn-sm btn-flat"><i class="fa fa-plus"></i>&nbsp;&nbsp;Cadastrar Novo Usuário</a>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <button type="submit" value="Cadastrar" name="SendAlter" class="btn btn-block btn-sm btn-success btn-flat" <?php if ($Data['usuario_status'] == 2) echo 'disabled' ?> ><i class="fas fa-save"></i>&nbsp;&nbsp;Salvar Alterações</button>
                                </div>
                            </div>
                        </div>

                        <?php
                    else:
                        ?>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <a href="painel.php" class="btn btn-block btn-danger btn-sm btn-flat"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Voltar</a>
                            </div>
                            <div class="col-md-6">
                                <div class="text-center">
                                    <button type="submit" value="Cadastrar" name="SendAlter" class="btn btn-block btn-success btn-sm btn-flat"><i class="fas fa-save"></i>&nbsp;&nbsp;Salvar Alterações</button>
                                </div>
                            </div>
                        </div>
                    <?php
                    endif;
                    ?>
                </div>
            </form>
        </div>

        <div class="card card-primary card-outline">
            <!--Título do box-->
            <div class="card-header"><h3 class="card-title"><i class="fas fa-lock"></i>&nbsp;&nbsp;Gestão de Senha </h3></div>
            <!--início do corpo do box-->
            <div class="card-body">
                <form name="fileForm" action="" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Senha</label>
                                <input class="form-control form-control-sm" type="password" name="usuario_senha" id="usuario_senha">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Confirmação da senha</label>
                                <input class="form-control form-control-sm" type="password" name="usuario_senha_confirm" id="usuario_senha_confirm">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="text-center">
                                <button type="submit" onclick="return confirm('Você realmente deseja alterar sua senha?')" value="pass" name="alterPassword" class="btn btn-info btn-block btn-sm btn-flat"><i class="fas fa-edit"></i>&nbsp;&nbsp;Alterar Senha de Acesso</button>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <button type="submit" onclick="return confirm('Deseja resetar a senha deste usuário?')" value="default" name="alterPassword" class="btn btn-secondary btn-block btn-sm btn-flat"><i class="fas fa-undo-alt"></i>&nbsp;&nbsp;Resetar Senha</button>
                        </div>
                        <div class="col-md-12">
                            <p>A opção de Resetar senha envia uma nova senha aleatória para o email do usuário. Assim, ele deverá logar por essa nova senha. Lembrando que o usuário poderá trocar a senha quando desejar em seu painel.</p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
