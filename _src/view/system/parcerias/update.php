<?php
//VERIFICA A EXISTÊNCIA DE UM LOGIM, CASO SIM, ELE SEMPRE DIRECIONA PARA O PAINEL, CASO NÃO, DIRECIONA A TELA DE LOGIN
$checkLevel = new Login(1);
if (!$checkLevel->CheckLevel()):
    redireciona('painel.php?restrito=true'); // chama a função
endif;

if (!class_exists('Login')) :
    header('Location: ../../index.php');
    die;
endif;

if (filter_input(INPUT_GET, 'create', FILTER_VALIDATE_BOOLEAN)):
    IMDErro('<b>Sucesso :D</b><br>A licitação foi cadastrada com sucesso! Você agora pode acrescentar os anexos ou editar alguma informação necessária!', IMD_ACCEPT);
endif;

require_once ('../Controllers/parceriasController.class.php');
$controller = new parceriasController();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$Data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!empty($Data['SendAlter'])):
    unset($Data['SendAlter']);

    if (empty($Data['parceria_termo_fomento'])):
        $Data['parceria_termo_fomento'] = 'F';
    endif;
    if (empty($Data['parceria_termo_colaboracao'])):
        $Data['parceria_termo_colaboracao'] = 'F';
    endif;
    if (empty($Data['parceria_acordo_coperacao'])):
        $Data['parceria_acordo_coperacao'] = 'F';
    endif;
    if (empty($Data['parceria_manf_interesse_publico'])):
        $Data['parceria_manf_interesse_publico'] = 'F';
    endif;
    if (empty($Data['parceria_chamamento_publico'])):
        $Data['parceria_chamamento_publico'] = 'F';
    endif;
    $controller->updade($id, $Data, $userlogin['usuario_empresa_id']);
else:

    $Data = $controller->read($id, $userlogin['usuario_empresa_id']);
endif;


