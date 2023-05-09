<?php

require_once '../_app/Config.inc.php';

function retorna($id) {
    $read = new Read();
    $read->FullRead("SELECT * FROM inf_cidades where cidade_estado_id = :state", "state={$id}");
    return json_encode($read->getResult());
}

echo retorna(filter_input(INPUT_GET, 'id', FILTER_DEFAULT));
