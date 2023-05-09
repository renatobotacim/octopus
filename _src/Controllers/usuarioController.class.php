<?php

require_once '../_app/Config.inc.php';
require_once ('../_models/AdminUsuarios.class.php');

class usuarioController {

    private $rowCount;

    public function _getMetrics($company) {
        
    }

    public function _getRowCount() {
        return $this->rowCount;
    }

    public function _filters($urlBase, $filtros, $params) {
        require('_generic_filters.php');
    }

    public function read($id, $empresa_id) {
        $read = new Read;
        $read->FullRead("Select * from inf_usuarios WHERE usuario_id = :id AND usuario_empresa_id = :id_emp", "id={$id}&id_emp={$empresa_id}");
        return $read->getResult()[0];
    }

    public function readPagination($limit, $offset, $params, $company) {
        $busca = '*';
        $QueryParams = $this->montaQuery($params);
        $readList = new Read;
        $readList->FullRead("select {$busca} from inf_usuarios "
                . "where usuario_empresa_id = {$company} {$QueryParams} limit :limit offset :offset",
                "limit={$limit}&offset={$offset}");
        return $readList->getResult();
    }

    public function countByEmp($id, $params) {
        $read = new Read;
        $QueryParams = $this->montaQuery($params);
        $read->FullRead("Select usuario_id from inf_usuarios WHERE usuario_empresa_id = :id {$QueryParams}", "id={$id}");
        return $read->getRowCount();
    }

    public function montaQuery($params) {
        $mes = "";
        unset($params);
        return $mes;
    }

    public function create($Data) {
        $srt = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%&*'), 0, 6);
        $Data['usuario_senha'] = cript($srt);
        $Data['usuario_start'] = date('Y-m-d');
        $Data['usuario_status'] = 1;
        $cadastra = new AdminUsuarios();
        $cadastra->ExeCreate($Data);
        if (!$cadastra->getResult()):
            IMDErro($cadastra->getError()[0], $cadastra->getError()[1]);
        else:
            $Home = HOME;
            $ano = date('Y');
            $email['Assunto'] = 'Usuário Cadastrado!';
            $email['Mensagem'] = "Olá {$Data['usuario_nome']}, tudo bem? <br><br>Você acabou de ser cadastrado na plataforma gerenciadora de Alvará!<br><br>"
                    . "A sua senha de acesso a plataforma é: {$srt}<br><br>"
                    . "Clique no link ao lado para acessar a plataforma: {$Home}<br><br>"
                    . "Recomenda-se que ao acessar o sistema, efetue a senha para uma de sua confiaça. Não compartilhe sua senha com outras pessoas!"
                    . "<br><br><br>"
                    . "<b>© Sistema Alvará</b><br><hr>Sistema de gerenciamento de Alvarás<br>"
                    . "2020-{$ano} - Infire Soluções Digitais CNPJ: 35.666.781/0001-32 Todos os direitos reservados.";
            $email['RemetenteNome'] = 'Sistema de Gestor de Conteúdo';
            $email['RemetenteEmail'] = 'sistema@infire.com.br';
            $email['DestinoNome'] = "Infire Soluções Digitais";
            $email['DestinoEmail'] = $Data['usuario_email'];
            $sendEmail = new Email();
            $sendEmail->Enviar($email);
            redireciona("painel.php?exe=usuarios/update&create=true&id=" . $cadastra->getResult()[0]);
        endif;
    }

    public function status($id, $status) {
        if ($status == 1):
            $Data['usuario_status'] = 1;
            $Data['usuario_start'] = date('Y-m-d');
            $Data['usuario_end'] = null;
        else:
            $Data['usuario_status'] = 2;
            $Data['usuario_end'] = date('Y-m-d');
        endif;
        $update = new AdminUsuarios();
        $update->ExeUpdate($id, $Data);
        IMDErro($update->getError()[0], $update->getError()[1]);
    }

    public function updade($id, $Data) {
        $update = new AdminUsuarios();
        $update->ExeUpdate($id, $Data);
        IMDErro($update->getError()[0], $update->getError()[1]);
    }

    public function password($id, $pass) {
        if ($pass == 'default'):
            $srt = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%&*'), 0, 6);
            $Data['usuario_senha'] = cript($srt);
            $SetPass = new AdminUsuarios();
            $SetPass->ExePass($id, $Data);
            if ($SetPass->getResult()):
                $read = new Read();
                $read->FullRead("select usuario_email from inf_usuarios where usuario_id = :id", "id={$id}");
                $ano = date('Y');
                $email['Assunto'] = 'Recuperação de Senha!';
                $email['Mensagem'] = "A sua senha de acesso a plataforma de gerenciamento de conteúdo!<br><br>"
                        . "Se você não fez a solicitação, comunique ao seu gestor imediatamente!<br><br>"
                        . "A sua nova senha de acesso ao sistema é: {$srt}<br><br>"
                        . "Recomenda-se que ao acessar o sistema, efetue a senha para uma de sua confiaça. Não compartilhe sua senha com outras pessoas!"
                        . "<br><br><br>"
                        . "<b>© CMS Infire</b><br><hr>Sistema de gerenciamento de contetúdo<br>"
                        . "2020-{$ano} - Infire Soluções Digitais - Todos os direitos reservados.";
                $email['RemetenteNome'] = 'Sistema de Gestor de Alvarás';
                $email['RemetenteEmail'] = 'sistema@infire.com.br';
                $email['DestinoNome'] = "Infire Mídias Digitais";
                $email['DestinoEmail'] = $read->getResult()[0]['usuario_email'];
                $sendEmail = new Email();
                $sendEmail->Enviar($email);
            endif;
        else:
            $Data['usuario_senha'] = cript($pass);
            $SetPass = new AdminUsuarios();
            $SetPass->ExePass($id, $Data);
        endif;

        IMDErro($SetPass->getError()[0], $SetPass->getError()[1]);
    }

    public function delete($id) {
        $deletar = new AdminUsuarios();
        $deletar->ExeDelete($id);
        return IMDErro($deletar->getError()[0], $deletar->getError()[1]);
    }

}
