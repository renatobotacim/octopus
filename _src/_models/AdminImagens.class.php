<?php
/**
 * AdminJobs.class [ MODEL ADMIN ]
 * Modelo Resposável por gerenciar os jobs do sistema admin!
 * @copyright (c) 2020, Renato S. Botacim - Infire Soluções
 */
class AdminImagens {

    private $Data;
    private $Id;
    private $Error;
    private $Result;

    const Entity = "inf_imagens";
    const ColumnID = "imagem_id";

    public function ExeCreate(array $Data) {
        $this->Data = $Data;
        $this->Create();
    }

    public function ExeUpdate($ID, array $Data) {
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
            $this->Error = ["<b>Delete Error!</b><br>To delete it is necessary to inform an existing record.", IMD_ALERT];
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


    
//FAZ O CADASTRO NO BANCO DE DADOS
    private function Create() {
        $create = new Create();
        $create->ExeCreate(self::Entity, $this->Data);
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
            $this->Error = ["<b>Success!</b><br>The record has been updated.", IMD_ACCEPT];
        else:
            $this->Result = false;
            $this->Error = ["<b>Excuse me!</b><br>An error occurred while updating the record!", IMD_ERROR];
        endif;
    }

    //FAZ O UPDATE DA CATEGORIA NO BANCO DE DADOS   
    private function Delete() {
        $Delete = new Delete();
        $Delete->ExeDelete(self::Entity, "WHERE " . self::ColumnID . " = :deleteId", "deleteId={$this->Id}");
        if ($Delete->getResult()):
            $this->Result = true;
            $this->Error = ["<b>Sucesso ao Deletar!</b><br>Registro deletado com sucesso!", IMD_ACCEPT];
        endif;
    }

}
