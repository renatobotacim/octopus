<?php
    $Data = $_POST;
  
    require('../Controllers/ordensComprasController.class.php');
    $create = new ordensComprasController();
    $Result = $create->createItemOC($Data);
    var_dump($Result);
//    if ($uploads->getResult()):
////        $data = ['id' => $uploads->getResult()[0], 'link' => $uploads->getResult()[1], 'nome' => $uploads->getResult()[2], 'hash' => $uploads->getResult()[3]];
//        echo json_encode($data);
//    endif;
