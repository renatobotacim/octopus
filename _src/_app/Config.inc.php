<?php

define('HOME', 'http:localhost/projetos/octopus/_src/');
define('LINK', 'localhost/projetos/octopus/_src/uploads');

//CONFIGURAÇÃO DO SITE ############################

define('HOST', 'localhost');
define('USER', 'root');
define('PASS', '');
define('DBSA', 'u774867044_octopus');

define('MAILUSER', 'sistema@infire.com.br');
define('MAILPASS', '*PS7D4@tWl');
define('MAILPORT', '587');
define('MAILHOST', 'smtp.hostinger.com.br');

//AUTO LOAD DE CLASSES ############################

function autoload_defaut($Class) {

    $cDir = ['Conn', 'Class', 'Helpers', 'Models', 'Library'];
    $iDir = null;

    foreach ($cDir as $dirName):
        if (!$iDir && file_exists(__DIR__ . "//{$dirName}//{$Class}.class.php") && !is_dir(__DIR__ . "//{$dirName}//{$Class}.class.php")):
            include_once (__DIR__ . "//{$dirName}//{$Class}.class.php");
            $iDir = true;
        endif;
    endforeach;

    if (!$iDir):
        IMDErro("<b><i class=\"fa fa-times\" aria-hidden=\"true\"></i><br>Erro Fatal!</b><br>Não foi possível incluir {$Class}.class.php!", IMD_ERROR);
//        trigger_error("Não foi possível incluir {$Class}.class.php", E_USER_ERROR);
        die;
    endif;
}

spl_autoload_register("autoload_defaut");

//TRATAMENTO DE ERROS #############################
//CSS contantes :: Mensagens de erro

define('IMD_ACCEPT', 'success');
define('IMD_INFOR', 'info');
define('IMD_ALERT', 'warning');
define('IMD_ERROR', 'error');

// WSErro :: Exibe erros lançados :: Front
function IMDErro($ErrMsg, $ErrNo, $ErrDie = null) {
    $CssClass = ($ErrNo == E_USER_NOTICE ? IMD_INFOR : ($ErrNo == E_USER_WARNING ? IMD_ALERT : ($ErrNo == E_USER_ERROR ? IMD_ERROR : $ErrNo)));
    echo "<script>$(document).ready(function(){toastr.{$CssClass}('{$ErrMsg}')});</script>";
    if ($ErrDie):
        die;
    endif;
}

//PHPErro :: personaliza o gatilho do PHP
function PHPErro($ErrNo, $ErrMsg, $ErrFile, $ErrLine) {
    $CssClass = ($ErrNo == E_USER_NOTICE ? IMD_INFOR : ($ErrNo == E_USER_WARNING ? IMD_ALERT : ($ErrNo == E_USER_ERROR ? IMD_ERROR : $ErrNo)));
    echo"<div id=\"popup\" class=\"{$CssClass}\">";
    echo"<b>Erro na linha: {$ErrLine}::</b> {$ErrMsg}<br>";
    echo"<small>{$ErrFile}</small>";
    echo"<span class=\"ajax_close\"></span></p>";

    if ($ErrNo == E_USER_ERROR):
        die;
    endif;
}

function cript($srt){
    $pw = hash("sha256", '$6$rounds=5000$_ usesomesillystringforsalt$'.$srt);
    return $pw;
}

function dateFormat($date) {
    if (!empty($date)):
        $dateF = new DateTime($date);
        return $dateF->format('d/m/Y');
    else:
        return '';
    endif;
}

function dateFormatFull($date) {
    if (!empty($date)):
        $dateF = new DateTime($date);
        return $dateF->format('d/m/Y H:i:s');
    else:
        return '';
    endif;
}

set_error_handler('PHPErro');