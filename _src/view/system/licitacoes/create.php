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

$Data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!empty($Data['sendForm'])):
    unset($Data['sendForm']);

    require_once ('../Controllers/licitacoesController.class.php');
    $controller = new licitacoesController();

    $Data['licitacao_empresa_id'] = $userlogin['usuario_empresa_id'];

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
            <form name="fileForm" action="" method="post" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Número</label>
                                <input class="form-control  form-control-sm"  name="licitacao_numero" id="licitacao_numero" type="number" value="<?php if (isset($Data)) echo $Data['licitacao_numero'] ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
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
                          <div class="col-md-2">
                            <div class="form-group">
                                <label>Abertura do certame</label>
                                <input class="form-control  form-control-sm"  name="licitacao_certame" id="licitacao_certame" type="datetime-local" value="<?php if (isset($Data)) echo $Data['licitacao_certame'] ?>">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Descrição</label>
                                <textarea name="licitacao_descricao" placeholder="Escreva aqui o conteúdo" style="width: 100%; height: 200px; font-size: 14px;border: 1px solid #dddddd; padding: 10px;"><?php if (isset($Data)) echo $Data['licitacao_descricao'] ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-md-center">
                        <div class="col-md-2">
                            <a href="painel.php?exe=<?= $navegacao[0] ?>/index" class="btn btn-danger btn-block btn-sm btn-flat"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Cancelar / Voltar</a>
                        </div>
                        <div class="col-md-2">
                            <button type="reset" value="clear" name="clear" class="btn btn-warning btn-block btn-sm btn-flat"><i class="fas fa-eraser"></i>&nbsp;Limpar Formulário</button>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" value="Cadastrar" name="sendForm" class="btn btn-success btn-sm btn-block btn-flat"><i class="fas fa-save"></i>&nbsp;Cadastrar</button>
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
