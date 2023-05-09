<?php

/**
 * licitacoesController [ CONTROLLER ADMIN ]
 * Controlador Resposável por gerenciar os Licitações do sistema admin!
 * @copyright (c) 2021, Renato S. Botacim - Infire Soluções Digitais
 */
require_once '../_app/Config.inc.php';
require_once('../_models/AdminCollaborators.class.php');
require_once('../_models/AdminArquivos.class.php');

class CollaboratorController
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
        $read->FullRead("Select c.*, i.imagem_host, i.imagem_link from inf_colaboradores as c 
                                         left JOIN inf_imagens as i ON i.imagem_id = c.colaborador_imagem 
                                         WHERE c.colaborador_id = :id AND c.colaborador_empresa_id = :id_emp", "id={$id}&id_emp={$empresa_id}");
        return $read->getResult()[0];
    }

    public function readPagination($limit, $offset, $params, $company)
    {
        $busca = 'c.* ';
        $QueryParams = $this->montaQuery($params);
        $readList = new Read;
        $readList->FullRead("select {$busca} from inf_colaboradores as c "
            //. "left JOIN vw_pessoascolaboradore as c ON c.colaboradore_id = g.id_conjuge "
//                . "left JOIN (select colaboradore_id, nome as nomeMae from inf_colaboradores) as m ON m.colaboradore_id = g.id_mae "
//                . "left JOIN (select colaboradore_id, nome as nomeConjuge from inf_colaboradores) as c ON c.colaboradore_id = g.id_conjuge "
            . "where c.colaborador_empresa_id = {$company} {$QueryParams} limit :limit offset :offset",
            "limit={$limit}&offset={$offset}");
        return $readList->getResult();
    }

    public function countByEmp($id, $params)
    {
        $read = new Read;
        $QueryParams = $this->montaQuery($params);
        $read->FullRead("Select colaborador_id from inf_colaboradores as g WHERE colaborador_empresa_id = :id {$QueryParams}", "id={$id}");
        return $read->getRowCount();
    }

    public function montaQuery($params)
    {
        $nome = (isset($params['nome']) ? "AND c.colaborador_nome like '%{$params['nome']}%' " : "");
        unset($params);
        return $nome;
    }

    public function create($data)
    {
        if (isset($data['colaborador_imagem'])) {
            $file = $data['colaborador_imagem'];
            unset($data['colaborador_imagem']);
        } else {
            $file = null;
        }
        $cadastra = new AdminCollaborators();
        $cadastra->ExeCreate($data);

        if ($cadastra->getResult()):
            if ($file) {
                require("uploadController.class.php");
                $uploads = new uploadController();
                $uploads->gravaImagem($_FILES['colaborador_imagem'], [
                    "id" => $cadastra->getResult(),
                    "imagem_type" => 3,
                    "imagem_empresa_id" => $data["colaborador_empresa_id"],
                    "imagem_descricao" => "Foto de {$data['colaborador_nome']}.",
                    "imagem_data" => date("Y-m-d H:i:s")
                ]);
                if (!$uploads->getResult()):
                    die(header("HTTP/1.0 404 Not Found")); //Throw an error on failure
                else:
                    $dataFoto["colaborador_imagem"] = $uploads->getResult();
                    $update = new AdminCollaborators();
                    $update->ExeUpdate($cadastra->getResult(), $dataFoto);
                endif;
            }
            redireciona("painel.php?exe=collaborators/update&create=true&id=" . $cadastra->getResult());
        else:
            return IMDErro($cadastra->getError()[0], $cadastra->getError()[1]);
        endif;

    }

    public function updade($id, $data)
    {

        $update = new AdminCollaborators();

        if (isset($data['colaborador_imagem'])) {
            require("uploadController.class.php");
            $uploads = new uploadController();
            $uploads->gravaImagem($_FILES['colaborador_imagem'], [
                "id" => $id,
                "imagem_type" => 3,
                "imagem_empresa_id" => $data["colaborador_empresa_id"],
                "imagem_descricao" => "Foto de {$data['colaborador_nome']}.",
                "imagem_data" => date("Y-m-d H:i:s")
            ]);
            if (!$uploads->getResult()):
                die(header("HTTP/1.0 404 Not Found")); //Throw an error on failure
            else:
                $data["colaborador_imagem"] = $uploads->getResult();
            endif;
        }
        $update->ExeUpdate($id, $data);
        return IMDErro($update->getError()[0], $update->getError()[1]);
    }

    public function delete($id)
    {
        $deletar = new AdminCollaborators();
        $deletar->ExeDelete($id);
        return IMDErro($deletar->getError()[0], $deletar->getError()[1]);
    }

}
