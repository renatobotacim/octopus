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

if (empty($hash)):
    $hash = date("m") . '004' . rand(10000, 99999);
endif;

$Data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!empty($Data['sendForm'])):
    unset($Data['sendForm']);

    require_once ('../Controllers/noticiasController.class.php');
    $controller = new noticiasController();

    $Data['noticia_empresa_id'] = $userlogin['usuario_empresa_id'];

    $controller->create($Data);
endif;
$icone = "fas fa-users";
require_once 'includes/navegador.php';
?>

<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">
                    <i class="fas fa-info-circle"></i>&nbsp;Dados do Serviço </h3>
            </div>
            <div class="card-body">
                <div class="p-2">
                    <div class="row">

                        <div class="col-md-6">
                            <label>Categoria do Arquivo</label>
                            <select class="form-control form-control-sm" id="arquivo_categoria_id">
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
                        <div class="col-md-12 my-2">
                            <label>Identificador</label>
                            <input class="form-control form-control-sm" type="text" id="arquivo_identificador" placeholder="Informe a Prioridade desta imagem">
                        </div>
                        <div class="col-md-12 my-2">
                            <label>Descrição</label>
                            <input class="form-control form-control-sm" type="text" id="arquivo_descricao" placeholder="Informe a Prioridade desta imagem">
                        </div>



                        <div class="area-upload" >
                            <label for="fileArquivos" class="label-upload">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <div class="texto">Clique aqui ou arraste o(s) arquivo(s) :)</div>
                            </label>
                            <input type="file"  id="fileArquivos" onchange="newInputFile(this)"/>
                            <ul id="dp-arquivos"></ul>
                        </div>

                    </div>
                    <div class="row">
                        <link rel="stylesheet"  href="dist/css/style_upload_master.css"/>
                        <script src="dist/js/script_upload_master.js"></script>
                        <script type="text/javascript">

                        </script>
                        <table  class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Link</th>
                                    <th>Prioridade</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody id="itens_imagens">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>  
        </div>
    </div>
</section>

<script type="text/javascript">
    function newInputFile(input) {
        this.cadastraArquivos();
    }

    function cadastraArquivos() {
        //                        $("body").addClass("loading");
        var form_data = new FormData();

        form_data.append('file', $('#fileArquivos').prop('files')[0]);
        form_data.append('id', <?= $id ?>);
        
//        form_data.append('id_noticia', <?= $id ?>);
//        form_data.append('type', 'noticias');

        form_data.append('arquivo_descricao', $('#arquivo_descricao').val());
        form_data.append('arquivo_identificador', $('#arquivo_identificador').val());

        if ($('#arquivo_categoria_id').val() != null && $('#arquivo_categoria_id').val() !== undefined) {
            form_data.append('arquivo_categoria_id', $('#arquivo_categoria_id').val());
        } else {
            form_data.append('arquivo_categoria_id', 15);
        }
        form_data.append('arquivo_empresa_id', <?= $userlogin['usuario_empresa_id'] ?>);

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
                $('#itens_arquivos').append("<tr><td>" + obj.descricao + "</td><td>" + obj.nome + "</td><td>" + obj.link + "</td><td>\n\
                     <botton class=\"btn btn-sm btn-sm btn-flat btn-danger\" id=\"removeAnexo\" onclick=\"excluirArquivos(" + obj.id + ")\"><i class=\"fas fa-trash\"></i></botton></td></tr>");
                $("#arquivo_categoria_id").val(0);
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

</script>
