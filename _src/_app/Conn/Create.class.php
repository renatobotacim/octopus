<?php

/**
 * Create.class [ TIPO ]
 * Classe responsável por cadastros genéricos no banco de dados!
 * @copyright (c) 2017, Renato S. Botacim - Infire Soluções
 */
class Create extends Conn {

    private $Tabela;
    private $Dados;
    private $Result;

    /** @var PDOStatement */
    private $Create;

    /** @var PDO */
    private $Conn;

    /**
     * <b>ExeCreate:</b>Executa um cadastro simplificado no banco de dados utilizando prepared statements.
     * Basta informar o nome da tabela e um array atribuitivo com o nome da coluna e valor!
     * 
     * @param STRING $Tabela = Informe o nome da tabela no banco!
     * @param ARRAY $Dados = Informe um array atribuitivo. (Nome da colula =>valor).
     */
    public function ExeCreate($Tabela, array $Dados) {
        $this->Tabela = (string) $Tabela;
        $this->Dados = $Dados;
        $this->getSintax();
        $this->Execute();
    }

    /**
     * <b>gerResult:</b>Retorna o id do registro inserido, ou FALSE caso não tenha sido inserido nenhum registro
     * @return INT $variavel = lastInsertId OF FALSE
     */
    public function getResult() {
        return $this->Result;
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
        // obtenção da conexão com o banco. A conexão feita na Class Conn
        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($this->Create);
    }

    /** <b>getSintax:</b>Cria a sintax para o Prepared Statement */
    private function getSintax() {
        $Fileds = implode(', ', array_keys($this->Dados));
        $Places = ':' . implode(', :', array_keys($this->Dados));
        $this->Create = "INSERT INTO {$this->Tabela} ({$Fileds}) VALUES ({$Places})";
    }

    /*     * <b>Execute:</b>Faz a execução da gravação dos dados no banco!. */

    private function Execute() {
        $this->Connect();
        try {
            $this->Create->execute($this->Dados);
            $this->Result = $this->Conn->lastInsertId();
        } catch (PDOException $e) {
            $this->Result = null;
//            IMDErro("<b>Erro ao cadastrar: </b>{$e->getMessage()}", $e->getCode());
                IMDErro("<h4><i class=\"fas fa-exclamation-triangle\" aria-hidden=\"true\"></i>&nbsp;&nbsp;&nbsp;<b>Erro ao Cadastrar!</b></h4>"
            . "{$e->getMessage()}<br>{$e->getCode()}", IMD_ERROR);
        }
    }

}
