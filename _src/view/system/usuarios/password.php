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

$AlterPassword = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!empty($AlterPassword['alterPassword'])):
    if ($AlterPassword['alterPassword'] == 'default'):
        $controller->password($id, 'default');
    else:
        if ($AlterPassword['usuario_senha'] == $AlterPassword['usuario_senha_confirm']):
            $controller->password($id, $AlterPassword['usuario_senha']);

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
            <!--Título do box-->
            <div class="card-header"><h3 class="card-title"><i class="fas fa-lock"></i>&nbsp;&nbsp;Alterar Senha</h3></div>
            <!--início do corpo do box-->
            <div class="card-body">
                <form name="fileForm" action="" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Senha</label>
                                <input class="form-control form-control-sm" type="password" name="usuario_senha" id="usuario_senha">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Confirmação da senha</label>
                                <input class="form-control form-control-sm" type="password" name="usuario_senha_confirm" id="usuario_senha_confirm">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="text-center">
                                <button type="submit" onclick="return confirm('Você realmente deseja alterar sua senha?')" value="pass" name="alterPassword" class="btn btn-info btn-block btn-sm btn-flat"><i class="fas fa-edit"></i>&nbsp;&nbsp;Alterar Senha de Acesso</button>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row mt-5">
                        <div class="col-12">
                            <p>A opção de Resetar senha envia uma nova senha aleatória para o email do usuário. Assim, ele deverá logar por essa nova senha. Lembrando que o usuário poderá trocar a senha quando desejar em seu painel.</p>
                        </div>
                        <div class="col-12">
                            <button type="submit" onclick="return confirm('Deseja resetar a senha deste usuário?')" value="default" name="alterPassword" class="btn btn-secondary btn-block btn-sm btn-flat"><i class="fas fa-undo-alt"></i>&nbsp;&nbsp;Resetar Senha</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
