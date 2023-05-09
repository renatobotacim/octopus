<?php

if (isset($_FILES['file']) && !empty($_FILES['file']['name'])):
   
    require('../Controllers/uploadController.class.php');
    $uploads = new uploadController();
    $uploads->gravarImgDestaque($_FILES['file'], $_POST);
    
    if ($uploads->getResult()):
        $data = ['id' => $uploads->getResult()[0], 'link' => $uploads->getResult()[1], 'nome' => $uploads->getResult()[2],'prioridade'=>$uploads->getResult()[3]];
        echo json_encode($data);
    else:
        $data = ['erro' => $uploads->getError()];
        echo json_encode($data);
        die(header("HTTP/1.0 404 Not Found")); //Throw an error on failure
    endif;
endif;
