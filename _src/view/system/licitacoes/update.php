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

require_once ('../Controllers/licitacoesController.class.php');
$controller = new licitacoesController();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$Data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (!empty($Data['SendAlter'])):
    unset($Data['SendAlter']);
    if (empty($Data['licitacao_certame'])):
        $Data['licitacao_certame'] = NULL;
    endif;
    $controller->updade($id, $Data);
else:
    $Data = $controller->read($id, $userlogin['usuario_empresa_id']);
    if (!empty($Data['licitacao_certame'])):
        $date = new DateTime($Data['licitacao_certame']);
        $Data['licitacao_certame'] = $date->format('Y-m-d\TH:i');
    endif;
endif;


$icone = "fas fa-users";
require_once 'includes/navegador.php';
?>
<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">
                    <i class="fas fa-info-circle"></i>&nbsp;Dados da Licitação</h3>
            </div>
            <form name="fileForm" action="" method="post" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Número</label>
                                <input class="form-control  form-control-sm"  name="licitacao_numero" id="licitacao_numero" type="number" value="<?php if (isset($Data)) echo $Data['licitacao_numero'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Ano</label>
                                <input class="form-control  form-control-sm"  name="licitacao_ano" id="licitacao_ano" type="number" value="<?php if (isset($Data)) echo $Data['licitacao_ano'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Modalidade</label>
                                <select class="form-control form-control-sm" id="licitacao_modalidade_id" name="licitacao_modalidade_id">
                                    <option disabled="disabled" selected="selected" value="null"> -- Selecione -- </option>  
                                    <?php
                                    $read = new Read;
                                    $read->FullRead("select * from inf_licitacoes_modalidades WHERE licitacao_modalidade_empresa_id = :id_emp", "id_emp={$userlogin['usuario_empresa_id']}");
                                    foreach ($read->getResult() as $licitacao_modalidade_id):
                                        extract($licitacao_modalidade_id);
                                        echo"<option ";
                                        if (isset($Data['licitacao_modalidade_id']) && $Data['licitacao_modalidade_id'] == $licitacao_modalidade_id):
                                            echo "selected=\"selected\"";
                                        endif;
                                        echo "value=\"{$licitacao_modalidade_id}\">{$licitacao_modalidade_nome}</option>";
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Situação</label>
                                <select class="form-control form-control-sm" id="licitacao_situacao_id" name="licitacao_situacao_id">
                                    <option disabled="disabled" selected="selected" value="null"> -- Selecione -- </option>   
                                    <?php
                                    $read = new Read;
                                    $read->FullRead("select * from inf_licitacoes_situacao WHERE licitacao_situacao_empresa_id = :id_emp", "id_emp={$userlogin['usuario_empresa_id']}");
                                    foreach ($read->getResult() as $licitacao_situacao_id):
                                        extract($licitacao_situacao_id);
                                        echo"<option ";
                                        if (isset($Data['licitacao_situacao_id']) && $Data['licitacao_situacao_id'] == $licitacao_situacao_id):
                                            echo "selected=\"selected\"";
                                        endif;
                                        echo "value=\"{$licitacao_situacao_id}\">{$licitacao_situacao_nome}</option>";
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Abertura do certame</label>
                                <input class="form-control  form-control-sm"  name="licitacao_certame" id="licitacao_certame" type="datetime-local" value="<?php if (isset($Data['licitacao_certame'])) echo $Data['licitacao_certame'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control form-control-sm" id="licitacao_status" name="licitacao_status">
                                    <option disabled="disabled" selected="selected" value="null"> -- Selecione -- </option>   
                                    <option <?php if (isset($Data['licitacao_status']) && $Data['licitacao_status'] == 1) echo 'selected' ?> value="1">ATIVA</option>
                                    <option <?php if (isset($Data['licitacao_status']) && $Data['licitacao_status'] == 0) echo 'selected' ?> value="0">INATIVA</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Descrição</label>
                                <textarea name="licitacao_descricao" placeholder="Escreva aqui o conteúdo" style="width: 100%; height: 80px; font-size: 14px; border: 1px solid #dddddd; padding: 10px"><?php if (isset($Data)) echo $Data['licitacao_descricao'] ?></textarea>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label>Resultado</label>
                                <textarea name="licitacao_resultado" placeholder="Escreva aqui o conteúdo"style="width: 100%; height: 80px; font-size: 14px;border: 1px solid #dddddd; padding: 10px"><?php if (isset($Data)) echo $Data['licitacao_resultado'] ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card card-primary card-outline " style="background-color: #f5f5f5">
                        <div class="card-header"><h3 class="card-title">
                                <i class="fas fa-file"></i>&nbsp;&nbsp;Anexos</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label>Descrição</label>
                                            <input class="form-control form-control-sm" type="text" id="arquivo_descricao" placeholder="Descrição do Arquivo">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label>Ordem do arquivo</label>
                                            <input class="form-control form-control-sm" type="number" id="arquivo_ordem" placeholder="Ordem">
                                        </div>
                                        <div class="area-upload mb-5" >
                                            <label for="fileArquivos" class="label-upload">
                                                <i class="fas fa-cloud-upload-alt"></i>
                                                <div class="texto">Clique aqui ou arraste o arquivo :)</div>
                                            </label>
                                            <input type="file"  id="fileArquivos" onchange="newInputFile(this)"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label>Lista de Anexos</label>
                                        <table  class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Nome do Arquivo</th>
                                                    <th>Identificador</th>
                                                    <th>Descrição do Arquivo (A QUE APARECE NO SITE)</th>
                                                    <th>Ordem</th>
                                                    <th>Link</th>
                                                    <th>Ação</th>
                                                </tr>
                                            </thead>
                                            <tbody id="itens_arquivos">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-md-center my-5">
                        <div class="col-md-2">
                            <a href="painel.php?exe=<?= $navegacao[0] ?>/index" class="btn btn-block btn-danger btn-flat btn-sm"><i class="fa fa-arrow-circle-left"></i>&nbsp;Voltar</a>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center">
                                <button type="submit" value="Cadastrar" name="SendAlter" class="btn btn-block btn-success btn-flat btn-sm"><i class="fas fa-save"></i>&nbsp;&nbsp;Salvar</button>
                            </div>
                        </div>
                    </div>
                </div>  
            </form>
        </div>
    </div>

    <div class="modal fade" id="update-file">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Propriedades do Arquivo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form name="files" enctype="multipart/form-data">
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label>ID do arquivo</label>
                                <input class="form-control form-control-sm" name="arquivo_id" disabled type="text" id="arquivo_id_up" >
                            </div>
                            <div class="col-md-9 mb-3">
                                <label>Nome do arquivo</label>
                                <input class="form-control form-control-sm" disabled type="text" id="arquivo_nome_up" placeholder="Nome do Arquivo">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Identificador</label>
                                <input class="form-control form-control-sm" type="text" id="arquivo_identificador_up" placeholder="Identificador do Arquivo">
                            </div>
                            <div class="col-md-9 mb-3">
                                <label>Descrição</label>
                                <input class="form-control form-control-sm" type="text" id="arquivo_descricao_up" placeholder="Descrição do Arquivo">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Ordem do arquivo</label>
                                <input class="form-control form-control-sm" type="number" step="0.1" id="arquivo_ordem_up" placeholder="Ordem">
                            </div>
                        </div>
                        <div class="row justify-content-md-center my-5">
                            <div class="col-md-2">
                                <button type="button" data-dismiss="modal" class="btn btn-block btn-danger btn-flat btn-sm"><i class="fa fa-arrow-circle-left"></i>&nbsp;Voltar</button>
                            </div>
                            <div class="col-md-2">
                                <div class="text-center">
                                    <button type="submit" value="Cadastrar" name="SendAlterFile" class="btn btn-block btn-success btn-flat btn-sm"><i class="fas fa-save"></i>&nbsp;Salvar</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

