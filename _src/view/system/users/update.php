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


require_once ('../Controllers/userController.class.php');
$controller = new userController();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$Data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (!empty($Data['SendAlter'])):
    unset($Data['SendAlter']);
    $controller->updade($id, $Data);
else:
    $Data = $controller->read($id);
endif;

$AlterPassword = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!empty($AlterPassword['alterPassword'])):
    if ($AlterPassword['alterPassword'] == 'default'):
        $controller->password($id, 'default');
    else:
        if ($AlterPassword['password'] == $AlterPassword['confirm_password']):
            $controller->password($id, $AlterPassword['password']);
        else:
            IMDErro('<b>Sorry!</b><br>But the passwords entered are not the same. Try again!', IMD_ALERT);
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
                    <i class="fas fa-info-circle"></i>&nbsp;User Data</h3>
            </div>
            <form name="fileForm" action="" method="post" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label>Name</label>
                                <input class="form-control" type="text" name="user_name" id="user_name" value="<?php if (isset($Data)) echo $Data['user_name'] ?>">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group" style="color: ">
                                <label>Email</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input class="form-control" type="email" name="email" id="email" value="<?php if (isset($Data)) echo $Data['email'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-4"> 
                            <div class="form-group">
                                <label>Login</label>
                                <input class="form-control" type="tel" name="login" id="login" value="<?php if (isset($Data)) echo $Data['login'] ?>">
                            </div>
                        </div>
                        <?php
                        if ($userlogin['user_level'] == 4):
                            ?>

                            <div class="col-8">
                                <div class="form-group">
                                    <label>Search Company</label>
                                    <input class="form-control" type="text" id="search" placeholder="Inform a company for research">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label>&nbsp;</label>
                                <button type="button" id="alterStatus" class="btn btn-info btn-block btn-flat"><i class="fas fa-search"></i>&nbsp;Search</button>
                            </div>
                            <input style="display: none" type="num" id="datacompany" value="<?php if (isset($Data)) echo $Data['company'] ?>">
                            <script type='text/javascript'>
                                $(document).ready(function () {
                                    var valor = $('#datacompany').val();
                                    var estado = $.getJSON("../_request/CompanyNameRead.php", "id=" + valor, function (data) {
                                        var dados = data;
                                        $("#company").empty();
                                        dados.forEach(item => {
                                            $('#company').append('<option value="' + item.id_company + '">' + item.company_name + '</option>');
                                        });
                                    });
                                    $('#alterStatus').click('change', function () {
                                        $("#company").prop("disabled", false);
                                        var valor = $('#search').val();
                                        var estado = $.getJSON("../_request/listCompanyRead.php", "search=" + valor, function (data) {
                                            var dados = data;
                                            $("#company").empty();
                                            dados.forEach(item => {
                                                $('#company').append('<option value="' + item.id_company + '">' + item.company_name + '</option>');
                                            });
                                        });
                                    });
                                });
                            </script>

                            <div class="col-6">
                                <div class="form-group">
                                    <label>Company</label>
                                    <select class="form-control select2"  id="company" name="company">
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>User Level</label>
                                    <select class="form-control" id="id_user_level" name="id_user_level">
                                        <option disabled="disabled" selected="selected" value="null">Selecione</option>                                     
                                        <?php
                                        $read = new Read;
                                        $read->ExeRead(SCHEMA . 'user_level');
                                        foreach ($read->getResult() as $id_user_level):
                                            extract($id_user_level);
                                            echo"<option ";
                                            if (isset($Data['id_user_level']) && $Data['id_user_level'] == $id_user_level):
                                                echo "selected=\"selected\"";
                                            endif;
                                            echo "value=\"{$id_user_level}\"> {$user_level_desc} </option>";
                                        endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <?php
                        endif;
                        ?>

                    </div>
                </div>
                <div class="col-md-12 card-primary card-outline">
                    <!--Título do box-->
                    <div class="card-header"><h3 class="card-title"><i class="fa fa-arrow-circle-left"></i>&nbsp;Save Data </h3></div>
                    <!--início do corpo do box-->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <a href="painel.php?exe=<?= $navegacao[0] ?>/index" class="btn btn-block btn-danger btn-flat"><i class="fa fa-arrow-circle-left"></i>&nbsp;Cancel</a>
                            </div>
                            <div class="col-4">
                                <a href="painel.php?exe=<?= $navegacao[0] ?>/create" class="btn btn-info btn-block btn-flat"><i class="fa fa-plus"></i>&nbsp;&nbsp;New</a>
                            </div>
                            <div class="col-4">
                                <div class="text-center">
                                    <button type="submit" value="Cadastrar" name="SendAlter" class="btn btn-block btn-success btn-flat"><i class="fas fa-save"></i>&nbsp;Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="card card-primary card-outline">
            <!--Título do box-->
            <div class="card-header"><h3 class="card-title"><i class="fas fa-lock"></i>&nbsp;Password </h3></div>
            <!--início do corpo do box-->
            <div class="card-body">
                <form name="fileForm" action="" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Password</label>
                                <input class="form-control" type="password" name="password" id="password">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Confirm your password</label>
                                <input class="form-control" type="password" name="confirm_password" id="confirm_password">
                            </div>
                        </div>
                        <div class="col-6">
                            <button type="submit" onclick="return confirm('Do you really want to reset the password to the default?')" value="default" name="alterPassword" class="btn btn-info btn-block btn-flat"><i class="fas fa-undo-alt"></i>&nbsp;Restore Default Access Password</button>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <button type="submit" onclick="return confirm('Do you really want to change the password?')" value="pass" name="alterPassword" class="btn btn-info btn-block btn-flat"><i class="fas fa-edit"></i>&nbsp;Change Access Password</button>
                            </div>
                        </div>
                    </div>    
                </form>
            </div>
        </div>
    </div>
</section>
<script>
    function previewImage() {
        var previewBox = document.getElementById("preview");
        previewBox.src = URL.createObjectURL(event.target.files[0]);
    }
</script>
