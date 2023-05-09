<?php

/**
 * Delete.class [ TIPO ]
 * Classe responsável por deletar genéricamente no banco de dados!
 * @copyright (c) 2017, Renato S. Botacim - Infire Soluções
 */
class Delete extends Conn {

    private $Tabela;
    private $Termos;
    private $Places;
    private $Result;

    /** @var PDOStatement */
    private $Delete;

    /** @var PDO */
    private $Conn;

    /**
     * <b>ExeDelete:</b>Executa um cadastro simplificado no banco de dados utilizando prepared statements.
     * Basta informar o nome da tabela e um array atribuitivo com o nome da coluna e valor!
     * 
     * @param STRING $Tabela = Informe o nome da tabela no banco!
     * @param ARRAY $Dados = Informe um array atribuitivo. (Nome da colula =>valor).
     */
    public function ExeDelete($Tabela, $Termos, $ParseString) {
        $this->Tabela = (string) $Tabela;
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

    public function getRowCount() {
        return $this->Delete->rowCount();
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
        $this->Delete = $this->Conn->prepare($this->Delete);
    }

    /**
     * <b>getSintax:</b>Obtem a sintax, ela que vai montar a query!.
     */
    private function getSintax() {
        $this->Delete = "DELETE FROM {$this->Tabela} {$this->Termos}";
    }

    /**
     * <b>Execute:</b>Faz a execução da gravação dos dados no banco!.
     */
    private function Execute() {
        $this->Connect();
        try {
            $this->Delete->execute($this->Places);
            $this->Result = true;
        } catch (PDOException $e) {
            $this->Result = null;
            IMDErro("<i class=\"fa fa-close\" aria-hidden=\"true\"></i><br><b>Erro ao Deletar!</b><br>{$e->getMessage()}", IMD_ERROR);
//            IMDErro("<b>Erro ao Deletar: </b>{$e->getMessage()}", $e->getCode());
        }
    }
}
