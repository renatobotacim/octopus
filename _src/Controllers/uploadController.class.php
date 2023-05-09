<?php

require_once('../_app/Config.inc.php');
require_once('../_models/AdminArquivos.class.php');
require_once('../_models/AdminImagens.class.php');

class uploadController {

    private $Error;
    private $Result;

    public function converteImg($file) {
        // Le o arquivo binario da imagem
        $data = file_get_contents($file['tmp_name']);
        // retorna o valor transformado em hexadecimal
        $logo = base64_encode($data);
        if (!isset($logo)):
            return false;
        else:
            return base64_encode($data);
        endif;
    }

    public function gravar($file, $dataParecer) {

        $upload = new Upload();
        $upload->File($file, $file['name'], "/{$dataParecer['anexo_prefeitura_id']}/{$dataParecer['anexo_protocolo']}");

        if ($upload->getResult()):
            $data['anexo_nome'] = $file['name'];
            $data['anexo_formato'] = pathinfo($file['name'], PATHINFO_EXTENSION);
            $data['anexo_link'] = $upload->getResult();
            $data['anexo_arquivo'] = null;
            $data['anexo_protocolo'] = $dataParecer['anexo_protocolo'];
            $data['anexo_tipo'] = $dataParecer['anexo_tipo'];
            $data['anexo_prefeitura_id'] = $dataParecer['anexo_prefeitura_id'];
            $data['anexo_setor_id'] = $dataParecer['anexo_setor_id'];
            $data['anexo_usuario_id'] = $dataParecer['anexo_usuario_id'];

            $cadastra = new AdminAnexos();
            $cadastra->ExeCreate($data);
            if (!$cadastra->getResult()):
                $this->Error = IMDErro('<b>Ocorreu um erro!</b><br>Não foi possível regitrar o seu anexo no sistema, Tente Novamente!', IMD_ERROR);
            else:
                $this->Result = [$cadastra->getResult(), $data];
            endif;
        else:
            $this->Result = false;
        endif;




        //converte cada arquivo para um indice do array
//        $gbFiles = array();
//        $gbCount = count($file['name']);
//        $gbKeys = array_keys($file);
//        for ($gb = 0; $gb < $gbCount; $gb++):
//            foreach ($gbKeys as $Keys):
//                $gbFiles[$gb][$Keys] = $file[$Keys][$gb];
//            endforeach;
//        endfor;
        //PERCORRE A LISTA DE AQUIVOS PARA PODER FAZER O UPLOAD PARA A PASTA E CADASTRAR NO BANCO DE DADOS
//        foreach ($gbFiles as $i):
//            extract($i);
//            
//            $upload = new Upload();
//            $upload->File($i, $i['name'], "/{$dataParecer['anexo_prefeitura_id']}/{$dataParecer['anexo_protocolo']}");
//
//            $data['anexo_nome'] = $i['name'];
//            $data['anexo_formato'] = pathinfo($i['name'], PATHINFO_EXTENSION);
//            $data['anexo_link'] = $upload->getResult();
//            $data['anexo_arquivo'] = null;
//            $data['anexo_protocolo'] = $dataParecer['anexo_protocolo'];
//            $data['anexo_tipo'] = $dataParecer['anexo_tipo'];
//            $data['anexo_prefeitura_id'] = $dataParecer['anexo_prefeitura_id'];
//            $data['anexo_setor_id'] = $dataParecer['anexo_setor_id'];
//            $data['anexo_usuario_id'] = $dataParecer['anexo_usuario_id'];
//
//            $cadastra = new AdminAnexos();
//            $cadastra->ExeCreate($data);
//            if (!$cadastra->getResult()):
//                $this->Error = IMDErro('<b>Ocorreu um erro!</b><br>Não foi possível regitrar o seu anexo no sistema, Tente Novamente!', IMD_ERROR);
//            else:
//                $this->Result = $cadastra->getResult();
//            endif;
//        endforeach;
    }

//    public function gravar($file, $prefeitura, $protocolo, $parecer_id = null, $setor = null, $dataParecer) {
//        //converte cada arquivo para um indice do array
//        $gbFiles = array();
//        $gbCount = count($file['name']);
//        $gbKeys = array_keys($file);
//        for ($gb = 0; $gb < $gbCount; $gb++):
//            foreach ($gbKeys as $Keys):
//                $gbFiles[$gb][$Keys] = $file[$Keys][$gb];
//            endforeach;
//        endfor;
//        //PERCORRE A LISTA DE AQUIVOS PARA PODER FAZER O UPLOAD PARA A PASTA E CADASTRAR NO BANCO DE DADOS
//        foreach ($gbFiles as $i):
//            extract($i);
//            $nome = "SETOR{$setor}_{$i['name']}";
//
//            $upload = new Upload();
//            $upload->File($i, $nome, "/{$prefeitura}/{$protocolo}");
//
//            $data['anexo_parecer_id'] = $parecer_id;
//            $data['anexo_nome'] = $nome;
//            $data['anexo_formato'] = pathinfo($i['name'], PATHINFO_EXTENSION);
//            $data['anexo_link'] = $upload->getResult();
//            $data['anexo_arquivo'] = null;
//
//            $cadastra = new AdminAnexos();
//            $cadastra->ExeCreate($data);
//            if (!$cadastra->getResult()):
//                $this->Error = IMDErro('<b>Ocorreu um erro!</b><br>Não foi possível regitrar o seu anexo no sistema, Tente Novamente!', IMD_ERROR);
//            else:
//                $this->Result = $cadastra->getResult();
//            endif;
//        endforeach;
//    }

