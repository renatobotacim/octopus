<?php

/**
 * jobController [ CONTROLLER ADMIN ]
 * Controlador Resposável por gerenciar os jobs do sistema admin!
 * @copyright (c) 2020, Renato S. Botacim - Infire Soluções
 */
require_once '../_app/Config.inc.php';
require_once('../_models/AdminConteudos.class.php');

class conteudosController {

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
        $read->ExeRead("inf_conteudos", "WHERE conteudo_id = :id AND conteudo_empresa_id = :id_emp", "id={$id}&id_emp={$empresa_id}");
        return $read->getResult()[0];
    }

    public function readPagination($limit, $offset, $params, $company) {
        $busca = '*';
        $QueryParams = $this->montaQuery($params);
        $readList = new Read;
        $readList->FullRead("select {$busca} from inf_conteudos as c "
                . "where c.conteudo_empresa_id = {$company} {$QueryParams} limit :limit offset :offset",
                "limit={$limit}&offset={$offset}");
        return $readList->getResult();
    }

    public function countByEmp($id, $params) {
        $read = new Read;
        $QueryParams = $this->montaQuery($params);
        $read->FullRead("Select * from inf_conteudos WHERE conteudo_empresa_id = :id {$QueryParams}", "id={$id}");
        return $read->getRowCount();
    }

    public function montaQuery($params) {
        $titulo = (isset($params['titulo']) ? "AND conteudo_titulo like '%{$params['titulo']}%' " : "");
        $pai = (isset($params['pai']) ? "AND conteudo_pai = {$params['pai']}" : "");
        unset($params);
        return $titulo . $pai;
    }

    public function create($Data) {
        $cadastra = new AdminConteudos();
        $cadastra->ExeCreate($Data);
        if ($cadastra->getResult()):
            redireciona("painel.php?exe=contents/update&create=true&id=" . $cadastra->getResult());
        else:
            return IMDErro($cadastra->getError()[0], $cadastra->getError()[1]);
        endif;
    }

    public function setUpload($Data) {
        require_once('../Controllers/uploadController.class.php');
        $uploads = new uploadController();
        $result = $uploads->converteImg($Data['logo']);
        if (!$result):
            return IMDErro('<b>Sorry</b><br>There was an error making the logo change, try again!', IMD_ALERT);
        else:
            return $result;
            unset($Data['logo']);
        endif;
    }

    public function updade($id, $Data) {
        $update = new AdminConteudos();
        $update->ExeUpdate($id, $Data);
        return IMDErro($update->getError()[0], $update->getError()[1]);
    }

    public function delete($id) {
        $deletar = new AdminConteudos();
        $deletar->ExeDelete($id);
        return IMDErro($deletar->getError()[0], $deletar->getError()[1]);
    }

}
