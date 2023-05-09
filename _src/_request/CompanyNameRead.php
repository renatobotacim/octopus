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
    $read->FullRead("SELECT id_company, company_name FROM " . SCHEMA . "company where id_company = {$id}");
    return json_encode($read->getResult());
}

echo retorna(filter_input(INPUT_GET, 'id', FILTER_DEFAULT));
