<?php
//VERIFICA A EXISTÊNCIA DE UM LOGIM, CASO SIM, ELE SEMPRE DIRECIONA PARA O PAINEL, CASO NÃO, DIRECIONA A TELA DE LOGIN
$checkLevel = new Login(2);
if (!$checkLevel->CheckLevel()):
    redireciona('painel.php?restrito=true'); // chama a função
endif;

if (!class_exists('Login')) :
    header('Location: ../../index.php');
    die;
endif;

if (filter_input(INPUT_GET, 'create', FILTER_VALIDATE_BOOLEAN)):
    IMDErro('<b>Sucesso</b><br>O cliente foi cadastrado! Se precisar você pode atualizar os dados.', IMD_ACCEPT);
endif;

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id == 1):
    redireciona('painel.php?exe=clients/index&padrao=true'); // chama a função
endif;

require_once ('../Controllers/clientesController.class.php');
$controller = new clientesController();

//upload da imagem do cliente
//require_once ('../Controllers/uploadController.class.php');
//$uploads = new uploadController();

$Data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!empty($Data['SendAlter'])):
    unset($Data['SendAlter']);
//    if ($_FILES['media']['size']):
//        $Data['media'] = $_FILES['media'];
//    endif;
    $controller->updade($id, $Data);
else:
    $Data = $controller->read($id);
endif;

//$controllerAddress = new addressController();
//$controllerAddress->readFullByCompany($userlogin['id_company']);
//$DataAddress = $controllerAddress->getResult();
////read media
//$logo = $uploads->lerImage($id, 'jobs', 'job_media', 'id_job');

