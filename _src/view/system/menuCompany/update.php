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
    require ('_models/AdminMenuCompany.class.php');
    $cadastra = new AdminMenuCompany();
    $cadastra->ExeUpdate($id, $Data);
    IMDErro($cadastra->getError()[0], $cadastra->getError()[1]);
else:
    $read = new Read();
    $read->ExeRead("inf_menucompanies", "WHERE menuCompany_id = :id", "id={$id}");
    if (!$read->getResult()):
        header('Location:painel.php?exe=menuCompany/index&empty=true');
    else:
        $Data = $read->getResult()[0];
    endif;
endif;
$checkCreate = filter_input(INPUT_GET, 'create', FILTER_VALIDATE_BOOLEAN);
if ($checkCreate):
    IMDErro("<i class=\"fa fa-check\" aria-hidden=\"true\"></i><br><b>Sucesso ao Cadastrar!</b><br>O menu foi associado a empresa com sucesso no sistema! <br>Continue atualizando a mesma.", IMD_ACCEPT);
endif;
?>
<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header"><i class="fa fa-pencil-square-o"></i> &nbsp; Atualizaçao Cadastral do menus por Empresas</h3>
        <ol class="breadcrumb">
            <li>
                <i class="fa fa-dashboard"></i>  <a href="painel">Dashboard</a>
            </li>
            <li>
                <i class="fa fa-university"></i> <a href="painel.php?exe=menuCompany/index">Menus por Empresas</a>
            </li>
            <li class="active">
                <i class="fa fa-pencil-square-o"></i> Atualizaçao
            </li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-green">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-university"></i>&nbsp;Atualizaçao Cadastral do menus por Empresas</h3>
            </div>
            <div class="panel-body">
                <div class="flot-chart">
                    <div class="flot-chart-content" id="flot-moving-line-chart">
                        <form name="fileForm" action="" method="post" enctype="multipart/form-data">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Empresa Referência </label>
                                    <select id="menuCompany_company_id" class="form-control chzn-select" name="menuCompany_company_id">
                                        <option disabled="disabled" selected="selected" value="null">Selecione</option>                                     
                                        <?php
                                        $readEmpresas = new Read;
                                        $readEmpresas->ExeRead("inf_companies", "WHERE companies_id");
                                        foreach ($readEmpresas->getResult() as $companies_id):
                                            extract($companies_id);
                                            echo"<option ";
                                            if ($Data['menuCompany_company_id'] == $companies_id):
                                                echo "selected=\"selected\"";
                                            endif;
                                            echo "value=\"{$companies_id}\"> {$companies_name} </option>";
                                        endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Menu Referencia</label>
                                    <select id="menuCompany_menu_id" class="form-control chzn-select" name="menuCompany_menu_id">
                                        <option disabled="disabled" selected="selected" value="null">Selecione</option>                                     
                                        <?php
                                        $readMenu = new Read;
                                        $readMenu->ExeRead("inf_menus", "WHERE menu_id");
                                        foreach ($readMenu->getResult() as $menu_id):
                                            extract($menu_id);
                                            echo"<option ";
                                            if ($Data['menuCompany_menu_id'] == $menu_id):
                                                echo "selected=\"selected\"";
                                            endif;
                                            echo "value=\"{$menu_id}\"> {$menu_name} </option>";
                                        endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>                                                                                                                           
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-success" value="Salvar" name="SendAlter"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;Salvar</button>
                                <a type="submit" class="btn btn-info" value="" name="SendAlter" href="painel.php?exe=menuCompany/create"/><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Novo</a>
                                <a type="submit" class="btn btn-danger" value="" name="SendAlter" href="painel.php?exe=menuCompany/index"/><i class="fa fa-arrow-circle-left" aria-hidden="true"></i>&nbsp;Voltar</a>
                            </div>
                        </form>  
                    </div>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>