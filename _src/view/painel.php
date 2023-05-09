<?php
session_start();
require('includes/header.php');
//VERIFICA SE FOI INICIADO UM NOVO LOGIN E EXIBE A MENSAGEM DE LOGIN DO SISTEMA

//VERIFICA SE O USUÁRIO TENTOU ENTRAR EM UM CATEGORIA QUE NÃO TEM ACESSO
$getCheckLevel = filter_input(INPUT_GET, 'restrito', FILTER_VALIDATE_BOOLEAN);
if (!empty($getCheckLevel)):
    if ($getCheckLevel):
        IMDErro("<b>Sorry!</b><br>unfortunately you are not allowed to access this page! <br> Thanks for understanding.", IMD_ERROR);
    endif;
endif;
echo "<div class=\"content-wrapper px-2\">";
//var_dump($userlogin);
    if (!empty($getexe)):
        $includepatch = __DIR__ . DIRECTORY_SEPARATOR.'system'.DIRECTORY_SEPARATOR . strip_tags(trim($getexe) . '.php');
    else:
        $includepatch = __DIR__ . DIRECTORY_SEPARATOR.'system'.DIRECTORY_SEPARATOR.'home.php';
    endif;
    if (file_exists($includepatch)):
        require_once($includepatch);
    else:
        require_once(__DIR__ . DIRECTORY_SEPARATOR.'system'.DIRECTORY_SEPARATOR.'500.php');
        IMDErro("<b>OPSSS: System Erro</b><br>The {$getexe}.php page is not defined in the controller! <br> Contact your system administrator", IMD_ERROR);
    endif;
echo "</div>";

require('includes/footer.php');