$icone = "fas fa-users";
require_once 'includes/navegador.php';
?>
<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">
                    <i class="fas fa-info-circle"></i>&nbsp;Dados da Retificação</h3>
            </div>
            <form name="fileForm" action="" method="post" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Tipo</label>
                                <br>
                                <input type="checkbox" id="1" name="parceria_termo_fomento" value="T" <?php if (isset($Data) && $Data['parceria_termo_fomento'] == 'T') echo "checked" ?>><label for="1"> &nbsp; Parceria Termo de Fomento</label><br>
                                <input type="checkbox" id="2" name="parceria_termo_colaboracao" value="T" <?php if (isset($Data) && $Data['parceria_termo_colaboracao'] == 'T') echo "checked" ?>><label for="2"> &nbsp; Parceria Termo de colaboracao</label><br>
                                <input type="checkbox" id="3" name="parceria_acordo_coperacao" value="T" <?php if (isset($Data) && $Data['parceria_acordo_coperacao'] == 'T') echo "checked" ?>><label for="3"> &nbsp; Parceria de Acordo de Coperacao</label><br>
                                <input type="checkbox" id="4" name="parceria_manf_interesse_publico" value="T" <?php if (isset($Data) && $Data['parceria_manf_interesse_publico'] == 'T') echo "checked" ?>><label for="4"> &nbsp; Parceria Manf. Interesse Público</label><br>
                                <input type="checkbox" id="5" name="parceria_chamamento_publico" value="T" <?php if (isset($Data) && $Data['parceria_chamamento_publico'] == 'T') echo "checked" ?>><label for="5">&nbsp; Parceria de Chamamento Publico</label><br>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Parceria Número</label>
                                <input class="form-control  form-control-sm"  name="parceria_numero" id="parceria_numero" type="text" value="<?php if (isset($Data)) echo $Data['parceria_numero'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Processo</label>
                                <input class="form-control  form-control-sm"  name="parceria_processo" id="parceria_processo" type="text" value="<?php if (isset($Data)) echo $Data['parceria_processo'] ?>">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Ano</label>
                                <input class="form-control  form-control-sm"  name="parceria_ano" id="parceria_ano" type="number" value="<?php if (isset($Data)) echo $Data['parceria_ano'] ?>">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Secretaria</label>
                                <input class="form-control  form-control-sm"  name="parceria_secretaria" id="parceria_secretaria" type="text" value="<?php if (isset($Data)) echo $Data['parceria_secretaria'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>OSC</label>
                                <input class="form-control  form-control-sm"  name="parceria_osc" id="parceria_osc" type="text" value="<?php if (isset($Data)) echo $Data['parceria_osc'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Valor</label>
                                <input class="form-control  form-control-sm"  name="parceria_valor" id="parceria_valor" type="text" value="<?php if (isset($Data)) echo $Data['parceria_valor'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Vigência</label>
                                <input class="form-control  form-control-sm"  name="parceria_vigencia" id="parceria_vigencia" type="text" value="<?php if (isset($Data)) echo $Data['parceria_vigencia'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Data</label>
                                <input class="form-control  form-control-sm"  name="parceria_data" id="parceria_data" type="text" value="<?php if (isset($Data)) echo $Data['parceria_data'] ?>">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label>Objeto</label>
                                <textarea class="textarea summernote" name="parceria_objeto" placeholder="Escreva aqui o conteúdo" 
                                          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?php if (isset($Data)) echo $Data['parceria_objeto'] ?>
                                </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-md-center">
                        <div class="col-md-2">
                            <a href="painel.php?exe=<?= $navegacao[0] ?>/index" class="btn btn-block btn-danger btn-flat btn-sm"><i class="fa fa-arrow-circle-left"></i>&nbsp;Cancelar / Voltar</a>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center">
                                <button type="submit" value="Cadastrar" name="SendAlter" class="btn btn-block btn-success btn-flat btn-sm"><i class="fas fa-save"></i>&nbsp;Salvar</button>
                            </div>
                        </div>
                    </div>
                </div>  
            </form>
        </div>


        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">
                    <i class="fas fa-file"></i>&nbsp;&nbsp;Editais e Extratos</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 card-body p-2">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Descrição</label>
                                <input class="form-control form-control-sm" required type="text" id="arquivo_descricao_0" placeholder="Descrição do Arquivo">
                            </div>
                            <div class="col-md-4">
                                <label>Identificador</label>
                                <input class="form-control form-control-sm" type="text" id="arquivo_identificador_0" placeholder="Identificador">
                            </div>
                            <div class="col-md-4">
                                <label>Categoria</label>
                                <select class="form-control form-control-sm" id="arquivo_categoria_id_0">
                                    <option disabled="disabled" selected="selected" value="null"> -- Selecione -- </option>   
                                    <?php
                                    $read = new Read;
                                    $read->FullRead("select * from inf_categorias WHERE categoria_empresa_id = :id_emp AND categoria_status = 2", "id_emp={$userlogin['usuario_empresa_id']}");
                                    foreach ($read->getResult() as $categoria_id):
                                        extract($categoria_id);
                                        echo"<option ";
                                        echo "value=\"{$categoria_id}\">{$categoria_nome}</option>";
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                            <div class="area-upload" >

                                <label for="fileArquivos_0" class="label-upload">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <div class="texto">Clique aqui ou arraste o(s) arquivo(s) :)</div>
                                </label>
                                <input type="file"  id="fileArquivos_0" onchange="newInputFileEditaisExtratos(this)"/>
                            </div>
                            <div class="col-md-12" >
                                <!--<button class="btn btn-block btn-sm btn-success btn-flat" type="button" id="btEnviar" ><i class="fas fa-save"></i>&nbsp;&nbsp;Anexar!</button>-->
                            </div>
                        </div>
                        <div class="row">
                            <label>Lista de Anexos</label>
                            <table  class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Descrição</th>
                                        <th>Link</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody id="itens_arquivos_0">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">
                    <i class="fas fa-file"></i>&nbsp;&nbsp;Resultado e Homologações</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 card-body p-2">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Descrição</label>
                                <input class="form-control form-control-sm" required type="text" id="arquivo_descricao_1" placeholder="Descrição do Arquivo">
                            </div>
                            <div class="col-md-4">
                                <label>Identificador</label>
                                <input class="form-control form-control-sm" type="text" id="arquivo_identificador_1" placeholder="Identificador">
                            </div>
                            <div class="col-md-4">
                                <label>Categoria</label>

                                <select class="form-control form-control-sm" id="arquivo_categoria_id_1">
                                    <option disabled="disabled" selected="selected" value="null"> -- Selecione -- </option>   
                                    <?php
                                    $read = new Read;
                                    $read->FullRead("select * from inf_categorias WHERE categoria_empresa_id = :id_emp AND categoria_status = 2", "id_emp={$userlogin['usuario_empresa_id']}");
                                    foreach ($read->getResult() as $categoria_id):
                                        extract($categoria_id);
                                        echo"<option ";
                                        echo "value=\"{$categoria_id}\">{$categoria_nome}</option>";
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                            <div class="area-upload" >
                                <label for="fileArquivos_1" class="label-upload">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <div class="texto">Clique aqui ou arraste o(s) arquivo(s) :)</div>
                                </label>
                                <input type="file"  id="fileArquivos_1" onchange="newInputFilePlanoTrabalhoHomologacoes(this)"/>
                            </div>
                            <div class="col-md-12" >
                                <!--<button class="btn btn-block btn-sm btn-success btn-flat" type="button" id="btEnviar" ><i class="fas fa-save"></i>&nbsp;&nbsp;Anexar!</button>-->
                            </div>
                        </div>
                        <div class="row">
                            <label>Lista de Anexos</label>
                            <table  class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Descrição</th>
                                        <th>Link</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody id="itens_arquivos_1">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">
                    <i class="fas fa-file"></i>&nbsp;&nbsp;Plano de Trabalho</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 card-body p-2">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Descrição</label>
                                <input class="form-control form-control-sm" required type="text" id="arquivo_descricao_2" placeholder="Descrição do Arquivo">
                            </div>
                            <div class="col-md-4">
                                <label>Identificador</label>
                                <input class="form-control form-control-sm" type="text" id="arquivo_identificador_2" placeholder="Identificador">
                            </div>
                            <div class="col-md-4">
                                <label>Categoria</label>

                                <select class="form-control form-control-sm" id="arquivo_categoria_id_2">
                                    <option disabled="disabled" selected="selected" value="null"> -- Selecione -- </option>   
                                    <?php
                                    $read = new Read;
                                    $read->FullRead("select * from inf_categorias WHERE categoria_empresa_id = :id_emp AND categoria_status = 2", "id_emp={$userlogin['usuario_empresa_id']}");
                                    foreach ($read->getResult() as $categoria_id):
                                        extract($categoria_id);
                                        echo"<option ";
                                        echo "value=\"{$categoria_id}\">{$categoria_nome}</option>";
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                            <div class="area-upload" >
                                <label for="fileArquivos_2" class="label-upload">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <div class="texto">Clique aqui ou arraste o(s) arquivo(s) :)</div>
                                </label>
                                <input type="file"  id="fileArquivos_2" onchange="newInputFilePlanoTrabalho(this)"/>
                            </div>
                            <div class="col-md-12" >
                                <!--<button class="btn btn-block btn-sm btn-success btn-flat" type="button" id="btEnviar" ><i class="fas fa-save"></i>&nbsp;&nbsp;Anexar!</button>-->
                            </div>
                        </div>
                        <div class="row">
                            <label>Lista de Anexos</label>
                            <table  class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Descrição</th>
                                        <th>Link</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody id="itens_arquivos_2">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">
                    <i class="fas fa-file"></i>&nbsp;&nbsp;Outros</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 card-body p-2">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Descrição</label>
                                <input class="form-control form-control-sm" required type="text" id="arquivo_descricao_3" placeholder="Descrição do Arquivo">
                            </div>
                            <div class="col-md-4">
                                <label>Identificador</label>
                                <input class="form-control form-control-sm" type="text" id="arquivo_identificador_3" placeholder="Identificador">
                            </div>
                            <div class="col-md-4">
                                <label>Identificador</label>

                                <select class="form-control form-control-sm" id="arquivo_categoria_id_3">
                                    <option disabled="disabled" selected="selected" value="null"> -- Selecione -- </option>   
                                    <?php
                                    $read = new Read;
                                    $read->FullRead("select * from inf_categorias WHERE categoria_empresa_id = :id_emp AND categoria_status = 2", "id_emp={$userlogin['usuario_empresa_id']}");
                                    foreach ($read->getResult() as $categoria_id):
                                        extract($categoria_id);
                                        echo"<option ";
                                        echo "value=\"{$categoria_id}\">{$categoria_nome}</option>";
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                            <div class="area-upload" >
                                <label for="fileArquivos_3" class="label-upload">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <div class="texto">Clique aqui ou arraste o(s) arquivo(s) :)</div>
                                </label>
                                <input type="file"  id="fileArquivos_3" onchange="newInputFileOutros(this)"/>
                            </div>
                            <div class="col-md-12" >
                                <!--<button class="btn btn-block btn-sm btn-success btn-flat" type="button" id="btEnviar" ><i class="fas fa-save"></i>&nbsp;&nbsp;Anexar!</button>-->
                            </div>
                        </div>
                        <div class="row">
                            <label>Lista de Anexos</label>
                            <table  class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Descrição</th>
                                        <th>Link</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody id="itens_arquivos_3">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">
                    <i class="fas fa-file"></i>&nbsp;&nbsp;Informações de Parceria</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 card-body p-2">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Descrição</label>
                                <input class="form-control form-control-sm" required type="text" id="arquivo_descricao_4" placeholder="Descrição do Arquivo">
                            </div>
                            <div class="col-md-4">
                                <label>Identificador</label>
                                <input class="form-control form-control-sm" type="text" id="arquivo_identificador_4" placeholder="Identificador">
                            </div>
                            <div class="col-md-4">
                                <label>Identificador</label>

                                <select class="form-control form-control-sm" id="arquivo_categoria_id_4">
                                    <option disabled="disabled" selected="selected" value="null"> -- Selecione -- </option>   
                                    <?php
                                    $read = new Read;
                                    $read->FullRead("select * from inf_categorias WHERE categoria_empresa_id = :id_emp AND categoria_status = 2", "id_emp={$userlogin['usuario_empresa_id']}");
                                    foreach ($read->getResult() as $categoria_id):
                                        extract($categoria_id);
                                        echo"<option ";
                                        echo "value=\"{$categoria_id}\">{$categoria_nome}</option>";
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                            <div class="area-upload" >
                                <label for="fileArquivos_4" class="label-upload">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <div class="texto">Clique aqui ou arraste o(s) arquivo(s) :)</div>
                                </label>
                                <input type="file"  id="fileArquivos_4" onchange="newInputFileInformacoesParceria(this)"/>
                            </div>
                            <div class="col-md-12" >
                                <!--<button class="btn btn-block btn-sm btn-success btn-flat" type="button" id="btEnviar" ><i class="fas fa-save"></i>&nbsp;&nbsp;Anexar!</button>-->
                            </div>
                        </div>
                        <div class="row">
                            <label>Lista de Anexos</label>
                            <table  class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Descrição</th>
                                        <th>Link</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody id="itens_arquivos_4">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
