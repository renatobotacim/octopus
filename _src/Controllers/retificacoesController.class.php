<?php

/**
 * licitacoesController [ CONTROLLER ADMIN ]
 * Controlador Resposável por gerenciar os Licitações do sistema admin!
 * @copyright (c) 2021, Renato S. Botacim - Infire Soluções Digitais
 */
require_once '../_app/Config.inc.php';
require_once('../_models/AdminRetificacoes.class.php');

class retificacoesController {

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
        $read->FullRead("Select r.* from inf_licitacoes_retificacoes as r INNER JOIN inf_licitacoes as l ON l.licitacao_id = r.retificacao_licitacao_id WHERE r.retificacao_id = :id AND licitacao_empresa_id = :id_emp", "id={$id}&id_emp={$empresa_id}");
        return $read->getResult()[0];
    }

    public function readPagination($limit, $offset, $params, $company) {
        $busca = 'r.*, l.licitacao_empresa_id, l.licitacao_ano, l.licitacao_numero ';
        $QueryParams = $this->montaQuery($params);
        $readList = new Read;
        $readList->FullRead("select {$busca} from inf_licitacoes_retificacoes as r "
        . "INNER JOIN inf_licitacoes as l ON l.licitacao_id = r.retificacao_licitacao_id "
                . "where l.licitacao_empresa_id = {$company} {$QueryParams} order by r.retificacao_id DESC limit :limit offset :offset",
                "limit={$limit}&offset={$offset}");
        return $readList->getResult();
    }

    public function countByEmp($id, $params) {
        $read = new Read;
        $QueryParams = $this->montaQuery($params);
        $read->FullRead("Select r.retificacao_id from inf_licitacoes_retificacoes as r INNER JOIN inf_licitacoes as l ON l.licitacao_id = r.retificacao_licitacao_id WHERE l.licitacao_empresa_id = :id {$QueryParams}", "id={$id}");
        return $read->getRowCount();
    }

    public function montaQuery($params) {
        $numero = (isset($params['numero']) ? "AND l.licitacao_numero = {$params['numero']} " : "");
        $ano = (isset($params['ano']) ? "AND l.licitacao_ano = {$params['ano']} " : "");
        $descricao = (isset($params['descricao']) ? "AND r.retificacao_descricao like '%{$params['descricao']}%' " : "");
        unset($params);
        return $numero . $ano . $descricao;
    }

    public function create($Data) {
        $cadastra = new AdminRetificacoes();
        $cadastra->ExeCreate($Data);
        if ($cadastra->getResult()):
            redireciona("painel.php?exe=retificacoes/update&create=true&id=" . $cadastra->getResult());
        else:
            return IMDErro($cadastra->getError()[0], $cadastra->getError()[1]);
        endif;
    }

    public function updade($id, $Data) {
        $update = new AdminRetificacoes();
        $update->ExeUpdate($id, $Data);
        if ($update->getResult()):
            redireciona("painel.php?exe=retificacoes/update&id=" . $id);
        else:
            return IMDErro($update->getError()[0], $update->getError()[1]);
        endif;
    }

    public function delete($id) {
        $deletar = new AdminRetificacoes();
        $deletar->ExeDelete($id);
        return IMDErro($deletar->getError()[0], $deletar->getError()[1]);
    }

}
