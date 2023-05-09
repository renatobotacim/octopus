<?php
//VERIFICA A EXISTÊNCIA DE UM LOGIM, CASO SIM, ELE SEMPRE DIRECIONA PARA O PAINEL, CASO NÃO, DIRECIONA A TELA DE LOGIN
$checkLevel = new Login(4);
if (!$checkLevel->CheckLevel()):
    $link = 'painel.php?restrito=true'; // especifica o endereço
    redireciona($link); // chama a função
endif;

if (!class_exists('Login')) :
    header('Location: ../../index.php');
    die;
endif;

$Data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!empty($Data['sendCompanies'])):
    unset($Data['sendCompanies']);
    require ('_models/AdminCompanies.class.php');
    $cadastra = new AdminEmpresas();
    $cadastra->ExeCreate($Data);
    if (!$cadastra->getResult()):
        IMDErro($cadastra->getError()[0], $cadastra->getError()[1]);
    else:
        IMDErro($cadastra->getError()[0], $cadastra->getError()[1]);
        $link = 'painel.php?exe=companies/update&create=true&id=' . $cadastra->getResult(); // especifica o endereço
        redireciona($link); // chama a função
    endif;
endif;
$navegacao = filter_input(INPUT_GET, 'exe', FILTER_DEFAULT);
$navegacao = explode("/", $navegacao);
$navegacaoRef = "Cadastro de Empresas";
$navegacaoIco = "fas fa-university";
require_once 'includes/navegador.php';
?>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php if($navegacao[1] == "create"): echo "{$navegacaoRef}";elseif($navegacao[1] == "update"): echo"Alteração de {$navegacaoRef}"; else: echo "Listagem de {$navegacaoRef}";endif;?></h3>
                </div>
                <div class="box-body">
                    <form name="fileForm" action="" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-xs-3">
                                <label>Nome</label>
                                <input type="text" name="empresa_nome" id="empresa_nome" class="form-control" placeholder="Nome da Empresa" value="<?php if (isset($Data)) echo $Data['empresa_nome']; ?>">
                            </div>
                            <div class="col-xs-3">
                                <label>Site</label>
                                <input type="text" name="empresa_site" id="empresa_site" class="form-control" placeholder="Site da Empresa" value="<?php if (isset($Data)) echo $Data['empresa_site']; ?>">
                            </div>
                            <div class="col-xs-3">
                                <label>E-mail</label>
                                <input type="text" name="empresa_email" id="empresa_email" class="form-control" placeholder="E-mail da Empresa" value="<?php if (isset($Data)) echo $Data['empresa_email']; ?>">
                            </div>
                            <div class="col-xs-3">
                                <label>Telefone</label>
                                <input type="text" name="empresa_telefone" id="empresa_telefone" class="form-control" placeholder="Telefone da Empresa" value="<?php if (isset($Data)) echo $Data['empresa_telefone']; ?>">
                            </div>
                            <div class="col-xs-3">
                                <label>Razão Social</label>
                                <input type="text" name="empresa_razao_social" id="empresa_razao_social" class="form-control" placeholder="Razão Social da Empresa" value="<?php if (isset($Data)) echo $Data['empresa_razao_social']; ?>">
                            </div>
                        </div>
                        <br>
                        <div class="text-right">
                            <button type="submit" value="Cadastrar" name="sendMenu" class="btn btn-success btn-flat"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;Cadastrar</button>
                            <a href="painel.php?exe=menu/create" class="btn btn-info btn-flat"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Novo</a>
                            <button type="reset" class="btn btn-warning btn-flat"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Limpar Forumário</button>
                            <a href="painel.php?exe=menu/index" class="btn btn-danger btn-flat"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i>&nbsp;Voltar</a>
                        </div>
                    </form>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
</section>


<!--pra baixo-->
<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header"><i class="fa fa-university"></i> &nbsp; Cadastro de Empresas</h3>
        <ol class="breadcrumb">
            <li>
                <i class="fa fa-dashboard"></i>  <a href="painel">Dashboard</a>
            </li>
            <li>
                <i class="fa fa-university"></i> <a href="painel.php?exe=companies/index">Empresas</a>
            </li>
            <li class="active">
                <i class="fa fa-plus"></i> Cadastro
            </li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-green">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-plus"></i> Cadastro de Empresas</h3>
            </div>
            <div class="panel-body">
                <div class="flot-chart">
                    <div class="flot-chart-content" id="flot-moving-line-chart">
                        <form name="fileForm" action="" method="post" enctype="multipart/form-data">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Nome da Empresa</label>
                                    <input type="text" name="companies_name" id="companies_name" class="form-control" placeholder="Nome da empresa" value="<?php if (isset($Data)) echo $Data['companies_name']; ?>">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Site da Empresa</label>
                                    <input type="text" name="companies_link" id="companies_link" class="form-control" placeholder="Site" value="<?php if (isset($Data)) echo $Data['companies_link']; ?>">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>E-mail da Empresa</label>
                                    <input type="text" name="companies_email" id="companies_email" class="form-control" placeholder="E-mail" value="<?php if (isset($Data)) echo $Data['companies_email']; ?>">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Telefone da Empresa</label>
                                    <input type="text" name="companies_phone" id="companies_phone" class="form-control" placeholder="Insira número de telefone" value="<?php if (isset($Data)) echo $Data['companies_phone']; ?>">
                                </div>
                            </div>                                                                                                                           
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-success" value="Cadastrar" name="sendCompanies"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;Cadastrar</button>
                                <a type="submit" class="btn btn-info" value="" name="SendAlter" href="painel.php?exe=companies/create"/><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Novo</a>
                                <button type="reset" class="btn btn-warning" value="" name="SendAlter"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Limpar Forumário</button>
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