    public function gravarUploadConsulta($file, $dataParecer) {

        $upload = new Upload();
        $upload->File($file, $file['name'], "/{$dataParecer['anexo_prefeitura_id']}/{$dataParecer['anexo_protocolo']}");

        if ($upload->getResult()):

            $data['anexo_alvara_id'] = $dataParecer['anexo_alvara_id'];
            $data['anexo_nome'] = $file['name'];
            $data['anexo_formato'] = pathinfo($file['name'], PATHINFO_EXTENSION);
            $data['anexo_link'] = $upload->getResult();
            $data['anexo_arquivo'] = null;
            $data['anexo_protocolo'] = $dataParecer['anexo_protocolo'];
            $data['anexo_prefeitura_id'] = $dataParecer['anexo_prefeitura_id'];

            $cadastra = new AdminAnexos();
            $cadastra->ExeCreate($data);
            if (!$cadastra->getResult()):
                $this->Error = IMDErro('<b>Ocorreu um erro!</b><br>Não foi possível regitrar o seu anexo no sistema, Tente Novamente!', IMD_ERROR);
            else:
                $this->Result = [$cadastra->getResult(), $data];
            endif;
        else:
            $this->Result = false;
            $this->Error = IMDErro('<b>OPSS</b><br>Não foi possível anexar este arquivo. Verifique o tamanho do arquivo a ser enviado. Tente Novamente!', IMD_ERROR);
        endif;
    }

    public function MoveAnexo($hash, $prefeitura, $protocolo, $alvara_id = null, $setor = null) {
        $read = new Read();
        $read->ExeRead('inf_temp', "where anexo_hash = :hash", "hash={$hash}");

        if ($read->getResult()):
            $upload = new Upload();
            $upload->MoveFiles("{$prefeitura}/{$protocolo}");
            foreach ($read->getResult() as $id):
                extract($id);
                rename("../uploads{$anexo_link}", "../uploads/{$prefeitura}/{$protocolo}/{$anexo_nome}");
            endforeach;
        endif;
    }

    public function gravarImgNoticia($File, $data) {

        $name = Check::Name($File['name']);
        $name = str_replace("?", "-", $name);

        $upload = new Upload();
        $upload->File($File, "{$data['imagem_noticia_id']}-{$name}", "/Imagens/{$data['imagem_empresa_id']}/noticias");

        if ($upload->getResult()):
            $data['imagem_nome'] = $name;
            $data['imagem_host'] = LINK;
            $data['imagem_link'] = $upload->getResult();
            $cadastra = new AdminImagens();
            $cadastra->ExeCreate($data);
            if ($cadastra->getResult()):
                $this->Error = $cadastra->getError();
                $this->Result = [$cadastra->getResult(), "{$data['imagem_host']}{$data['imagem_link']}", $data['imagem_nome'], $data['imagem_prioridade']];
            else:

                $this->Result = false;
            endif;
            $this->Error = $cadastra;
        else:
            $this->Result = false;
        endif;
    }

