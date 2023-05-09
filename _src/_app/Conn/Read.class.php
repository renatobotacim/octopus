<?php

/**
 * Read.class [ TIPO ]
 * Classe responsável por leituras genéricas no banco de dados!
 * @copyright (c) 2017, Renato S. Botacim - Infire Soluções
 */
class Read extends Conn {

    private $Select;
    private $Places;
    private $Result;

    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    /**
     * <b>ExeRead:</b>Executa um cadastro simplificado no banco de dados utilizando prepared statements.
     * Basta informar o nome da tabela e um array atribuitivo com o nome da coluna e valor!
     * 
     * @param STRING $Tabela = Informe o nome da tabela no banco!
     * @param ARRAY $Dados = Informe um array atribuitivo. (Nome da colula =>valor).
     */
    public function ExeRead($Tabela, $Termos = null, $ParseString = null) {
        if (!empty($ParseString)):
            $this->Places = $ParseString;
            parse_str($ParseString, $this->Places);
        endif;

        $this->Select = "SELECT * FROM {$Tabela} {$Termos}";
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
        return $this->Read->rowCount();
    }

    public function FullRead($Query, $ParseString = null) {
        $this->Select = (string) $Query;
        if (!empty($ParseString)):
            $this->Places = $ParseString;
            parse_str($ParseString, $this->Places);
        endif;
        $this->Execute();
    }
    
    public function setPlaces($ParseString){
        //reseta a string
        parse_str($ParseString, $this->Places);
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
        //objeto PDO
        //faz a conexão com o banco de dados contido na class pai Conn.
        $this->Conn = parent::getConn();
        //prepara a query;
        $this->Read = $this->Conn->prepare($this->Select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
    }

    /**
     * <b>getSintax:</b>Obtem a sintax, ela que vai montar a query!.
     */
    private function getSintax() {
        if ($this->Places):
            foreach ($this->Places as $Vinculo => $Valor):
                if ($Vinculo == 'limit' || $Vinculo == 'offset'):
                    $Valor = (int) $Valor;
                endif;
                $this->Read->bindValue(":{$Vinculo}", $Valor, (is_int($Valor) ? PDO::PARAM_INT : PDO::PARAM_STR));
            endforeach;
        endif;
    }

    /**
     * <b>Execute:</b>Faz a execução da gravação dos dados no banco!.
     */
    private function Execute() {
        $this->Connect();
        try {
            $this->getSintax();
            $this->Read->execute();
            $this->Result = $this->Read->fetchAll();
        } catch (PDOException $e) {
            $this->Result = null;
            IMDErro("<b>Erro ao Ler: </b>{$e->getMessage()}", $e->getCode());
        }
    }

}
