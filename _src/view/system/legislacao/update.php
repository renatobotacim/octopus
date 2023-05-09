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
    IMDErro('<b>Sucesso :D</b><br>A Legislação foi cadastrada com sucesso!', IMD_ACCEPT);
endif;

require_once ('../Controllers/legislacaoController.class.php');
$controller = new legislacaoController();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$Data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!empty($Data['SendAlter'])):
    unset($Data['SendAlter']);
    if ($_FILES['infralegal_link']['size'] > 0):
        $Data['file'] = $_FILES['infralegal_link'];
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
                    <i class="fas fa-info-circle"></i>&nbsp;Dados da Legislação</h3>
            </div>
            <form name="fileForm" action="" method="post" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Número</label>
                                <input class="form-control  form-control-sm"  name="infralegal_numero" id="infralegal_numero" type="number" value="<?php if (isset($Data)) echo $Data['infralegal_numero'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Ano</label>
                                <input class="form-control  form-control-sm"  name="infralegal_ano" id="infralegal_ano" type="number" value="<?php if (isset($Data)) echo $Data['infralegal_ano'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tipo</label>
                                <select class="form-control form-control-sm" id="infralegal_tipo" name="infralegal_tipo">
                                    <option disabled="disabled" selected="selected" value="null"> -- Selecione -- </option>  
                                    <?php
                                    $read = new Read;
                                    $read->FullRead("select * from inf_legislacao_infralegal_tipos WHERE infralegal_tipo_empresa_id = :id_emp", "id_emp={$userlogin['usuario_empresa_id']}");
                                    foreach ($read->getResult() as $infralegal_tipo_id):
                                        extract($infralegal_tipo_id);
                                        echo"<option ";
                                        if (isset($Data['infralegal_tipo']) && $Data['infralegal_tipo'] == $infralegal_tipo_id):
                                            echo "selected=\"selected\"";
                                        endif;
                                        echo "value=\"{$infralegal_tipo_id}\">{$infralegal_tipo_nome}</option>";
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Sistema</label>
                                <select class="form-control form-control-sm" id="infralegal_legislacao_sistema" name="infralegal_legislacao_sistema">
                                    <option disabled="disabled" selected="selected" value="null"> -- Selecione -- </option>   
                                    <?php
                                    $read = new Read;
                                    $read->FullRead("select * from inf_legislacao_sistema WHERE legislacao_sistema_empresa_id = :id_emp", "id_emp={$userlogin['usuario_empresa_id']}");
                                    foreach ($read->getResult() as $legislacao_sistema_id):
                                        extract($legislacao_sistema_id);
                                        echo"<option ";
                                        if (isset($Data['infralegal_legislacao_sistema']) && $Data['infralegal_legislacao_sistema'] == $legislacao_sistema_id):
                                            echo "selected=\"selected\"";
                                        endif;
                                        echo "value=\"{$legislacao_sistema_id}\">{$legislacao_sistema_nome}</option>";
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Assunto</label>
                                <textarea class="textarea summernote" name="infralegal_assunto" placeholder="Escreva aqui o conteúdo" 
                                          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?php if (isset($Data)) echo $Data['infralegal_assunto'] ?>
                                </textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <?php
                        if (!empty($Data['infralegal_link'])):
                            ?>
                            <table  class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Anexo</th>
                                    </tr>
                                </thead>
                                <tbody id="itens_arquivos">
                                <td><a class="btn btn-sm btn-sm btn-flat btn-info" href="<?= $Data['infralegal_link'] ?>" target="_blank"><i class="fas fa-eye"></i></a>&nbsp;&nbsp;&nbsp;  <?= $Data['infralegal_link'] ?> </td>
                                </tbody>
                            </table>
                            <?php
                        endif;
                        ?>


                        <div class="">
                            <label>Atualizar Anexo</label><br>
                            <input type="file" name="infralegal_link" id="infralegal_link" />
                        </div>
                    </div>

                    <div class="row mt-4 justify-content-md-center">
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
    </div>
</section>
<link rel="stylesheet"  href="dist/css/style_upload_master.css"/>
<script src="dist/js/script_upload_master.js"></script>
<script type="text/javascript">
    function newInputFile(input) {
        var filesStr = null;
        alert('2');
        document.getElementById("infralegal_link").innerHTML = filesStr;
        var filesStr = "<label><i class=\"fas fa-cloud-upload-alt\"></i>&nbsp;&nbsp;Pendente!</label><li>" + input.files[0].name + "</li>";
        document.getElementById("dp-files").innerHTML += filesStr;
        document.getElementById("btEnviar").disabled = false;
    }

    function cadastraArquivos(tipo, index) {
        //                        $("body").addClass("loading");
        var form_data = new FormData();
        form_data.append('file', $('#fileArquivos').prop('files')[0]);
        form_data.append('id', <?= $id ?>);
        form_data.append(tipo, <?= $id ?>);
        form_data.append('arquivo_descricao', $('#arquivo_descricao').val());
        form_data.append('arquivo_categoria_id', 8);
        form_data.append('arquivo_identificador', 'Licitação');
        form_data.append('arquivo_empresa_id', <?= $userlogin['usuario_empresa_id'] ?>);
        form_data.append('type', 'licitacao/' +<?= $id ?>);
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
                $('#itens_arquivos').append("<tr><td>" + obj.nome + "</td><td>" + obj.descricao + "</td><td><a href=\"http://" + obj.link + "\" target=\"_black\" class=\"btn btn-block btn-info btn-flat btn-sm\"><i class=\"fas fa-eye\"></i></a></td><td>\n\
     <button class=\"btn btn-sm btn-sm btn-flat btn-danger\" id=\"removeAnexo\" onclick=\"excluirArquivos(" + obj.id + ")\"><i class=\"fas fa-trash\"></i></button></td></tr>");
                $("#arquivo_identificador").val('');
                $("#arquivo_descricao").val('');
                //                                                    $("body").removeClass("loading");
            },
            error: function () {
                toastr.warning('<b>OPSSS!</b><br>Não foi possível anexar este arquivo. Verifique o tamanho do arquivo a ser enviado. Tente Novamente!');
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

//function carregaArquivos() {
//        var filesStr = null;
//        document.getElementById("itens_arquivos").innerHTML = filesStr;
//        var titulo = $.getJSON("../_request/getFiles.php", "id=" + <?= $id ?> + '&type=licitacao', function (data) {
//            var valor = data;
//            valor.forEach(item => {
//                $('#itens_arquivos').append("<tr><td>" + item.arquivo_nome + "</td><td>" + item.arquivo_descricao + "</td><td><a href=\"http://" + item.arquivo_host + "/" + item.arquivo_link + "\" target=\"_black\" class=\"btn btn-block btn-info btn-flat btn-sm\"><i class=\"fas fa-eye\"></i></a></td><td>\n\
//                 <botton class=\"btn btn-sm btn-sm btn-flat btn-danger\" id=\"removeAnexo\" onclick=\"excluirArquivos(" + item.arquivo_id + ")\"><i class=\"fas fa-trash\"></i></botton></td></tr>");
//            });
//        });
//    }
//    $(document).ready(function () {
//        carregaArquivos();
//    });
</script>



