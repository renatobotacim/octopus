<?php
//VERIFICA A EXISTÊNCIA DE UM LOGIM, CASO SIM, ELE SEMPRE DIRECIONA PARA O PAINEL, CASO NÃO, DIRECIONA A TELA DE LOGIN
$checkLevel = new Login(2);
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
    require_once ('../Controllers/userController.class.php');
    $controller = new userController();
    if ($userlogin['user_level'] > 1):
        $Data['company'] = $userlogin['id_company'];
    endif;
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
                        <div class="col-4">
                            <div class="form-group">
                                <label>Name</label>
                                <input class="form-control" type="text" name="user_name" id="user_name" value="<?php if (isset($Data)) echo $Data['user_name'] ?>">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
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
                        if ($userlogin['user_level'] == 1):
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

                            <script type='text/javascript'>
                                $(document).ready(function () {
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
                                    <select class="form-control"  id="company" name="company">
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
                    <div class="row">
                        <div class="col-3">
                            <a href="painel.php?exe=<?= $navegacao[0] ?>/index" class="btn btn-danger btn-block btn-flat"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Cancel</a>
                        </div>
                        <div class="col-3">
                            <button type="reset" value="clear" name="clear" class="btn btn-warning btn-block btn-flat"><i class="fas fa-eraser"></i>&nbsp;Clear Forms</button>
                        </div>
                        <div class="col-3">
                            <a href="painel.php?exe=<?= $navegacao[0] ?>/create" class="btn btn-info btn-block btn-flat"><i class="fa fa-plus"></i>&nbsp;&nbsp;New</a>
                        </div>
                        <div class="col-3">
                            <button type="submit" value="Cadastrar" name="sendForm" class="btn btn-success btn-block btn-flat"><i class="fas fa-save"></i>&nbsp;Save</button>
                        </div>
                    </div>
            </form>
        </div>
    </div>
</section>