    public function gravarImgDestaque($File, $data) {

        $name = Check::Name($File['name']);
        $name = str_replace("?", "-", $name);

        $upload = new Upload();
        $upload->File($File, "{$name}", "/{$data['imagem_empresa_id']}/imagens/destaques");

        if ($upload->getResult()):
            $data['imagem_nome'] = $name;
            $data['imagem_host'] = LINK;
            $data['imagem_link'] = $upload->getResult();
            $cadastra = new AdminImagens();
            $cadastra->ExeCreate($data);
            if ($cadastra->getResult()):
                $this->Error = $cadastra->getError();
                $this->Result = [$cadastra->getResult(), "{$data['imagem_host']}{$data['imagem_link']}", $data['imagem_nome'], $data['imagem_prioridade']];
            else:

                $this->Result = false;
            endif;
            $this->Error = $cadastra;
        else:
            $this->Result = false;
        endif;
    }

    public function gravaImg($File, $data) {

        $name = Check::Name($File['name']);
        $name = str_replace("?", "-", $name);

        $upload = new Upload();
        $upload->File($File, "{$data['id']}-{$name}", "/{$data['imagem_empresa_id']}/imagens/{$data['type']}");

        if ($upload->getResult()):

            unset($data['id']);
            unset($data['type']);

            $data['imagem_nome'] = $name;
            $data['imagem_host'] = LINK;
            $data['imagem_link'] = $upload->getResult();
            $cadastra = new AdminImagens();
            $cadastra->ExeCreate($data);
            if ($cadastra->getResult()):
                $this->Error = $cadastra->getError();
                $this->Result = [$cadastra->getResult(), "{$data['imagem_host']}{$data['imagem_link']}", $data['imagem_nome'], $data['imagem_prioridade']];
            else:

                $this->Result = false;
            endif;
            $this->Error = $cadastra;
        else:
            $this->Result = false;
        endif;
    }

    public function gravaImagem($File, $data) {



        $name = Check::Name($File['name']);
        $name = str_replace("?", "-", $name);

        $upload = new Upload();
        $upload->File($File, "{$data['id']}-{$name}", "/{$data['imagem_empresa_id']}/imagens/{$data['imagem_type']}");



        if ($upload->getResult()):
            unset($data['id']);
            unset($data['type']);

            $data['imagem_nome'] = $name;
            $data['imagem_host'] = LINK;
            $data['imagem_link'] = $upload->getResult();

            $cadastra = new AdminImagens();
            $cadastra->ExeCreate($data);
            if ($cadastra->getResult()):
                $this->Result = $cadastra->getResult();
            else:
                $this->Result = false;
            endif;
        else:
            $this->Result = false;
        endif;
    }

    public function gravaFile($File, $data) {

        $data['arquivo_data'] = date("Y-m-d H:m:s");

        $name = Check::Name($File['name']);
        $name = str_replace("?", "-", $name);

        $upload = new Upload();
        $upload->File($File, "{$data['id']}-{$name}", "{$data['arquivo_empresa_id']}/files/{$data['type']}");

        if ($upload->getResult()):
            unset($data['id']);
            unset($data['type']);
            $data['arquivo_nome'] = $name;
            $data['arquivo_host'] = LINK;
            $data['arquivo_extensao'] = pathinfo($File['name'], PATHINFO_EXTENSION);
            $data['arquivo_tamanho'] = $File['size'];
            $data['arquivo_link'] = $upload->getResult();

            $cadastra = new AdminArquivos();
            $cadastra->ExeCreate($data);
            if ($cadastra->getResult()):
                $this->Error = $cadastra->getError();
                $this->Result = [$cadastra->getResult(), "{$data['arquivo_host']}/{$data['arquivo_link']}", $data['arquivo_nome'], $data['arquivo_descricao']];
            else:
                $this->Result = false;
            endif;
            $this->Error = $cadastra;
        else:
            $this->Result = false;
        endif;
    }

    public function uploadFileGenerico($File, $path) {
        $name = Check::Name($File['name']);
        $name = str_replace("?", "-", $name);
        $upload = new Upload();
        $upload->File($File, $name, $path);
        if ($upload->getResult()):
            $this->Result = $upload->getResult();
        else:
            $this->Result = false;
        endif;
    }

