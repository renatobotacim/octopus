<?php
//VERIFICA A EXISTÊNCIA DE UM LOGIM, CASO SIM, ELE SEMPRE DIRECIONA PARA O PAINEL, CASO NÃO, DIRECIONA A TELA DE LOGIN
$checkLevel = new Login(4);
if (!$checkLevel->CheckLevel()):
    $link = 'painel.php?restrito=true'; // especifica o endereço
    redireciona($link); // chama a função
endif;
$empty = filter_input(INPUT_GET, 'empty', FILTER_VALIDATE_BOOLEAN);
if ($empty):
    IMDErro("Você tentou editar uma empresa que não existe no sistema", IMD_INFOR);
endif;
$del = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT);
if ($del):
    require ('_models/AdminMenuCompany.class.php');
    $deletar = new AdminMenuCompany();
    $deletar->ExeDelete($del);
    IMDErro($deletar->getError()[0], $deletar->getError()[1]);
endif;
//Box de estatisticas
$readContStatistc_1 = new Read;
$readContStatistc_1->ExeRead('inf_menucompanies', "WHERE menuCompany_id");
?>
<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header"><i class="fa fa-list"></i> &nbsp; Listagem de  Menus por Empresas Cadastrados</h3>
        <ol class="breadcrumb">
            <li>
                <i class="fa fa-dashboard"></i>  <a href="painel.php">Dashboard</a>
            </li>
            <li class="active">
                <i class="fa fa-university"></i> Menus por Empresas
            </li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-4 col-md-6">
        <div class="panel panel-red">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-university fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?= $readContStatistc_1->getRowCount(); ?></div>
                        <div></div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <span class="pull-left">Total de Ralacionamentos de Menus por empresas</span>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-green">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-university"></i> Menus por Empresas Cadastrados</h3>
            </div>
            <div class="panel-body">
                <div class="flot-chart">
                    <div class="flot-chart-content" id="flot-moving-line-chart">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Empresa Referente</th>
                                        <th>Menu Referente</th>
                                        <th>Data de Criação</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>    
                                <tbody>
                                    <?php
                                    $readList = new Read;
                                    $readList->FullRead('select * from inf_menucompanies');
                                    foreach ($readList->getResult() as $menuCompany_id):
                                        extract($menuCompany_id);
                                        $readCompany = new Read;
                                        $readCompany->ExeRead('inf_companies', 'WHERE companies_id = :company', "company={$menuCompany_company_id}");
                                        foreach ($readCompany->getResult() as $companies_id):
                                            extract($companies_id);
                                        endforeach;
                                        $readMenu = new Read;
                                        $readMenu->ExeRead('inf_menus', 'WHERE menu_id = :menu', "menu={$menuCompany_menu_id}");
                                        foreach ($readMenu->getResult() as $menu_id):
                                            extract($menu_id);
                                        endforeach;
                                        echo "<tr>"
                                        . "<td>{$menuCompany_id}</td>"
                                        . "<td>{$companies_name}</td>"
                                        . "<td>{$menu_name}</td>"
                                        . "<td>{$menuCompany_registration}</td>"
                                        . "<td>"
                                        . "<a class=\"btn btn-primary\" type=\"submit\" name=\"editar\" value=\"editar\" title=\"Editar\" href=\"painel.php?exe=menuCompany/update&id={$menuCompany_id}\"><i class=\"fa fa-pencil\" aria-hidden=\"true\"></i></a>&nbsp;&nbsp;"
                                        . "<a class=\"btn btn-danger\" onclick=\"return confirm('Tem certeza que deseja deletar este registro?')\" type=\"submit\" name=\"excluir\" value=\"excluir\" title=\"Excluir\" href=\"painel.php?exe=menuCompany/index&delete={$menuCompany_id}\"><i class=\"fa fa-times\" aria-hidden=\"true\"></i></a>"
                                        . "</td>"
                                        . "</tr>";
                                    endforeach;
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="form-group text-right">
    <a type="submit" class="btn btn-success" value="" name="SendAlter" href="painel.php?exe=menuCompany/create"/><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Novo Item</a>
</div>