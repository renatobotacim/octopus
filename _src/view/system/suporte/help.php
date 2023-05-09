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
    require_once ('../_controller/userController.class.php');
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
                    <i class="fas fa-info-circle"></i>Suporte Técnico</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label>WhatsApp</label>
                            <a href="https://web.whatsapp.com/send?l=br&phone=5528999484871" class="btn btn-info btn-block btn-flat"><i class="fab fa-whatsapp-square"></i>&nbsp;&nbsp;Iniciar conversa pelo WhatsApp WEB</a>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label>Ler QrCode</label><br>
                            <img src="dist/img/QR-SUPORTE.svg" alt="suporte" class="" width="220">
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