    public function gravarTemp($File, $hash) {
        $name = Check::Name($File['name']);
        $name = str_replace("?", "-", $name);

        $upload = new Upload();
        $upload->File($File, "{$hash}-{$name}", "/temp/{$hash}");

        if ($upload->getResult()):
            $data['anexo_nome'] = "{$hash}-{$name}";
            $data['anexo_path'] = "/temp/{$hash}";
            $data['anexo_formato'] = pathinfo($File['name'], PATHINFO_EXTENSION);
            $data['anexo_link'] = $upload->getResult();
            $data['anexo_hash'] = $hash;
            $data['anexo_arquivo'] = null;

            $cadastra = new AdminTemp();
            $cadastra->ExeCreate($data);
            if ($cadastra->getResult()):
                $this->Error = $cadastra->getError();
                $this->Result = [$cadastra->getResult(), $data['anexo_link'], $data['anexo_nome'], $hash];
            else:
                $this->Result = false;
            endif;
        else:
            $this->Result = false;

        endif;
    }

    public function removeImg($id) {
        $read = new Read();
        $read->ExeRead('inf_imagens', 'where imagem_id = :id', "id={$id}");
        $file = $read->getResult()[0];
        if (file_exists('../uploads/' . $file['imagem_link'])):
            unlink('../uploads/' . $file['imagem_link']);
            $cadastra = new AdminImagens();
            $cadastra->ExeDelete($file['imagem_id']);
            $this->Error = $cadastra->getError();
            $this->Result = [$cadastra->getResult()];
        endif;
    }

    public function removeFile($id) {
        $read = new Read();
        $read->ExeRead('inf_arquivos', 'where arquivo_id = :id', "id={$id}");
        $file = $read->getResult()[0];
        if (file_exists('../uploads/' . $file['arquivo_link'])):
            unlink('../uploads/' . $file['arquivo_link']);
        endif;
        $cadastra = new AdminArquivos();
        $cadastra->ExeDelete($file['arquivo_id']);
        $this->Error = $cadastra->getError();
        $this->Result = [$cadastra->getResult()];
    }

    public function removeTemp($id, $hash) {
        $read = new Read();
        $read->ExeRead('inf_temp', 'where id = :id', "id={$id}");
        $file = $read->getResult()[0];
        if (file_exists('../uploads' . $file['anexo_link'])):
            unlink('../uploads' . $file['anexo_link']);
            $cadastra = new AdminTemp();
            $cadastra->ExeDelete($file['id']);
            $this->Error = $cadastra->getError();
            $this->Result = [$cadastra->getResult()];
        endif;
    }

    public function converteFile($file) {
        // Le o arquivo binario da imagem
        $data = file_get_contents($file['tmp_name']);
        // transforma em hexadecimal
        $escaped = base64_encode($data);
        var_dump($escaped);
    }

    public function gravarImg($id, $file) {
        // Le o arquivo binario da imagem
        $data = file_get_contents($file['tmp_name']);
        // transforma em hexadecimal
        $escaped = base64_encode($data);
        //chama con
        $this->con();
        // Insere no campo tipo bytea do postgress
        $result = pg_query("update countertopstore_teste.company set company_logo = '{$escaped}' where id_company = '{$id}'");
        if (!$result):
            return false;
        else:
            return true;
        endif;
    }

    public function lerImg($id) {
//        $this->con();
//        //$res = pg_query("SELECT company_logo FROM countertopstore_teste.company WHERE id_company='1'");
//        $res = pg_query("SELECT encode(company_logo, 'base64') AS company_logo FROM countertopstore_teste.company WHERE id_company='{$id}'");
//        $raw = pg_fetch_result($res, 'company_logo');
//        $imagem = base64_decode($raw);
//        return $imagem;
        $read = new Read();
        $read->FullRead("SELECT encode(company_logo, 'base64') AS company_logo FROM countertopstore_teste.company WHERE id_company='{$id}'");
        if ($read->getResult()):
            $imagem = base64_decode($read->getResult()[0]['company_logo']);
            return $imagem;
        else:
            return NULL;
        endif;
    }

    public function lerImage($id, $table, $column, $column_id) {
        $read = new Read();
        $read->FullRead("SELECT encode({$column}, 'base64') AS {$column} FROM " . SCHEMA . "{$table} WHERE {$column_id}={$id}");
        $imagem = base64_decode($read->getResult()[0]["{$column}"]);
        return $imagem;
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

    private function con() {
        $minhaConexao = pg_connect("host=" . HOST . " port=5432 dbname=" . DBSA . " user=" . USER . " password=" . PASS);
        return $minhaConexao;
    }

    //<img src='data:image/jpg;base64,<? echo $imagem 
}
