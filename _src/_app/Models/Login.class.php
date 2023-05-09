<?php

/**
 * Login.class [ MODEL ]
 * Responável por autenticar, validar, e checar usuário do sistema de login!
 * 
 * @copyright (c) 2020, Renato S. Botacim Infire Soluções
 */
class Login {

    private $Level;
    private $User;
    private $Senha;
    private $Error;
    private $Result;
    private $Recover;

    /**
     * <b>Informar Level:</b> Informe o nível de acesso mínimo para a área a ser protegida.
     * @param INT $Level = Nível mínimo para acesso
     */
    function __construct($Level) {
        $this->Level = (int) $Level;
    }

    /**
     * <b>Efetuar Login:</b> Envelope um array atribuitivo com índices STRING user [email], STRING pass.
     * Ao passar este array na ExeLogin() os dados são verificados e o login é feito!
     * @param ARRAY $UserData = user [email], pass
     */
    public function ExeLogin(array $UserData) {
        $this->User = (string) strip_tags(trim($UserData['user']));
        $this->Senha = (string) strip_tags(trim($UserData['pass']));
        $this->setLogin();
    }

    public function ExeRecover(array $UserData) {
        $this->User = (string) strip_tags(trim($UserData['user']));
        $this->setRecoverPass();
    }

    /**
     * <b>Verificar Login:</b> Executando um getResult é possível verificar se foi ou não efetuado
     * o acesso com os dados.
     * @return BOOL $Var = true para login e false para erro
     */
    public function getResult() {
        return $this->Result;
    }

    /**
     * <b>Obter Erro:</b> Retorna um array associativo com uma mensagem e um tipo de erro WS_.
     * @return ARRAY $Error = Array associatico com o erro
     */
    public function getError() {
        return $this->Error;
    }

    /**
     * <b>Obter Level do usuário:</b> 
     */
    public function getLevel() {
        return $this->Level;
    }

    /**
     * <b>Obter o estatus do recover:</b> Caso seu retorno seja true, exibe uma mensgem de sucesso
     * para o usuário que sua senha foi enviada para seu email!
     * @return type
     */
    function getRecover() {
        return $this->Recover;
    }

    function setRecover($Recover) {
        $this->Recover = $Recover;
    }

    /**
     * <b>Checar Login:</b> Execute esse método para verificar a sessão USERLOGIN e revalidar o acesso
     * para proteger telas restritas.
     * @return BOLEAM $login = Retorna true ou mata a sessão e retorna false!
     */
    public function CheckLogin() {
        if (empty($_SESSION['userlogin']) || $_SESSION['userlogin']['usuario_permissao'] <= $this->Level):
            unset($_SESSION['userlogin']);
            return false;
        else:
            return true;
        endif;
    }

    public function CheckLevel() {
        if ($_SESSION['userlogin']['usuario_permissao'] <= $this->Level):
            return false;
        else:
            return true;
        endif;
    }

    /*
     * ***************************************
     * **********  PRIVATE METHODS  **********
     * ***************************************
     */

//Valida os dados e armazena os erros caso existam. Executa o login!
    private function setLogin() {
        if (!$this->User || !$this->Senha):
            $this->Error = ["<b>OPSSS,</b><br>enter your username or email and password to login!", IMD_INFOR];
            $this->Result = false;
        elseif (!$this->getUser()):
            $this->Error = ["<b>Sorry,</b><br>We did not find any users with permission to access this dashboard. Check your credentials and try again!", IMD_ALERT];
            $this->Result = false;
        else:
           $this->Execute();
        endif;
    }

    //Vetifica usuário e senha no banco de dados!
    private function getUser() {
        $readpass = new Read;
        $readpass->ExeRead("inf_usuarios", "WHERE usuario_email = :e or usuario_usuario= :c", "e={$this->User}&c={$this->User}");
        if ($readpass->getResult()):
            $data = $readpass->getResult()[0];
            if (cript($this->Senha) == $data['usuario_senha'] && $data['usuario_status'] == 1 ):
//                unset($data['usuario_login']);
                $this->Level = $data['usuario_permissao'];
                $this->Result = $data;
                return true;
            else:
                return false;
            endif;
        else:
            return false;
        endif;
    }

//Executa o login armazenando a sessão!
    private function Execute() {
        if (!session_id()):
            session_start();
        endif;
        $_SESSION['userlogin'] = $this->Result;
        $this->Error = ["<i class=\"fa fa-info-circle\" aria-hidden=\"true\"></i><br>Olá, seja bem vindo(a). Aguarde redirecionamento!", IMD_ACCEPT];
        $this->Result = true;
    }

}
