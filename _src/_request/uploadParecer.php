<?php

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
