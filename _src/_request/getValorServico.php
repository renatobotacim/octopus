<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 *  
 */
require_once '../_app/Config.inc.php';

function retorna($id) {
    $read = new Read();
    $read->FullRead("SELECT servico_valor from inf_servicos where servico_id = {$id}");
    return json_encode($read->getResult()[0]);
}

echo retorna(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));