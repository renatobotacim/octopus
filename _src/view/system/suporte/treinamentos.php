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
                <i class="fas fa-graduation-cap"></i>&nbsp;Treinamentos</h3>
            </div>
            <div class="card-body">
              <label>Lista de vídeos sobre a nossa ferramenta!</label>
              <div class="row">
                <div class="col-3">
                  <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link active" id="v-pills-apresentacao-tab" data-toggle="pill" data-target="#v-pills-apresentacao" type="button" role="tab" aria-controls="v-pills-apresentacao" aria-selected="true">Apresentação</button>
                    <button class="nav-link" id="v-pills-modulo-base-tab" data-toggle="pill" data-target="#v-pills-modulo-base" type="button" role="tab" aria-controls="v-pills-modulo-base" aria-selected="false">Módulo Base</button>
                    <button class="nav-link" id="v-pills-modulo-financeiro-tab" data-toggle="pill" data-target="#v-pills-modulo-financeiro" type="button" role="tab" aria-controls="v-pills-modulo-financeiro" aria-selected="false">Módulo Financeiro</button>
                    <button class="nav-link" id="v-pills-modulo-protocolos-tab" data-toggle="pill" data-target="#v-pills-modulo-protocolos" type="button" role="tab" aria-controls="v-pills-modulo-protocolos" aria-selected="false">Módulo Protocolos</button>
                  </div>
                </div>
                <div class="col-9">
                  <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="v-pills-apresentacao" role="tabpanel" aria-labelledby="v-pills-apresentacao-tab">
                      <iframe width="100%" height="600px" src="https://www.youtube.com/embed/yDogcodnwAU" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    <div class="tab-pane fade" id="v-pills-modulo-base" role="tabpanel" aria-labelledby="v-pills-modulo-base-tab">

                      <iframe width="560" height="315" src="https://www.youtube.com/embed/kx3SXjwUcrw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                      <iframe width="560" height="315" src="https://www.youtube.com/embed/hNmWmuCagw8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                      <iframe width="560" height="315" src="https://www.youtube.com/embed/NTNvqDcei_o" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                      <iframe width="560" height="315" src="https://www.youtube.com/embed/uZNBkPxNIIw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

                    </div>
                    <div class="tab-pane fade" id="v-pills-modulo-financeiro" role="tabpanel" aria-labelledby="v-pills-modulo-financeiro-tab">
                      <iframe width="100%" height="600px" src="https://www.youtube.com/embed/cQiU6OELVu4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    <div class="tab-pane fade" id="v-pills-modulo-protocolos" role="tabpanel" aria-labelledby="v-pills-modulo-protocolos-tab">
                      <iframe width="100%" height="600px" src="https://www.youtube.com/embed/3Xxltb0msMQ" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
</section>
