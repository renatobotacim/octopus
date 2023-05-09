<?php

if (isset($_POST)):
    $id = $_POST['id'];
    unset($_POST['id']);
    require_once('../Controllers/arquivosController.class.php');
    $controller = new arquivosController();

    $resulct = $controller->updade($id, $_POST);
    if ($resulct):
        $data = ['status' => 201, 'message' => 'Arquivo atualizado com sucesso.'];
        echo json_encode($data);
    else:
        $data = ['type' => 400, 'message' => 'Verifique os dados referentes ao arquivo e tente novamente.'];
        echo json_encode($data);
    endif;
endif;
