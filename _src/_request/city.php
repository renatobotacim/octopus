<?php

require_once '../_app/Config.inc.php';

function retorna($id) {
    $read = new Read();
    $read->FullRead("SELECT * FROM inf_cidades c INNER JOIN inf_estados as e on c.cidade_estado_id = e.estado_id where cidade_id = :cidade", "cidade={$id}");
    return json_encode($read->getResult());
}

echo retorna(filter_input(INPUT_GET, 'id', FILTER_DEFAULT));
