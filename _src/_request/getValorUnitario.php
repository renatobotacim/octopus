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
    $read->FullRead("SELECT p.produto_valor_venda, (e.estoque_quantidade - e.estoque_uso) as max FROM inf_produtos as p INNER JOIN inf_estoques as e ON e.estoque_produto_id = p.produto_id where produto_id = {$id}");
    return json_encode($read->getResult()[0]);
}

echo retorna(filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT));