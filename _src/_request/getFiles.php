<?php

require_once '../_app/Config.inc.php';

function retorna($id) {
    $a = filter_input(INPUT_GET, 'type', FILTER_DEFAULT);
    $read = new Read();
    $read->FullRead("SELECT * from inf_arquivos where id_{$a} = {$id} order by arquivo_ordem");
    return json_encode($read->getResult());
}

echo retorna(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));
