<?php

/**
 * AdminJobs.class [ MODEL ADMIN ]
 * Modelo Resposável por gerenciar os serviços do sistema admin!
 * @copyright (c) 2021, Renato S. Botacim - Infire Soluções
 */
class AdminCategories {

    private $Data;
    private $Id;
    private $Error;
    private $Result;

    //nome da tabela no banco de dados

    const Entity = "inf_categorias";
    const ColumnID = "categoria_id";

    public function ExeCreate(array $Data) {
        $this->Data = $Data;
        $this->setData();
        $this->Create();
    }

    public function ExeUpdate($ID, array $Data) {
        $this->Id = (int) $ID;
        $this->Data = $Data;
        $this->setData();
        $this->Update();
    }

    public function ExeAprove($ID, array $Data) {
        $this->Id = (int) $ID;
        $this->Data = $Data;
        $this->Update();
    }

    public function ExeDelete($id) {
        $this->Id = (int) $id;
        $read = new Read;
        $read->ExeRead(self::Entity, "WHERE " . self::ColumnID . "  = :deleteId", "deleteId={$this->Id}");
        if (!$read->getResult()):
            $this->Result = false;
            $this->Error = ["<b>OPSSS!</b><br>Para deletar é necessário enviar um registro existente", IMD_ALERT];
        else:
            extract($read->getResult()[0]);
            $this->Delete();
            $this->Result = true;
        endif;
    }

    /**
     * <b>Verifica Cadastro:</b> Retorna true se o cadastro ou update for efetuado com sucesso ou false se não. Para ver os erros
     * execute o comando getError();
     * @retun BOOL var = True ou False;
     */
    function getResult() {
        return $this->Result;
    }

    /**
     * <b>Obtem o Erro:</b> Retorna um array associativo com a mensagem de erro do tipo erro!
     * As configuraçãoes de personalização do erro estão no arquivo Config.inc.php
     * @retun ARRAY $Erro = Array associativo com o erro;
     * [0] -> Mensagem do erro, [1] -> Tipo do erro gerado!
     */
    function getError() {
        return $this->Error;
    }

    /**
     * ****************************************
     * *********** PRIVATE METHODS ************
     * ****************************************
     */
    //valida e cria os dados para realizar o cadastro
    private function setData() {
        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);
    }

    //FAZ O CADASTRO NO BANCO DE DADOS
    private function Create() {
        $create = new Create();
        $create->ExeCreate(self::Entity, $this->Data);
        var_dump($create);
        if ($create->getResult()):
            $this->Result = $create->getResult();
        endif;
    }

    //FAZ O UPDATE DA CATEGORIA NO BANCO DE DADOS
    private function Update() {
        $Update = new Update();
        $Update->ExeUpdate(self::Entity, $this->Data, "WHERE " . self::ColumnID . " = :id", "id={$this->Id}");
        if ($Update->getResult()):
            $this->Result = true;
            $this->Error = ["<b>Sucesso!</b><br>Os registro alterado com sucesso!", IMD_ACCEPT];
        else:
            $this->Result = false;
            $this->Error = ["<b>OPSSS!</b><br>Ocorreu um erro ao atualizar este registro, tente novamente!", IMD_ERROR];
        endif;
    }

    //FAZ O UPDATE DA CATEGORIA NO BANCO DE DADOS   
    private function Delete() {
        $Delete = new Delete();
        $Delete->ExeDelete(self::Entity, "WHERE " . self::ColumnID . " = :deleteId", "deleteId={$this->Id}");
        if ($Delete->getResult()):
            $this->Result = true;
            $this->Error = ["<b>Sucesso!</b><br>O registro foi deletado do sistema.", IMD_ACCEPT];
        endif;
    }

}
