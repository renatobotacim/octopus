<!DOCTYPE html>
<html lang="pt-br">
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

        <!-- Tempusdominus Bbootstrap 4 -->
        <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
        <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">

        <!-- iCheck -->
        <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">

        <!-- JQVMap -->
        <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">

        <!-- Theme style -->
        <link rel="stylesheet" href="dist/css/adminlte.min.css">

        <!-- overlayScrollbars -->
        <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">

        <!-- Daterange picker -->
        <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">

        <!-- Google Font: Source Sans Pro -->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

        <!-- jQuery -->
        <script src="plugins/jquery/jquery.min.js"></script>

        <!-- DataTables -->
        <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">

        <!-- Toastr -->
        <link rel="stylesheet" href="plugins/toastr/toastr.min.css">

        <!-- Select2 -->
        <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
        <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

        <!-- daterange picker -->
        <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">

        <!-- daterange picker -->
        <link rel="stylesheet" href="plugins/bootstrap-fileinput/css/fileinput.min.css">

        <!-- summernote -->
        <link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">


    </head>

    <style>
        .alert {width: 300px; z-index: 1000!important; position: absolute; right: 25px}

        .example-modal .modal {
            position: relative;
            top: auto;
            bottom: auto;
            right: auto;
            left: auto;
            display: block;
            z-index: 1;
        }

        .example-modal .modal {
            background: transparent !important;
        }

        .logo{
            overflow: hidden;
            text-align: center;
        }

        .logo img{
            max-height: 100px;
            max-width: 90%;
            margin: 0px auto;
        }

        .nav-header {
            padding: 5px!important;
        }

        .inputfile {
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            position: absolute;
            z-index: -1;
        }
        .uploadArquivo {text-align: center;cursor: pointer;}
        .uploadArquivo .mudarfoto {
            position: absolute;
            font-size: 1.5em;
            top: 50%;
            left: 40%;
            transform: translateY(-50%);
            display: none;
        }
        .inputfile + label:hover, .inputfile + label img{opacity: 0.9}

        .btn-action{width: 40px; margin-right: 7px}

    </style>
    <script>
        window.onload = function () {
            document.getElementById("closefull").style.display = "none";
        };
        var elem = document.documentElement;
        function openFullscreen() {
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.webkitRequestFullscreen) { /* Safari */
                elem.webkitRequestFullscreen();
            } else if (elem.msRequestFullscreen) { /* IE11 */
                elem.msRequestFullscreen();
            }
            document.getElementById('closefull').style.display = 'block';
            document.getElementById('openfull').style.display = 'none';
        }
        function closeFullscreen() {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) { /* Safari */
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) { /* IE11 */
                document.msExitFullscreen();
            }
            document.getElementById('closefull').style.display = 'none';
            document.getElementById('openfull').style.display = 'block';
        }
    </script>
    <?php
    require_once '../_app/Config.inc.php';
    //VERIFICA A EXISTÊNCIA DE UM LOGIM, CASO SIM, ELE SEMPRE DIRECIONA PARA O PAINEL, CASO NÃO, DIRECIONA A TELA DE LOGIN

    $login = new Login(0);
    if (!$login->CheckLogin()):
        unset($_SESSION['userlogin']);
        redireciona('index.php?exe=restrito'); // chama a função
    else:
        $userlogin = $_SESSION['userlogin'];
    endif;

    // VERIFICA SE FOI REALIZADO O LOGOFF DO SISTEMA PARA PODER VOLTAR A TELA DE LOGIN
    $logoff = filter_input(INPUT_GET, 'logoff', FILTER_VALIDATE_BOOLEAN);
    if ($logoff):
        unset($_SESSION['userlogin']);
        session_destroy();
        redireciona('index.php?exe=logoff'); // chama a função
    endif;

    // FUNÇÃO RESPONSÁVEL POR CORRIGIR O HEADER LOCATION QUE APRESENTA ERROR
    function redireciona($link) {
        if ($link == - 1) {
            echo"<script>history.go(-1);</script>";
        } else {
            echo"<script>document.location.href = '$link'</script>";
        }
    }

    //USO DO FILTRO PARA PODER FAZER A NAVEGAÇÃO ENTRE AS PÁGINAS
    $getexe = filter_input(INPUT_GET, 'exe', FILTER_DEFAULT);

    if($getexe){
      $exe = explode("/", filter_input(INPUT_GET, 'exe', FILTER_DEFAULT));
    }else{
      $exe[0] = 'dashboard';
      $exe[1] = '';
    }

    ?>

    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">

            <!-- Navbar -->
            <nav class="main-header navbar navbar-expand navbar-white navbar-light">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                    </li>
                    <!--                    <li class="nav-item">
                                            <a class="nav-link" id="openfull" onclick="openFullscreen();" role="button"><i class="fas fa-expand"></i></a>
                                            <a class="nav-link"  id="closefull" onclick="closeFullscreen();" role="button"><i class="fas fa-compress"></i></a>
                                        </li>-->
                    <li class="nav-item d-none d-sm-inline-block">
                        <a href="painel.php"  class="nav-link">Dashboard</a>
                    </li>
                    <!--                    <li class="nav-item d-none d-sm-inline-block">
                                            <a href="#" class="nav-link">Contatc</a>
                                        </li>-->
                </ul>
                <!-- Right navbar links -->
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="painel.php?exe=usuarios/update&id=<?= $userlogin['usuario_id'] ?>">
                            <i class="fas fa-user"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"  href="painel.php?logoff=true">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- /.navbar -->
            <!-- Main Sidebar Container -->

            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <!-- Brand Logo -->
                <div class="brand-link" style="padding: 0px!important;margin: 0px!important">
