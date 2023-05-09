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

//if (empty($hash)):
//    $hash = date("m") . '004' . rand(10000, 99999);
//endif;

$Data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!empty($Data['sendForm'])):
    unset($Data['sendForm']);
//
//
//    require_once ('../Controllers/noticiasController.class.php');
//    $controller = new noticiasController();
//
//    $Data['noticia_empresa_id'] = $userlogin['usuario_empresa_id'];
//
//    $controller->create($Data);
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
            <form name="fileForm" action="" method="post" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 card-body p-2">
                            <label><i class="fas fa-cloud-upload-alt"></i>&nbsp;&nbsp;Anexar Imagem</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <input class="form-control form-control-sm my-2" required type="number" min="1" value="1" id="imagem_prioridade" placeholder="Informe a Prioridade desta imagem">
                                </div>
                                <div class="col-md-4">
                                    <input class="form-control form-control-sm my-2" type="text" id="imagem_credito" placeholder="Informe de quem é o crédito da imagem">
                                </div>
                                <div class="col-md-4">
                                    <input class="form-control form-control-sm  my-2" type="text" id="imagem_descricao" placeholder="Informe a descrição da imagem">
                                </div>

                                <div class="area-upload" >
                                    <label for="file" class="label-upload">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <div class="texto">Clique aqui ou arraste o(s) arquivo(s) :)</div>
                                    </label>
                                    <input type="file"  id="file" onchange="newInput(this)"/>
                                    <ul id="dp-files"></ul>
                                </div>
                                <div class="col-12" >
                                    <!--<button class="btn btn-block btn-sm btn-success btn-flat" type="button" id="btEnviar" ><i class="fas fa-save"></i>&nbsp;&nbsp;Anexar!</button>-->
                                </div>
                            </div>

                            <link rel="stylesheet"  href="dist/css/style_upload_master.css"/>
                            <script src="dist/js/script_upload_master.js"></script>


                            <script type="text/javascript">

                                        function newInput(input) {
                                            var filesStr = null;
                                            document.getElementById("dp-files").innerHTML = filesStr;
                                            this.cadastra();
                                        }
                                        function cadastra() {
                                            //                        $("body").addClass("loading");
                                            var form_data = new FormData();
                                            form_data.append('file', $('#file').prop('files')[0]);
                                            form_data.append('id', '');
                                            form_data.append('imagem_destaque', 2);
                                            form_data.append('imagem_descricao', $('#imagem_descricao').val());
                                            form_data.append('imagem_credito', $('#imagem_credito').val());
                                            form_data.append('imagem_prioridade', $('#imagem_prioridade').val());
                                            form_data.append('imagem_empresa_id', <?= $userlogin['usuario_empresa_id'] ?>);
                                            form_data.append('type', 'destaques');
                                            $.ajax({
                                                url: '../_request/uploadImgUpdate.php',
                                                dataType: 'text',
                                                cache: false,
                                                contentType: false,
                                                processData: false,
                                                data: form_data,
                                                type: 'post',
                                                success: function (data) {
                                                    const obj = JSON.parse(data);
                                                    toastr.success('<b>Sucesso!</b><br>A imagem foi incluida com sucesso!');
                                                    $('#itens_imagens').append("<tr><td>" + obj.nome + "</td><td>" + obj.link + "</td><td>" + obj.prioridade + "</td><td>\n\
                                            <botton class=\"btn btn-sm btn-sm btn-flat btn-danger\" id=\"removeAnexo\" onclick=\"excluir(" + obj.id + ")\"><i class=\"fas fa-trash\"></i></botton></td></tr>");
                                                    $("#imagem_prioridade").val(1);
                                                    $("#imagem_credito").val('');
                                                    $("#imagem_descricao").val('');
                                                    //                                                    $("body").removeClass("loading");
                                                },
                                                error: function () {
                                                    toastr.warning('<b>OPSSS!</b><br>Não foi possível anexar este arquivo. Verifique o tamanho do arquivo a ser enviado. Tente Novamente!');
                                                }

                                            });
                                        }

                                        function excluir(id, link) {
                                            var r = confirm("Deseja excluir esse documento?");
                                            if (r == true) {
                                                var form_data = new FormData();
                                                form_data.append('id', id);
                                                form_data.append('link', link);
                                                $.ajax({
                                                    url: '../_request/deleteImg.php',
                                                    dataType: 'text',
                                                    cache: false,
                                                    contentType: false,
                                                    processData: false,
                                                    data: form_data,
                                                    type: 'post',
                                                    success: function (data) {
                                                        if (data) {
                                                            toastr.success('<b>Sucesso ao deletar!</b><br>O arquivo foi apagado com sucesso!');
                                                            carregaImagens();
                                                        } else {
                                                            toastr.warning('<b>OPSSS!</b><br>Aconteceu algo de errado ao apagar o arquivo, tente novamente!');
                                                        }
                                                    }
                                                });
                                            }
                                        }
                                        function carregaImagens() {
                                            var filesStr = null;
                                            document.getElementById("itens_imagens").innerHTML = filesStr;
                                            var titulo = $.getJSON("../_request/getImgDestaque.php", function (data) {
                                                var valor = data;
                                                valor.forEach(item => {
                                                    $('#itens_imagens').append("<tr><td>" + item.imagem_nome + "</td><td>http://" + item.imagem_host + '/' + item.imagem_link + "</td><td>" + item.imagem_prioridade + "</td><td>\n\
                                                        <botton class=\"btn btn-sm btn-sm btn-flat btn-danger\" id=\"removeAnexo\" onclick=\"excluir(" + item.imagem_id + ")\"><i class=\"fas fa-trash\"></i></botton></td></tr>");
                                                });
                                            });
                                        }
                                        $(document).ready(function () {
                                            carregaImagens();
                                        });
                                        ;
                            </script>
                            <div class="row">
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
                    <div class="row justify-content-md-center">
                        <div class="col-md-3">
                            <a href="painel.php?exe=<?= $navegacao[0] ?>/index" class="btn btn-danger btn-block btn-sm btn-flat"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Cancelar / Voltar</a>
                        </div>
                    </div>
                </div>  
            </form>
        </div>
    </div>
</section>
<!--<script>
    function previewImage() {
        var previewBox = document.getElementById("preview");
        previewBox.src = URL.createObjectURL(event.target.files[0]);
    }
</script>-->
