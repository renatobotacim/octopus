<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 *  
 */
require_once '../_app/Config.inc.php';

function retorna() {
    $read = new Read();
    $read->FullRead("SELECT * from inf_imagens where imagem_destaque = 2");
    return json_encode($read->getResult());
}

echo retorna();
