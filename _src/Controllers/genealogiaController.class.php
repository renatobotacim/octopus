<?php

/**
 * licitacoesController [ CONTROLLER ADMIN ]
 * Controlador Resposável por gerenciar os Licitações do sistema admin!
 * @copyright (c) 2021, Renato S. Botacim - Infire Soluções Digitais
 */
require_once '../_app/Config.inc.php';
require_once('../_models/AdminGenealogias.class.php');
require_once('../_models/AdminArquivos.class.php');

class genealogiaController
{

    private $rowCount;

    public function _getMetrics($company)
    {

    }

    public function _getRowCount()
    {
        return $this->rowCount;
    }

    public function _filters($urlBase, $filtros, $params)
    {
        require('_generic_filters.php');
    }

    public function read($id, $empresa_id)
    {
        $read = new Read;
        $read->ExeRead("inf_genealogias", "WHERE genealogia_id = :id AND genealogia_empresa_id = :id_emp", "id={$id}&id_emp={$empresa_id}");
        $read->FullRead("Select g.*, i.imagem_host, i.imagem_link from inf_genealogias as g 
                                         left JOIN inf_imagens as i ON i.imagem_id = g.path_foto 
                                         WHERE genealogia_id = :id AND genealogia_empresa_id = :id_emp", "id={$id}&id_emp={$empresa_id}");
        return $read->getResult()[0];
    }

    public function readPagination($limit, $offset, $params, $company)
    {
        $busca = 'g.genealogia_id, g.nome, g.data_nascimento, g.status, p.pessoa as pai, m.pessoa as mae, c.pessoa as conjuge ';
        $QueryParams = $this->montaQuery($params);
        $readList = new Read;
        $readList->FullRead("select {$busca} from inf_genealogias as g "
            . "left JOIN vw_pessoasgenealogia as p ON p.genealogia_id = g.id_pai "
            . "left JOIN vw_pessoasgenealogia as m ON m.genealogia_id = g.id_mae "
            . "left JOIN vw_pessoasgenealogia as c ON c.genealogia_id = g.id_conjuge "
//                . "left JOIN (select genealogia_id, nome as nomeMae from inf_genealogias) as m ON m.genealogia_id = g.id_mae "
//                . "left JOIN (select genealogia_id, nome as nomeConjuge from inf_genealogias) as c ON c.genealogia_id = g.id_conjuge "
            . "where g.genealogia_empresa_id = {$company} {$QueryParams} limit :limit offset :offset",
            "limit={$limit}&offset={$offset}");

        return $readList->getResult();
    }

    public function countByEmp($id, $params)
    {
        $read = new Read;
        $QueryParams = $this->montaQuery($params);
        $read->FullRead("Select genealogia_id from inf_genealogias as g WHERE genealogia_empresa_id = :id {$QueryParams}", "id={$id}");
        return $read->getRowCount();
    }

    public function montaQuery($params)
    {
        $nome = (isset($params['nome']) ? "AND g.nome like '%{$params['nome']}%' " : "");
        $pai = (isset($params['pai']) ? "AND p.pessoa like '%{$params['pai']}%' " : "");
        $mae = (isset($params['mae']) ? "AND m.pessoa like '%{$params['mae']}%' " : "");
        $conjuge = (isset($params['conjuge']) ? "AND c.pessoa like '%{$params['conjuge']}%' " : "");
        unset($params);
        return $nome . $pai . $mae . $conjuge;
    }

    public function create($data)
    {
        if (isset($data['file'])) {
            $file = $data['file'];
            unset($data['file']);
        } else {
            $file = null;
        }
        $cadastra = new AdminGenealogias();
        $cadastra->ExeCreate($data);

        if ($cadastra->getResult()):
            if ($file) {
                require("uploadController.class.php");
                $uploads = new uploadController();
                $uploads->gravaImagem($_FILES['file'], [
                    "id" => $cadastra->getResult(),
                    "imagem_type" => 4,
                    "imagem_empresa_id" => $data["genealogia_empresa_id"],
                    "imagem_descricao" => "Foto de {$data['nome']} - arvore genealógica.",
                    "imagem_data" => date("Y-m-d H:i:s")
                ]);
                if (!$uploads->getResult()):
                    die(header("HTTP/1.0 404 Not Found")); //Throw an error on failure
                else:
                    $dataFoto["path_foto"] = $uploads->getResult();
                    $update = new AdminGenealogias();
                    $update->ExeUpdate($cadastra->getResult(), $dataFoto);
                endif;
            }
            redireciona("painel.php?exe=genealogia/update&create=true&id=" . $cadastra->getResult());
        else:
            return IMDErro($cadastra->getError()[0], $cadastra->getError()[1]);
        endif;

    }

    public function updade($id, $data)
    {

        $update = new AdminGenealogias();

        if (isset($data['file'])) {
            require("uploadController.class.php");
            $uploads = new uploadController();
            $uploads->gravaImagem($_FILES['file'], [
                "id" => $id,
                "imagem_type" => 4,
                "imagem_empresa_id" => $data["genealogia_empresa_id"],
                "imagem_descricao" => "Foto de {$data['nome']} - arvore genealógica.",
                "imagem_data" => date("Y-m-d H:i:s")
            ]);
            if (!$uploads->getResult()):
                die(header("HTTP/1.0 404 Not Found")); //Throw an error on failure
            else:
                $data["path_foto"] = $uploads->getResult();
                unset($data["file"]);
            endif;
        }
        $update->ExeUpdate($id, $data);
        return IMDErro($update->getError()[0], $update->getError()[1]);
    }

    public function delete($id)
    {
        $deletar = new AdminGenealogias();
        $deletar->ExeDelete($id);
        return IMDErro($deletar->getError()[0], $deletar->getError()[1]);
    }

}
