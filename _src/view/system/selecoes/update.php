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
    IMDErro('<b>Sucesso :D</b><br>A notícia foi cadastrada com sucesso! Você agora está na tela de atualização, então, pode continuar editando a notícia!', IMD_ACCEPT);
endif;

require_once ('../Controllers/selecoesController.class.php');
$controller = new selecoesController();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$Data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!empty($Data['SendAlter'])):
    unset($Data['SendAlter']);
    $controller->updade($id, $Data);
else:
    $Data = $controller->read($id, $userlogin['usuario_empresa_id']);
endif;
if (empty($Data['noticia_data_publicacao'])):
    $Data['noticia_data_publicacao'] = date('d-m-Y');
endif;

$icone = "fas fa-users";
require_once 'includes/navegador.php';
?>
<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">
                    <i class="fas fa-info-circle"></i>&nbsp;Dados do Processo Seletivo</h3>
            </div>
            <form name="fileForm" action="" method="post" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tipo</label>
                                <select class="form-control form-control-sm" id="processo_seletivo_tipo" name="processo_seletivo_tipo">
                                    <option disabled="disabled" selected="selected" value="null"> -- Selecione -- </option>   
                                    <option <?php if ($Data['processo_seletivo_tipo'] == 1) echo 'selected' ?>  value="1">Processo Seletivo</option>   
                                    <option <?php if ($Data['processo_seletivo_tipo'] == 2) echo 'selected' ?>  value="2">Concurso Público</option>   
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Número</label>
                                <input class="form-control  form-control-sm"  name="processo_seletivo_numero" id="processo_seletivo_numero" type="number" value="<?php if (isset($Data)) echo $Data['processo_seletivo_numero'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Ano</label>
                                <input class="form-control  form-control-sm"  name="processo_seletivo_ano" id="processo_seletivo_ano" type="number" value="<?php if (isset($Data)) echo $Data['processo_seletivo_ano'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Categoria</label>
                                <select class="form-control form-control-sm" id="processo_seletivo_categoria_id" name="processo_seletivo_categoria_id">
                                    <?php
                                    $read = new Read;
                                    $read->FullRead("select * from inf_categorias as c where c.categoria_empresa_id = :id_emp AND c.categoria_status = 2", "id_emp={$userlogin['usuario_empresa_id']}");
                                    foreach ($read->getResult() as $categoria_id):
                                        extract($categoria_id);
                                        echo"<option ";
                                        if (isset($Data['processo_seletivo_categoria_id']) && $Data['processo_seletivo_categoria_id'] == $categoria_id):
                                            echo "selected=\"selected\"";
                                        endif;
                                        echo "value=\"{$categoria_id}\">{$categoria_nome} </option>";
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Link da Ferramenta</label>
                                <input class="form-control form-control-sm" type="text" name="processo_seletivo_link" id="processo_seletivo_link" value="<?php if (isset($Data)) echo $Data['processo_seletivo_link'] ?>">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label>Descrição</label>
                                <textarea class="textarea summernote" name="processo_seletivo_descricao" placeholder="Escreva aqui o conteúdo" 
                                          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?php if (isset($Data)) echo $Data['processo_seletivo_descricao'] ?>
                                </textarea>
                            </div>
                        </div>




                    </div>
                    <div class="row justify-content-md-center">
                        <div class="col-md-2">
                            <a href="painel.php?exe=<?= $navegacao[0] ?>/index" class="btn btn-block btn-danger btn-flat btn-sm"><i class="fa fa-arrow-circle-left"></i>&nbsp;Cancelar / Voltar</a>
                        </div>
                        <div class="col-md-2">
                            <a href="painel.php?exe=<?= $navegacao[0] ?>/create" class="btn btn-info btn-block btn-flat btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Novo Serviço</a>
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
                    <i class="fas fa-file"></i>&nbsp;&nbsp;Arquivos referentes ao Edital de Abertura</h3>
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
                                <label>Identificador</label>
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
                                <input type="file"  id="fileArquivos_0" onchange="newInputFileAbertura(this)"/>
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
                    <i class="fas fa-file"></i>&nbsp;&nbsp;Outros Arquivos</h3>
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
                                <label>Identificador</label>

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
                                <input type="file"  id="fileArquivos_1" onchange="newInputFileOutros(this)"/>
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
                    <i class="fas fa-file"></i>&nbsp;&nbsp;Resultados e Classificação</h3>
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
                                <label>Identificador</label>

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
                                <input type="file"  id="fileArquivos_2" onchange="newInputFileResultados(this)"/>
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
                    <i class="fas fa-file"></i>&nbsp;&nbsp;Convocações</h3>
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
                                <input type="file"  id="fileArquivos_3" onchange="newInputFileConvocacoes(this)"/>
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



    </div>
</section>
<link rel="stylesheet"  href="dist/css/style_upload_master.css"/>
<script src="dist/js/script_upload_master.js"></script>
<script type="text/javascript">
                                    function newInputFileAbertura(input) {
                                        this.cadastraArquivos('id_processo_seletivo_concurso_edital_abertura', 0);
                                    }
                                    function newInputFileOutros(input) {
                                        this.cadastraArquivos('id_processo_seletivo_concurso_edital_outros', 1);
                                    }
                                    function newInputFileResultados(input) {
                                        this.cadastraArquivos('id_processo_seletivo_concurso_edital_resultado', 2);
                                    }
                                    function newInputFileConvocacoes(input) {
                                        this.cadastraArquivos('id_processo_seletivo_concurso_edital_convocacao', 3);
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
                                        form_data.append('type', 'concursos/' +<?= $id ?>);
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
                                        var titulo = $.getJSON("../_request/getFilesSelecoes.php", "id=" + <?= $id ?> + "&type=" + type, function (data) {
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
                                        $('#arquivo_categoria_id').val(11)
                                    });
</script>