</section>
<link rel="stylesheet"  href="dist/css/style_upload_master.css"/>
<script src="dist/js/script_upload_master.js"></script>
<script type="text/javascript">

                                                $(function () {
                                                    $('form[name="files"]').submit(function (event) {
                                                        event.preventDefault();
                                                        var form_data = new FormData();
                                                        form_data.append('id', $('#arquivo_id_up').val());
                                                        form_data.append('arquivo_nome', $('#arquivo_nome_up').val());
                                                        form_data.append('arquivo_identificador', $('#arquivo_identificador_up').val());
                                                        form_data.append('arquivo_descricao', $('#arquivo_descricao_up').val());
                                                        if ($('#arquivo_ordem_up').val()) {
                                                            form_data.append('arquivo_ordem', $('#arquivo_ordem_up').val());
                                                        }

                                                        $.ajax({
                                                            url: '../_request/updateFiles.php',
                                                            dataType: 'json',
                                                            cache: false,
                                                            contentType: false,
                                                            processData: false,
                                                            data: form_data,
                                                            type: 'post',
                                                            success: function (data) {
                                                                toastr.success('<b>Sucesso!</b><br>' + data.message);
                                                                carregaArquivos();
                                                            },
                                                            error: function (data) {
                                                                toastr.warning('<b>OPSSS!</b><br>' + data.message);
                                                            }
                                                        });
                                                    });
                                                });
                                                function newInputFile(input) {
                                                    this.cadastraArquivos('id_licitacao');

                                                }

                                                function cadastraArquivos(tipo, index) {
                                                    $("body").addClass("loading");
                                                    var form_data = new FormData();
                                                    form_data.append('id', <?= $id ?>);
                                                    form_data.append(tipo, <?= $id ?>);
                                                    form_data.append('arquivo_descricao', $('#arquivo_descricao').val());
                                                    form_data.append('arquivo_categoria_id', 8);
                                                    form_data.append('arquivo_identificador', 'Licitação');
                                                    form_data.append('arquivo_ordem', $('#arquivo_ordem').val());
                                                    form_data.append('arquivo_empresa_id', <?= $userlogin['usuario_empresa_id'] ?>);
                                                    form_data.append('type', 'licitacao/' +<?= $id ?>);
                                                    form_data.append('file', $('#fileArquivos').prop('files')[0]);
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
                                                            console.log(obj);
                                                            toastr.success('<b>Sucesso!</b><br>O Upload do arquivo foi realizado com sucesso! Se o arquivo não aparecer na listagem abaixo, salve as instruições e atualize a página.');
                                                            $("#arquivo_identificador").val('');
                                                            $("#arquivo_descricao").val('');
                                                            $("#arquivo_ordem").val('');
                                                            $("#fileArquivos").val('');
                                                            $("body").removeClass("loading");
                                                            carregaArquivos();
                                                        },
                                                        error: function () {
                                                            toastr.warning('<b>OPSSS!</b><br>Não foi possível realizar o upload do arquivo. Verfique se as informações estão corretas');
                                                        }
                                                    });
                                                }

                                                function excluirArquivos(id) {
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
                                                                    carregaArquivos();
                                                                } else {
                                                                    toastr.warning('<b>OPSSS!</b><br>Aconteceu algo de errado ao apagar o arquivo, tente novamente!');
                                                                }
                                                            }
                                                        });
                                                    }
                                                }

                                                function editarArquivos(id) {
                                                    var titulo = $.getJSON("../_request/getFile.php", "id=" + id, function (data) {
                                                        var valor = data;
                                                        valor.forEach(item => {
                                                            $("#arquivo_ordem_up").val(item.arquivo_ordem);
                                                            $("#arquivo_id_up").val(item.arquivo_id);
                                                            $("#arquivo_descricao_up").val(item.arquivo_descricao);
                                                            $("#arquivo_identificador_up").val(item.arquivo_identificador);
                                                            $("#arquivo_nome_up").val(item.arquivo_nome);
//                                                 
                                                        });
                                                    });
                                                }

                                                function carregaArquivos() {
                                                    var filesStr = null;
                                                    document.getElementById("itens_arquivos").innerHTML = filesStr;
                                                    var titulo = $.getJSON("../_request/getFiles.php", "id=" + <?= $id ?> + '&type=licitacao', function (data) {
                                                        var valor = data;
                                                        valor.forEach(item => {
                                                            $('#itens_arquivos').append("<tr><td>" + item.arquivo_nome + "</td><td>" + item.arquivo_identificador + "</td><td>" + item.arquivo_descricao + "</td><td>" + item.arquivo_ordem + "</td><td><a href=\"http://" + item.arquivo_host + "/" + item.arquivo_link + "\" target=\"_black\" class=\"btn btn-block btn-info btn-flat btn-sm\"><i class=\"fas fa-eye\"></i></a></td>\n\
                                                <td>\n\
                                                <botton class=\"btn btn-sm btn-sm btn-flat btn-action  btn-primary\" id=\"removeAnexo\" data-toggle=\"modal\" data-target=\"#update-file\" onclick=\"editarArquivos(" + item.arquivo_id + ")\"><i class=\"fas fa-edit\"></i></botton>\n\
                                                <botton class=\"btn btn-sm btn-sm btn-flat btn-action  btn-danger\" id=\"removeAnexo\" onclick=\"excluirArquivos(" + item.arquivo_id + ")\"><i class=\"fas fa-trash\"></i></botton>\n\
                                                </td></tr>");
                                                        });
                                                    });
                                                }
                                                $(document).ready(function () {
                                                    carregaArquivos();
                                                });
</script>



