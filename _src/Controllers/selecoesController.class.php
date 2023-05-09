<?php

/**
 * jobController [ CONTROLLER ADMIN ]
 * Controlador Resposável por gerenciar os jobs do sistema admin!
 * @copyright (c) 2020, Renato S. Botacim - Infire Soluções
 */
require_once '../_app/Config.inc.php';
require_once('../_models/AdminSelecoes.class.php');

class selecoesController {

    private $rowCount;

    public function _getMetrics($company) {
        
    }

    public function _getRowCount() {
        return $this->rowCount;
    }

    public function _filters($urlBase, $filtros, $params) {
        require('_generic_filters.php');
    }

    public function read($id, $empresa_id) {
        $read = new Read;
        $read->ExeRead("inf_processo_seletivo", "WHERE processo_seletivo_id = :id AND processo_seletivo_empresa_id = :id_emp", "id={$id}&id_emp={$empresa_id}");
        return $read->getResult()[0];
    }

    public function readPagination($limit, $offset, $params, $company) {
        $busca = '*';
        $QueryParams = $this->montaQuery($params);
        $readList = new Read;
        $readList->FullRead("select {$busca} from inf_processo_seletivo as p "
                . "where p.processo_seletivo_empresa_id = {$company} {$QueryParams} order by p.processo_seletivo_id DESC limit :limit offset :offset",
                "limit={$limit}&offset={$offset}");
        return $readList->getResult();
    }

    public function countByEmp($id, $params) {
        $read = new Read;
        $QueryParams = $this->montaQuery($params);
        $read->FullRead("Select processo_seletivo_id from inf_processo_seletivo WHERE processo_seletivo_empresa_id = :id {$QueryParams}", "id={$id}");
        return $read->getRowCount();
    }

    public function montaQuery($params) {
        $mes = "";
        unset($params);
        return $mes;
    }

    public function create($Data) {
        $cadastra = new AdminSelecoes();
        $cadastra->ExeCreate($Data);
        var_dump($cadastra);
        if ($cadastra->getResult()):
            redireciona("painel.php?exe=selecoes/update&create=true&id=" . $cadastra->getResult());
        else:
            return IMDErro($cadastra->getError()[0], $cadastra->getError()[1]);
        endif;
    }


    public function updade($id, $Data) {
        $update = new AdminSelecoes();
        $update->ExeUpdate($id, $Data);
        return IMDErro($update->getError()[0], $update->getError()[1]);
    }

    public function delete($id) {
        $deletar = new AdminSelecoes();
        $deletar->ExeDelete($id);
        return IMDErro($deletar->getError()[0], $deletar->getError()[1]);
    }

}
