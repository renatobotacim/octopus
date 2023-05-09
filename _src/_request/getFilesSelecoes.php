<?php

require_once '../_app/Config.inc.php';

function retorna($id, $i) {
    $a = array('id_processo_seletivo_concurso_edital_abertura', 'id_processo_seletivo_concurso_edital_outros', 'id_processo_seletivo_concurso_edital_resultado', 'id_processo_seletivo_concurso_edital_convocacao');
    if (isset($i)):
        $read = new Read();
        $read->FullRead("SELECT * from inf_arquivos where {$a[$i]} = {$id}");
        return json_encode($read->getResult());
    else:
        $data = ['erro' => "Não foi possível localizar arquivos dessa categoria"];
        echo json_encode($data);
        die(header("HTTP/1.0 404 Not Found")); //Throw an error on failure            
    endif;
}

echo retorna(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT), filter_input(INPUT_GET, 'type', FILTER_VALIDATE_INT));
