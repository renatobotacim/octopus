<?php

/**
 * Read.class [ TIPO ]
 * Classe responsável por atualizações genéricas no banco de dados!
 * @copyright (c) 2017, Renato S. Botacim - Infire Soluções
 */
class Update extends Conn {

    private $Tabela;
    private $Dados;
    private $Termos;
    private $Places;
    private $Result;
    private $Error;

    /** @var PDOStatement */
    private $Update;

    /** @var PDO */
    private $Conn;

    /**
     * <b>ExeUpdate:</b>Executa um cadastro simplificado no banco de dados utilizando prepared statements.
     * Basta informar o nome da tabela e um array atribuitivo com o nome da coluna e valor!
     * 
     * @param STRING $Tabela = Informe o nome da tabela no banco!
     * @param ARRAY $Dados = Informe um array atribuitivo. (Nome da colula =>valor).
     */
    public function ExeUpdate($Tabela, array $Dados, $Termos, $ParseString) {
        $this->Tabela = (string) $Tabela;
        $this->Dados = $Dados;
        $this->Termos = (string) $Termos;

        parse_str($ParseString, $this->Places);
        $this->getSintax();
        $this->Execute();
    }

    /**
     * <b>gerResult:</b>Retorna um valor true ou false de acordo com o resultado da insrção de dados na tabela.
     * @return BOLEAN Retorna resultado de um cadastro ou não
     */
    public function getResult() {
        return $this->Result;
    }

    public function getError() {
        return $this->Error;
    }

    public function getRowCount() {
        return $this->Update->rowCount();
    }

    public function setPlaces($ParseString) {
        //reseta a string
        parse_str($ParseString, $this->Places);
        $this->getSintax();
        $this->Execute();
    }

    /**
     * ****************************************
     * ********** PRIVATE METHODS *************
     * ****************************************
     */

    /**
     * <b>Connect:</b>Vai obter a conexão com a PDO.
     */
    private function Connect() {
        $this->Conn = parent::getConn();
        $this->Update = $this->Conn->prepare($this->Update);
    }

    /**
     * <b>getSintax:</b>Obtem a sintax, ela que vai montar a query!.
     */
    private function getSintax() {
        foreach ($this->Dados as $Key => $Value):
            $Places[] = $Key . ' = :' . $Key;
        endforeach;
        $Places = implode(', ', $Places);
        $this->Update = "UPDATE {$this->Tabela} SET {$Places} {$this->Termos}";
    }

    /**
     * <b>Execute:</b>Faz a execução da gravação dos dados no banco!.
     */
    private function Execute() {
        $this->Connect();
        try {
            $this->Update->execute(array_merge($this->Dados, $this->Places));
            $this->Result = true;
        } catch (PDOException $e) {
            $this->Result = null;
            $this->Error = $e->getMessage();
        }
    }

}
