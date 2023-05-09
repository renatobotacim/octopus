<?php

/**
 * licitacoesController [ CONTROLLER ADMIN ]
 * Controlador Resposável por gerenciar os Licitações do sistema admin!
 * @copyright (c) 2021, Renato S. Botacim - Infire Soluções Digitais
 */
require_once '../_app/Config.inc.php';
require_once('../_models/AdminLicitacoes.class.php');

class licitacoesController {

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
        $read->ExeRead("inf_licitacoes", "WHERE licitacao_id = :id AND licitacao_empresa_id = :id_emp", "id={$id}&id_emp={$empresa_id}");
        return $read->getResult()[0];
    }

    public function readPagination($limit, $offset, $params, $company) {
        $busca = 'l.*, m.licitacao_modalidade_nome,s.licitacao_situacao_nome ';
        $QueryParams = $this->montaQuery($params);
        $readList = new Read;
        $readList->FullRead("select {$busca} from inf_licitacoes as l "
                . "LEFT JOIN inf_licitacoes_retificacoes as r ON l.licitacao_id = r.retificacao_licitacao_id "
                . "INNER JOIN inf_licitacoes_modalidades as m ON m.licitacao_modalidade_id = l.licitacao_modalidade_id "
                . "INNER JOIN inf_licitacoes_situacao as s ON s.licitacao_situacao_id = l.licitacao_situacao_id "
                . "where l.licitacao_empresa_id = {$company} {$QueryParams} order by l.licitacao_id DESC limit :limit offset :offset",
                "limit={$limit}&offset={$offset}");
        return $readList->getResult();
    }

    public function countByEmp($id, $params) {
        $read = new Read;
        $QueryParams = $this->montaQuery($params);
        $read->FullRead("Select licitacao_id from inf_licitacoes WHERE licitacao_empresa_id = :id {$QueryParams}", "id={$id}");
        return $read->getRowCount();
    }

    public function montaQuery($params) {
        $numero = (isset($params['numero']) ? "AND l.licitacao_numero = {$params['numero']} " : "");
        $ano = (isset($params['ano']) ? "AND l.licitacao_ano = {$params['ano']} " : "");
        $modalidade = (isset($params['modalidade']) ? "AND l.licitacao_modalidade_id = {$params['modalidade']} " : "");
        $situacao = (isset($params['situacao']) ? "AND l.licitacao_situacao_id = {$params['situacao']} " : "");
        $descricao = (isset($params['descricao']) ? "AND l.licitacao_descricao like '%{$params['descricao']}%' " : "");
        unset($params);
        return $numero . $ano . $modalidade . $situacao . $descricao;
    }

    public function create($Data) {
        $cadastra = new AdminLicitacoes();
        $cadastra->ExeCreate($Data);
        if ($cadastra->getResult()):
            redireciona("painel.php?exe=licitacoes/update&create=true&id=" . $cadastra->getResult());
        else:
            return IMDErro($cadastra->getError()[0], $cadastra->getError()[1]);
        endif;
    }

    public function updade($id, $Data) {
        $update = new AdminLicitacoes();
        $update->ExeUpdate($id, $Data);
        return IMDErro($update->getError()[0], $update->getError()[1]);
    }

    public function delete($id) {
        $deletar = new AdminLicitacoes();
        $deletar->ExeDelete($id);
        return IMDErro($deletar->getError()[0], $deletar->getError()[1]);
    }

}
