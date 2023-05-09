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

    require_once ('../Controllers/retificacoesController.class.php');
    $controller = new retificacoesController();

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
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Licitação</label>
                                <select class="form-control form-control-sm select2" id="retificacao_licitacao_id" name="retificacao_licitacao_id">
                                    <option disabled="disabled" selected="selected" value="null"> -- Selecione -- </option>  
                                    <?php
                                    $read = new Read;
                                    $read->FullRead("select * from inf_licitacoes WHERE licitacao_empresa_id = :id_emp order by licitacao_id DESC", "id_emp={$userlogin['usuario_empresa_id']}");
                                    foreach ($read->getResult() as $licitacao_id):
                                        extract($licitacao_id);
                                        echo"<option ";
                                        if (isset($Data['retificacao_licitacao_id']) && $Data['retificacao_licitacao_id'] == $licitacao_id):
                                            echo "selected=\"selected\"";
                                        endif;
                                        echo "value=\"{$licitacao_id}\">ID: {$licitacao_id} - Número/Ano: {$licitacao_numero}/{$licitacao_ano}</option>";
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Descrição</label>
                                <textarea class="textarea summernote" name="retificacao_descricao" placeholder="Escreva aqui o conteúdo" 
                                          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"><?php if (isset($Data)) echo $Data['retificacao_descricao'] ?>
                                </textarea>
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