$icone = "fas fa-users";
require_once 'includes/navegador.php';
?>
<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">
                    <i class="fas fa-info-circle"></i>&nbsp;Dados do Cliente </h3>
            </div>
            <form name="fileForm" action="" method="post" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">

                        <div class="col-2">
                            <div class="form-group">
                                <label>Enquadramento</label>
                                <select class="form-control form-control-sm" id="cliente_regualacao" name="cliente_regualacao">
                                    <option <?php if (isset($Data['cliente_regualacao']) && $Data['cliente_regualacao'] == 'PF') echo 'selected' ?> value="PF">Pessoa Física</option>
                                    <option <?php if (isset($Data['cliente_regualacao']) && $Data['cliente_regualacao'] == 'PJ') echo 'selected' ?> value="PJ">Pessoa Jurídica</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nome</label>
                                <input class="form-control form-control-sm" type="text" name="cliente_nome" id="cliente_nome" value="<?php if (isset($Data)) echo $Data['cliente_nome'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Razão Social</label>
                                <input class="form-control form-control-sm" type="text" name="cliente_nome_razao" id="cliente_nome_razao" value="<?php if (isset($Data)) echo $Data['cliente_nome_razao'] ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>CPF / CNPJ</label>
                                <input class="form-control form-control-sm" type="text" data-inputmask="'mask': ['999.999.999-99', '99.999.999/9999-99']" data-mask name="cliente_cpf_cnpj" id="cliente_cpf_cnpj" value="<?php if (isset($Data)) echo $Data['cliente_cpf_cnpj'] ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>RG / Ins. Estadual</label>
                                <input class="form-control form-control-sm" type="text" name="cliente_rg_ie" id="cliente_rg_ie" value="<?php if (isset($Data)) echo $Data['cliente_rg_ie'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Email</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input class="form-control form-control-sm" type="email" name="cliente_email" id="cliente_email" value="<?php if (isset($Data)) echo $Data['cliente_email'] ?>">
                                </div>
                            </div>
                        </div>
                            <div class="col-md-3">
                            <div class="form-group">
                                <label>Celular</label>
                                <input class="form-control form-control-sm" type="text" name="cliente_celular"  data-inputmask="'mask': ['(99) 9999-9999', '(99) 99999-9999']" data-mask id="cliente_celular"  value="<?php if (isset($Data)) echo $Data['cliente_celular'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3"> 
                            <div class="form-group">
                                <label>Telefone</label>
                                <input class="form-control form-control-sm" type="tel" name="cliente_telefone" data-inputmask="'mask': ['(99) 9999-9999', '(99) 99999-9999']" data-mask id="cliente_telefone"  value="<?php if (isset($Data)) echo $Data['cliente_telefone'] ?>">
                            </div>
                        </div>
                    </div>

                    <div class="card-header"><h3 class="card-title">
                            <i class="fas fa-info-circle"></i>&nbsp;Endereço </h3>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4"> 
                            <div class="form-group">
                                <label>Logradouro</label>
                                <input class="form-control form-control-sm" type="text" name="endereco_logradouro" id="endereco_logradouro"  value="<?php if (isset($Data)) echo $Data['endereco_logradouro'] ?>">
                            </div>
                        </div>
                        <div class="col-md-2"> 
                            <div class="form-group">
                                <label>Numero</label>
                                <input class="form-control form-control-sm" type="number" name="endereco_numero" id="endereco_numero"  value="<?php if (isset($Data)) echo $Data['endereco_numero'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3"> 
                            <div class="form-group">
                                <label>Bairro</label>
                                <input class="form-control form-control-sm" type="text" name="endereco_bairro" id="endereco_bairro"  value="<?php if (isset($Data)) echo $Data['endereco_bairro'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3"> 
                            <div class="form-group">
                                <label>Complemento</label>
                                <input class="form-control form-control-sm" type="text" name="endereco_complemento" id="endereco_complemento"  value="<?php if (isset($Data)) echo $Data['endereco_complemento'] ?>">
                            </div>
                        </div>

                        <div class="col-md-3"> 
                            <div class="form-group">
                                <label>CEP</label>
                                <input class="form-control form-control-sm" type="text" data-inputmask="'mask': ['99.999-999']" data-mask name="endereco_cep" id="endereco_cep"  value="<?php if (isset($Data)) echo $Data['endereco_cep'] ?>">
                            </div>
                        </div>

                        <input type="hidden" data-mask name="endereco_id" id="endereco_cep"  value="<?php if (isset($Data)) echo $Data['endereco_id'] ?>">

                        <div class="col-md-3"> 
                            <div class="form-group">
                                <label>Estado</label>
                                <select class="form-control form-control-sm select2" id="state">
                                    <option disabled="disabled" selected="selected" value="null">Selecione</option>                                     
                                    <?php
                                    $read = new Read;
                                    $read->ExeRead('inf_estados');
                                    foreach ($read->getResult() as $estado_id):
                                        extract($estado_id);
                                        echo"<option ";
                                        if (isset($Data['state']) && $Data['state'] == $id_state):
                                            echo "selected=\"selected\"";
                                        endif;
                                        echo "value=\"{$estado_id}\"> {$estado_nome} </option>";
                                    endforeach;
                                    ?>
                                </select>


                                <script type='text/javascript'>
                                    $(document).ready(function () {
                                        var cidade = $.getJSON("../_request/city.php", "id=" + <?= $Data['endereco_cidade_id'] ?>, function (data) {
                                            var dados = data;
                                            console.log(dados[0]);

                                            $('#endereco_cidade_id').append('<option value="' + dados[0].cidade_id + '">' + dados[0].cidade_nome + '</option>');
                                            $('#state').append('<option value="' + dados[0].estado_id + '" selected>' + dados[0].estado_nome + '</option>');
                                        });


                                        $('#state').on('change', function () {
                                            $("#city").prop("disabled", false);
                                            var valor = $('#state').val();
                                            var estado = $.getJSON("../_request/cities.php", "id=" + valor, function (data) {
                                                var dados = data;
                                                $("#endereco_cidade_id").prop("disabled", false);
                                                $("#endereco_cidade_id").empty();
                                                dados.forEach(item => {
                                                    $('#endereco_cidade_id').append('<option value="' + item.cidade_id + '">' + item.cidade_nome + '</option>');
                                                });
                                            });
                                        });
                                    });
                                </script>  

                            </div>
                        </div>
                        <div class="col-md-3"> 
                            <div class="form-group">
                                <label>Cidade</label>
                                <select class="form-control form-control-sm  select2"  name="endereco_cidade_id" id="endereco_cidade_id">
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="row mt-4 justify-content-md-center">
                        <div class="col-md-2">
                            <a href="painel.php?exe=<?= $navegacao[0] ?>/index" class="btn btn-block btn-danger btn-flat btn-sm"><i class="fa fa-arrow-circle-left"></i>&nbsp;Cancelar / Voltar</a>
                        </div>
                        <div class="col-md-2">
                            <a href="painel.php?exe=<?= $navegacao[0] ?>/index" class="btn btn-info btn-block btn-flat btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Cadastrar Novo cliente</a>
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
<!--<script>
    function previewImage() {
        var previewBox = document.getElementById("preview");
        previewBox.src = URL.createObjectURL(event.target.files[0]);
    }
</script>-->
