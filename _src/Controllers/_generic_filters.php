<?php
$url = "";
if (isset($filtros['entries'])):
    $params['entries'] = $filtros['entries'];
    for ($index = 0; $index < sizeof(array_keys($params)); $index++):
        if (isset($params[array_keys($params)[$index]])):
            $url .= "&" . array_keys($params)[$index] . "={$params[array_keys($params)[$index]]}";
        endif;
    endfor;
else:
    $url .= "&entries={$params['entries']}";
    for ($index = 0; $index < sizeof($params); $index++):
        if (isset($filtros[array_keys($params)[$index]]) && !empty($filtros[array_keys($params)[$index]])):
            $url .= "&" . array_keys($params)[$index] . "={$filtros[array_keys($params)[$index]]}";
        endif;
    endfor;
endif;
redireciona($urlBase . $url . "&pag=1");