<link rel="stylesheet"  href="dist/css/style_upload_master.css"/>
<script src="dist/js/script_upload_master.js"></script>
<script type="text/javascript">
                                    function newInputFileEditaisExtratos(input) {
                                        this.cadastraArquivos('id_editaisExtratos', 0);
                                    }
                                    function newInputFilePlanoTrabalhoHomologacoes(input) {
                                        this.cadastraArquivos('id_resultadoHomologacao', 1);
                                    }
                                    function newInputFilePlanoTrabalho(input) {
                                        this.cadastraArquivos('id_planoDeTrabalho', 2);
                                    }
                                    function newInputFileOutros(input) {
                                        this.cadastraArquivos('id_outros', 3);
                                    }
                                    function newInputFileInformacoesParceria(input) {
                                        this.cadastraArquivos('id_informacoesParceria', 4);
                                    }

                                    function cadastraArquivos(tipo, index) {
                                        //                        $("body").addClass("loading");
                                        var form_data = new FormData();
                                        form_data.append('file', $('#fileArquivos_' + index).prop('files')[0]);
                                        form_data.append('id', <?= $id ?>);
                                        form_data.append(tipo, <?= $id ?>);
                                        form_data.append('arquivo_descricao', $('#arquivo_descricao_' + index).val());
                                        if ($('#arquivo_categoria_id_' + index).val() != null && $('#arquivo_categoria_id_' + index).val() !== undefined) {
                                            form_data.append('arquivo_categoria_id', $('#arquivo_categoria_id_' + index).val());
                                        } else {
                                            form_data.append('arquivo_categoria_id', 11);
                                        }
                                        form_data.append('arquivo_identificador', $('#arquivo_identificador_' + index).val());
                                        form_data.append('arquivo_empresa_id', <?= $userlogin['usuario_empresa_id'] ?>);
                                        form_data.append('type', 'parcerias/' +<?= $id ?>);
                                        $.ajax({
                                            url: '../_request/uploadFiles.php',
                                            dataType: 'text',
                                            cache: false,
                                            contentType: false,
                                            processData: false,
                                            data: form_data,
                                            type: 'post',
                                            success: function (data) {
                                                const obj = JSON.parse(data);
                                                toastr.success('<b>Sucesso!</b><br>A imagem foi incluida com sucesso!');
                                                $('#itens_arquivos_' + index).append("<tr><td>" + obj.nome + "</td><td>" + obj.descricao + "</td><td>http://" + obj.link + "</td><td>\n\
     <botton class=\"btn btn-sm btn-sm btn-flat btn-danger\" id=\"removeAnexo\" onclick=\"excluirArquivos(" + obj.id + "," + index + ")\"><i class=\"fas fa-trash\"></i></botton></td></tr>");
                                                $("#arquivo_identificador").val('');
                                                $("#arquivo_descricao").val('');
                                                //                                                    $("body").removeClass("loading");
                                            },
                                            error: function () {
                                                toastr.warning('<b>OPSSS!</b><br>Não foi possível anexar este arquivo. Verifique o tamanho do arquivo a ser enviado. Tente Novamente!');
                                            }
                                        });
                                    }

                                    function excluirArquivos(id, index) {
                                        var r = confirm("Deseja excluir esse documento?");
                                        if (r == true) {
                                            var form_data = new FormData();
                                            form_data.append('id', id);
                                            $.ajax({
                                                url: '../_request/deleteFile.php',
                                                dataType: 'text',
                                                cache: false,
                                                contentType: false,
                                                processData: false,
                                                data: form_data,
                                                type: 'post',
                                                success: function (data) {
                                                    if (data) {
                                                        toastr.success('<b>Sucesso ao deletar!</b><br>O arquivo foi apagado com sucesso!');
                                                        carregaArquivos(index);
                                                    } else {
                                                        toastr.warning('<b>OPSSS!</b><br>Aconteceu algo de errado ao apagar o arquivo, tente novamente!');
                                                    }
                                                }
                                            });
                                        }
                                    }
                                    function carregaArquivos(type) {
                                        var filesStr = null;
                                        document.getElementById("itens_arquivos_" + type).innerHTML = filesStr;
                                        var titulo = $.getJSON("../_request/getFilesParcerias.php", "id=" + <?= $id ?> + "&type=" + type, function (data) {
                                            var valor = data;
                                            valor.forEach(item => {
                                                $('#itens_arquivos_' + type).append("<tr><td>" + item.arquivo_nome + "</td><td>" + item.arquivo_descricao + "</td><td>http://" + item.arquivo_host + '/' + item.arquivo_link + "</td><td>\n\
                 <botton class=\"btn btn-sm btn-sm btn-flat btn-danger\" id=\"removeAnexo\" onclick=\"excluirArquivos(" + item.arquivo_id + ", " + type + " )\"><i class=\"fas fa-trash\"></i></botton></td></tr>");
                                            });
                                        });
                                    }
                                    $(document).ready(function () {
                                        carregaArquivos(0);
                                        carregaArquivos(1);
                                        carregaArquivos(2);
                                        carregaArquivos(3);
                                        carregaArquivos(4);
                                        $('#arquivo_categoria_id').val(11)
                                    });
</script>
