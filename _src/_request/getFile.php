<?php

require_once '../_app/Config.inc.php';

function retorna($id) {
    $read = new Read();
    $read->FullRead("SELECT * from inf_arquivos where arquivo_id = {$id}");
    return json_encode($read->getResult());
}

echo retorna(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));