<!--                    <img src="dist/img/123.png" alt="logo" class="img-circle" style="width:33px;height:33px;">
                    -->

                    <div class="logo py-4">
                        <img src="dist/img/octopus.png" alt="logo octopus">
                    </div>
                </div>
                <!-- Sidebar -->
                <div class="sidebar pt-3 mb-5" >
                    <!-- Sidebar user panel (optional) -->

                    <!-- Sidebar Menu -->
                    <nav class="mt-2 mb-5">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                            <li class="nav-item">
                                <a href="painel.php" class="nav-link <?php echo $exe[0] == "dashboard" ? "active" : '' ?>">
                                    <i class="nav-icon fas fa-tachometer-alt"></i><p>Dashboard</p>
                                </a>
                            </li>

                            <?php
                            $readMenu = new Read;
                            $readMenu->FullRead("select m.modulo_nome, me.* from inf_modulos m
                INNER JOIN inf_planos_modulos as pm on  m.modulo_id = pm.modulo_id
                INNER JOIN inf_menus as me on me.menu_modulo_id = m.modulo_id
                INNER JOIN inf_menus_usuarios as mu on mu.menu_id = me.menu_id
                INNER JOIN inf_empresas as e on e.empresa_plano_id = pm.plano_id
                where e.empresa_id = {$userlogin['usuario_empresa_id']} AND mu.usuario_id = {$userlogin['usuario_id']} order by m.modulo_ordem ASC, me.menu_permissao desc
                ");

                            $rotulo = null;
                            $active = null;
                            foreach ($readMenu->getResult() as $menu_modulo_id):
                                extract($menu_modulo_id);
                                if ($exe[0] == $menu_link && $exe[1] == $menu_endpoint):
                                    $active = "active";
                                else:
                                    $active = null;
                                endif;

                                if ($modulo_nome != $rotulo):
                                    $rotulo = $modulo_nome;
                                    echo "<li class=\"nav-header mt-3 mb-1\" style=\"background-color:rgba(0,0,0,0.2)\">Módulo {$rotulo}</li>";
                                endif;

                                if (isset($menu_endpoint)):
                                    $menu_link = "{$menu_link}/{$menu_endpoint}";
                                else:
                                    $menu_link = "{$menu_link}/index";
                                endif;

                                if ($menu_permissao <= $userlogin['usuario_permissao']):
                                    echo "<li class=\"nav-item mb-1\" id=\"{$menu_link}\">"
                                    . "<a href=\"painel.php?exe={$menu_link}\" class=\"nav-link {$active}\" style=\"padding: 4px 0px 4px 15px;\" >"
                                    . "<i class=\"nav-icon {$menu_icone}\"></i><p>{$menu_nome}</p>"
                                    . "</a></li>";
                                endif;
                            endforeach;
                            ?>
                        </ul>
                      <ul>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                      </ul>
                    </nav>
                </div>
            </aside>
