<?php

/**
 * licitacoesController [ CONTROLLER ADMIN ]
 * Controlador Resposável por gerenciar os Licitações do sistema admin!
 * @copyright (c) 2021, Renato S. Botacim - Infire Soluções Digitais
 */
require_once '../_app/Config.inc.php';
require_once('../_models/AdminAgendas.class.php');

class agendasController {

    private $rowCount;

    public function _getMetrics($company) {    
    }

    public function getCategorias($idEmp) {
        return $this->readCategorias($idEmp);
    }

    public function _getRowCount() {
        return $this->rowCount;
    }

    public function _filters($urlBase, $filtros, $params) {
        require('_generic_filters.php');
    }

    public function read($id, $empresa_id) {
        $read = new Read;
        $read->FullRead("Select * from inf_agendas as a "
                . "left join inf_categorias as c ON c.categoria_id = a.agenda_categoria_id "
                . "WHERE agenda_id = :id AND c.categoria_empresa_id = :id_emp", "id={$id}&id_emp={$empresa_id}");
        return $read->getResult()[0];
    }

    public function readPagination($limit, $offset, $params, $company) {
        $busca = '*';
        $QueryParams = $this->montaQuery($params);
        $readList = new Read;
        $readList->FullRead("select {$busca} from inf_agendas as a "
                . "left join inf_categorias as c ON c.categoria_id = a.agenda_categoria_id "
                . "where c.categoria_empresa_id = {$company} {$QueryParams} order by a.agenda_data DESC limit :limit offset :offset",
                "limit={$limit}&offset={$offset}");
        return $readList->getResult();
    }

    public function countByEmp($id, $params) {
        $read = new Read;
        $QueryParams = $this->montaQuery($params);
        $read->FullRead("Select agenda_id from inf_agendas as a "
                . "left join inf_categorias as c ON c.categoria_id = a.agenda_categoria_id "
                . "WHERE c.categoria_empresa_id = :id {$QueryParams}", "id={$id}");
        return $read->getRowCount();
    }

    public function montaQuery($params) {
        $mes = "";
        unset($params);
        return $mes;
    }

    public function create($Data) {
        $cadastra = new AdminAgendas();
        $cadastra->ExeCreate($Data);
        if ($cadastra->getResult()):
            redireciona("painel.php?exe=agendas/update&create=true&id=" . $cadastra->getResult());
        else:
            return IMDErro($cadastra->getError()[0], $cadastra->getError()[1]);
        endif;
    }

    public function updade($id, $Data, $empID) {
        $update = new AdminAgendas();
        $update->ExeUpdate($id, $Data);
        if ($update->getResult()):
            return true;
        else:
            return IMDErro($update->getError()[0], $update->getError()[1]);
        endif;
    }

    public function delete($id) {
        $deletar = new AdminAgendas();
        $deletar->ExeDelete($id);
        return IMDErro($deletar->getError()[0], $deletar->getError()[1]);
    }

    /**
     * ********************************************************
     * PRIVATE METHODS
     * ********************************************************
     */
    function readCategorias($idEmp) {
        $read = new Read;
        $read->ExeRead("inf_categorias", "WHERE categoria_empresa_id = :idEmp", "idEmp={$idEmp}");
        return $read->getResult();
    }

}
