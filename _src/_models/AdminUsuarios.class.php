<?php

/**
 * AdminCategory.class [ MODEL ADMIN ]
 * Resposável por gerenciar os usuários do sistema admin!
 * @copyright (c) 2020, Renato S. Botacim - Infire Soluções
 */
class AdminUsuarios {

    private $Data;
    private $Id;
    private $Error;
    private $Result;
    private $Email;

    //nome da tabela no banco de dados
    const Entity = "inf_usuarios";
    const ColumnID = "usuario_id";

    public function ExeCreate(array $Data) {
        $this->Data = $Data;
        $this->Create();
    }

    public function ExeUpdate($ID, array $Data) {
        $this->Id = (int) $ID;
        $this->Data = $Data;
        $this->Update();
    }

    public function ExePass($ID, array $Data) {
        $this->Id = (int) $ID;
        $this->Data = $Data;
        $this->UpdatePass();
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

    //valida e cria os dados para realizar o cadastro
    private function setData() {
        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);
    }

    //Verifica o NAME da categoria. Se existir, adiciona um pós-fix + 1
    private function setName() {
        $where = (!empty($this->Id) ? "usuario_id != {$this->Id} AND" : '');
        $readName = new read;
        $readName->ExeRead(self::Entity, "WHERE {$where} usuario_name = :t", "t={$this->Data['usuario_name']}");
        if ($readName->getResult()):
            $this->Data['usuario_name'] = $this->Data['usuario_name'] . $readName->getRowCount();
        endif;
    }

    private function setEmail($setEmail) {
        $reademail = new read;
        $reademail->ExeRead(self::Entity, "WHERE usuario_email = :email", "email={$setEmail}");
        if ($reademail->getRowCount() == 0):
            $this->Result = true;
        else:
            $this->Result = false;
        endif;
    }

//FAZ O CADASTRO NO BANCO DE DADOS
    private function Create() {
        $create = new Create();
        $create->ExeCreate(self::Entity, $this->Data);
        if ($create->getResult()):
            $this->Result = [$create->getResult(), $this->Email];
        endif;
    }

    //FAZ O UPDATE DA CATEGORIA NO BANCO DE DADOS
    private function Update() {
        $Update = new Update();
        $Update->ExeUpdate(self::Entity, $this->Data, "WHERE " . self::ColumnID . " = :id", "id={$this->Id}");
        if ($Update->getResult()):
            $this->Result = true;
            if (isset($this->Data['usuario_status'])):
                if ($this->Data['usuario_status'] == 1):
                    $this->Error = ["<b>Sucesso!</b><br>O usuário foi reatiavado no sistema!", IMD_ACCEPT];
                else:
                    $this->Error = ["<b>Sucesso!</b><br>O usuário foi desativado do sistema!", IMD_ACCEPT];
                endif;
            else:
                $this->Error = ["<b>Sucesso!</b><br>O Registro foi alteado com sucesso!", IMD_ACCEPT];
            endif;
        else:
            $this->Result = false;
            $this->Error = ["<b>OPSSS!</b><br>Ocorreu algum erro ao alterar esse registro!", IMD_ERROR];
        endif;
    }

    private function UpdatePass() {
        $Update = new Update();
        $Update->ExeUpdate(self::Entity, $this->Data, "WHERE " . self::ColumnID . " = :id", "id={$this->Id}");
        if ($Update->getResult()):
            $this->Result = true;
            $this->Error = ["<b>Sucesso!</b><br>Sua senha foi redefinida e enviada por email!.", IMD_ACCEPT];
        else:
            $this->Error = ["<b>OPSSS!</b><br>Ocorreu algum erro ao alterar esse registro!", IMD_ERROR];
        endif;
    }

    //FAZ O UPDATE DA CATEGORIA NO BANCO DE DADOS   
    private function Delete() {
        $Delete = new Delete();
        $Delete->ExeDelete(self::Entity, " WHERE " . self::ColumnID . " = :deleteId", "deleteId={$this->Id}");
        if ($Delete->getResult()):
            $this->Result = true;
            $this->Error = ["<b>Success Delete!</b><br>The record has been deleted from the system", IMD_ACCEPT];
        endif;
    }

}
