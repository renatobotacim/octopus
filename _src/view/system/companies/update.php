<?php
//VERIFICA A EXISTÊNCIA DE UM LOGIM, CASO SIM, ELE SEMPRE DIRECIONA PARA O PAINEL, CASO NÃO, DIRECIONA A TELA DE LOGIN
$checkLevel = new Login(4);
if (!$checkLevel->CheckLevel()):
    $link = 'painel.php?restrito=true'; // especifica o endereço
    redireciona($link); // chama a função
endif;
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
$Data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!empty($Data['SendAlter'])):
    unset($Data['SendAlter']);
    require ('_models/AdminEmpresas.class.php');
    $cadastra = new AdminEmpresas();
    $cadastra->ExeUpdate($id, $Data);
    IMDErro($cadastra->getError()[0], $cadastra->getError()[1]);
else:
    $read = new Read();
    $read->ExeRead("inf_empresas", "WHERE empresa_id = :id", "id={$id}");
    if (!$read->getResult()):
        header('Location:painel.php?exe=companies/index&empty=true');
    else:
        $Data = $read->getResult()[0];
    endif;
endif;
$checkCreate = filter_input(INPUT_GET, 'create', FILTER_VALIDATE_BOOLEAN);
if ($checkCreate):
    IMDErro("<i class=\"fa fa-check\" aria-hidden=\"true\"></i><br><b>Sucesso ao Cadastrar!</b><br>A empresa <b>{$Data['companies_name']}</b> foi criada com sucesso no sistema! <br>Continue atualizando a mesma.", IMD_ACCEPT);
endif;
?>
<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header"><i class="fa fa-pencil-square-o"></i> &nbsp; Atualizaçao Cadastral da Empresa: <?= $Data['empresa_nome'] ?></h3>
        <ol class="breadcrumb">
            <li>
                <i class="fa fa-dashboard"></i>  <a href="painel">Dashboard</a>
            </li>
            <li>
                <i class="fa fa-university"></i> <a href="painel.php?exe=companies/index">Empresas</a>
            </li>
            <li class="active">
                <i class="fa fa-pencil-square-o"></i> Atualização
            </li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-green">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-university"></i>&nbsp;<?= $Data['empresa_nome'] ?></h3>
            </div>
            <div class="panel-body">
                <div class="flot-chart">
                    <div class="flot-chart-content" id="flot-moving-line-chart">
                        <form name="fileForm" action="" method="post" enctype="multipart/form-data">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Nome da Empresa</label>
                                    <input type="text" name="empresa_nome" id="empresa_nome" class="form-control" placeholder="Nome da empresa" value="<?php if (isset($Data)) echo $Data['empresa_nome']; ?>">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Site da Empresa</label>
                                    <input type="text" name="empresa_site" id="empresa_site" class="form-control" placeholder="Site" value="<?php if (isset($Data)) echo $Data['empresa_site']; ?>">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>E-mail da Empresa</label>
                                    <input type="text" name="empresa_email" id="empresa_email" class="form-control" placeholder="E-mail" value="<?php if (isset($Data)) echo $Data['empresa_email']; ?>">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Telefone da Empresa</label>
                                    <input type="text" name="empresa_telefone" id="empresa_telefone" class="form-control" placeholder="Insira o número de telefone" value="<?php if (isset($Data)) echo $Data['empresa_telefone']; ?>">
                                </div>
                            </div>                                                                                                                           
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-success" value="Salvar" name="SendAlter"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;Salvar</button>
                                <a type="submit" class="btn btn-info" value="" name="SendAlter" href="painel.php?exe=companies/create"/><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Novo</a>
                                <a type="submit" class="btn btn-danger" value="" name="SendAlter" href="painel.php?exe=companies/index"/><i class="fa fa-arrow-circle-left" aria-hidden="true"></i>&nbsp;Voltar</a>
                            </div>
                        </form>  
                    </div>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>