<?php

require_once('../Controllers/ordensComprasController.class.php');
$controller = new ordensComprasController();
$controller->deleteItemOC($_POST['id']);
if ($controller->_getResult()):
    $data = true;
    echo json_encode($data);
else:
    die(header("HTTP/1.0 404 Not Found"));
endif;







