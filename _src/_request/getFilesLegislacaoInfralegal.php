<?php

require_once '../_app/Config.inc.php';

function retorna($id) {
    $read = new Read();
    $read->FullRead("SELECT link from inf_arquivos where id_{$a} = {$id}");
    return json_encode($read->getResult());
}

echo retorna(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));
