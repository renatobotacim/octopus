<?php

$Data = $_POST;

require_once('../Controllers/ordensController.class.php');
$controller = new ordensController();


if (isset($_FILES['file']) && !empty($_FILES['file']['name'])):
    $Data = $_FILES['file'];
    $hash = filter_input(INPUT_GET, 'cod', FILTER_DEFAULT);
    require('../Controllers/uploadController.class.php');
    $uploads = new uploadController();
    $uploads->gravarTemp($Data, $hash);

    if ($uploads->getResult()):
        $data = ['id' => $uploads->getResult()[0], 'link' => $uploads->getResult()[1], 'nome' => $uploads->getResult()[2], 'hash' => $uploads->getResult()[3]];
        echo json_encode($data);
    else:
        $data = ['erro' => $uploads->getError()];
        echo json_encode($data);
        die(header("HTTP/1.0 404 Not Found")); //Throw an error on failure
    endif;
endif;


if (isset($_FILES['file']) && !empty($_FILES['file']['name'])):
    require('../Controllers/uploadController.class.php');
    $uploads = new uploadController();
    $uploads->gravar($_FILES['file'], $_POST);
    if ($uploads->getResult()):
        $data = ['link' => $uploads->getResult()[1]['anexo_link'], 'nome' => $uploads->getResult()[1]['anexo_nome'], 'setor' => $uploads->getResult()[1]['anexo_setor_id'], 'tipo' => ($uploads->getResult()[1]['anexo_tipo'] == 2) ? 'EXTERNO' : 'INTERNO'];
        echo json_encode($data);
    else:
        die(header("HTTP/1.0 404 Not Found")); //Throw
    endif;
endif;
