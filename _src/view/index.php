<?php
session_start();
require_once '../_app/Config.inc.php';

function redireciona($link) {
    if ($link == - 1) {
        echo "<script>history.go(-1);</script>";
    } else {
        echo "<script>document.location.href = '$link'</script>";
    }
}

$login = new Login(0);
if ($login->CheckLogin()):
    redireciona('painel.php');
endif;
$dataLogin = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!empty($dataLogin['AdminLogin'])):
    unset($dataLogin['AdminLogin']);
    $login->ExeLogin($dataLogin);
    if (!$login->getResult()):
        IMDErro($login->getError()[0], $login->getError()[1]);
    else:
        redireciona('painel.php?login=accept');
    endif;

    $get = filter_input(INPUT_GET, 'exe', FILTER_DEFAULT);
    if (!empty($get)):
        if ($get == 'restrito'):
            IMDErro("<h4><i class=\"fa fa-exclamation-triangle\" aria-hidden=\"true\"></i>&nbsp;&nbsp;&nbsp;<b>Atenção!</b></h4>
                Infelizmente você não tem permissão para acessar essa página.", IMD_ALERT);
        endif;
    endif;
endif;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
             <title>Octopus :: CMS System</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="A Infire Mídias Digitais busca a excelência em qualidade no desenvolvimento de sites, sistemas Web e serviços gráficos, afim de melhor atender nossos clientes.">
        <meta name="author" content="Infire Soluções Digitais">
        <link rel="icon" href="../view/dist/img/favicon.ico">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="dist/css/adminlte.min.css">
        <!-- Google Font: Source Sans Pro -->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

        <!-- jQuery -->
        <script src="plugins/jquery/jquery.min.js"></script>

        <!-- Toastr -->
        <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
            </div>
            <!-- /.login-logo -->
            <div class="card">
                <div class="card-body login-card-body">
                    <div class="login-logo">
                        <img class="img-fluid" src="../view/dist/img/logo.png" height="200" alt="Photo">
                    </div>
                    <p class="login-box-msg">Faça login para iniciar sua sessão</p>
                    <form name="AdminLoginForm" method="post">
          
                        <div class="row">
                                          <div class="input-group mb-2">
                            <input type="text" name="user" class="form-control form-control-sm" placeholder="Email">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-2">
                            <input type="password" name="pass" class="form-control  form-control-sm" placeholder="Password">
                            <div class="input-group-append ">
                                <div class="input-group-text ">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                            <!--                            <div class="col-8">
                                                            <div class="icheck-primary">
                                                                <input type="checkbox" id="remember">
                                                                <label for="remember">
                                                                    Remember Me
                                                                </label>
                                                            </div>
                                                        </div>-->
                            <!-- /.col -->
                                <button type="submit" name="AdminLogin" value="Logar"  class="btn btn-primary btn-block btn-sm">Logar</button>
                            <!-- /.col -->
                        </div>
                    </form>
                    <hr>
<!--                    <div class="social-auth-links text-center mb-3">
                        <a href="#" class="btn btn-block btn-primary">
                            <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
                        </a>
                        <a href="#" class="btn btn-block btn-danger">
                            <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
                        </a>
                    </div>-->
                    <!-- /.social-auth-links -->

                    <p class="mb-1">
                        <a href="forgot-password.html">Recuperar Senha</a>
                    </p>
<!--                    <p class="mb-0">
                        <a href="register.html" class="text-center">Register a new membership</a>
                    </p>-->
                </div>
                <!-- /.login-card-body -->
            </div>
        </div>
        <!-- /.login-box -->

        <!-- Bootstrap 4 -->
        <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- AdminLTE App -->
        <script src="dist/js/adminlte.min.js"></script>

    </body>
</html>

