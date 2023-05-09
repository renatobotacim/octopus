<?php

/**
 * licitacoesController [ CONTROLLER ADMIN ]
 * Controlador Resposável por gerenciar os Licitações do sistema admin!
 * @copyright (c) 2021, Renato S. Botacim - Infire Soluções Digitais
 */
require_once '../_app/Config.inc.php';
require_once('../_models/AdminLegislacoes.class.php');

class legislacaoController {

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
        $read->ExeRead("inf_legislacao_infralegal", "WHERE infralegal_id = :id AND infralegal_empresa_id = :id_emp", "id={$id}&id_emp={$empresa_id}");
        return $read->getResult()[0];
    }

    public function readPagination($limit, $offset, $params, $company) {
        $busca = '*';
        $QueryParams = $this->montaQuery($params);
        $readList = new Read;
        $readList->FullRead("select {$busca} from inf_legislacao_infralegal as l "
                . "INNER JOIN inf_legislacao_infralegal_tipos as lit ON lit.infralegal_tipo_id = l.infralegal_tipo "
                . "left JOIN inf_legislacao_sistema as ls ON ls.legislacao_sistema_id = l.infralegal_legislacao_sistema "
                . "where l.infralegal_empresa_id = {$company} {$QueryParams} ORDER BY l.infralegal_ano desc limit :limit offset :offset",
                "limit={$limit}&offset={$offset}");
        return $readList->getResult();
    }

    public function countByEmp($id, $params) {
        $read = new Read;
        $QueryParams = $this->montaQuery($params);
        $read->FullRead("Select infralegal_id from inf_legislacao_infralegal WHERE infralegal_empresa_id = :id {$QueryParams}", "id={$id}");
        return $read->getRowCount();
    }

    public function montaQuery($params) {
        $mes = "";
        unset($params);
        return $mes;
    }

    public function create($Data) {
        if (isset($Data['file'])):
            require('../Controllers/uploadController.class.php');
            $uploads = new uploadController();
            $uploads->uploadFileGenerico($Data['file'], "/{$Data['infralegal_empresa_id']}/files/legislacaoInfralegal");
            unset($Data['file']);
            $Data['infralegal_link'] = 'http://vendanova.es.gov.br/cms/_src/uploads' . $uploads->getResult();
        endif;
        $cadastra = new AdminLegislacoes();
        $cadastra->ExeCreate($Data);
        if ($cadastra->getResult()):
            redireciona("painel.php?exe=legislacao/update&create=true&id=" . $cadastra->getResult());
        else:
            return IMDErro($cadastra->getError()[0], $cadastra->getError()[1]);
        endif;
    }

    public function updade($id, $Data, $empID) {
        if (isset($Data['file'])):
            require('../Controllers/uploadController.class.php');
            $uploads = new uploadController();
            $uploads->uploadFileGenerico($Data['file'], "/{$empID}/files/legislacaoInfralegal");
            unset($Data['file']);
            $Data['infralegal_link'] = 'http://sistemas.vendanova.es.gov.br/cms/_src/uploads' . $uploads->getResult();
        endif;
        $update = new AdminLegislacoes();
        $update->ExeUpdate($id, $Data);
        if ($update->getResult()):
            redireciona("painel.php?exe=legislacao/update&id=" . $id);
        else:
            return IMDErro($update->getError()[0], $update->getError()[1]);
        endif;
    }

    public function delete($id) {
        $deletar = new AdminLegislacoes();
        $deletar->ExeDelete($id);
        return IMDErro($deletar->getError()[0], $deletar->getError()[1]);
    }

}
