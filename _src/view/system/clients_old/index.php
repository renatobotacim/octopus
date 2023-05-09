<?php
//VERIFICA A EXISTÊNCIA DE UM LOGIM, CASO SIM, ELE SEMPRE DIRECIONA PARA O PAINEL, CASO NÃO, DIRECIONA A TELA DE LOGIN
$checkLevel = new Login(2);
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
    require ('_models/AdminClients.class.php');
    $deletar = new AdminClients();
    $deletar->ExeDelete($del);
    IMDErro($deletar->getError()[0], $deletar->getError()[1]);
endif;
//Box de estatisticas
$readContStatistc_1 = new Read;
$readContStatistc_1->ExeRead('inf_clients', "WHERE clients_id and clients_companies_id = :clients_companies_id", "clients_companies_id={$userlogin['user_companies_id']}");

?>
<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header"><i class="fa fa-list"></i> &nbsp; Listagem de Clientes</h3>
        <ol class="breadcrumb">
            <li>
                <i class="fa fa-dashboard"></i>  <a href="painel.php">Dashboard</a>
            </li>
            <li class="active">
                <i class="fa fa-user"></i> Clientes
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
                <span class="pull-left">Clientes Cadastradas</span>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-green">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-university"></i> Itens Cadastrados</h3>
            </div>
            <div class="panel-body">
                <div class="flot-chart">
                    <div class="flot-chart-content" id="flot-moving-line-chart">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>level</th>
                                        <th>Link</th>
                                        <th>Logo</th>
                                        <th>status</th>
                                        <th>Data Registro</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>    
                                <tbody>
                                    <?php
                                    $readClients = new Read;
                                    $readClients->ExeRead('inf_clients', "WHERE clients_id and clients_companies_id = :clients_companies_id", "clients_companies_id={$userlogin['user_companies_id']}");
                                    foreach ($readClients->getResult() as $clients_id):
                                        extract($clients_id);
                                        if ($clients_status == "T"):
                                            $status = "Ativo";
                                        else:
                                            $status = "Inativo";
                                        endif;
                                        if (empty($clients_logo)):
                                            $img = "https://www.w3schools.com/w3images/avatar2.png";
                                        else:
                                            $img = "../admin/uploads/{$clients_logo}";
                                        endif;
                                       
                                        $readClientsLevel = new Read;
                                        $readClientsLevel->ExeRead('inf_clients_levels', "WHERE clients_levels_id = :idsssss", "idsssss={$clients_clients_level_id}");
                                        foreach ($readClientsLevel->getResult() as $clients_level_id):
                                            extract($clients_level_id);
                                            echo "<tr>"
                                            . "<td>{$clients_name}</td>"
                                            . "<td>{$clients_levels_name}</td>"
                                            . "<td>{$clients_link}</td>"
                                            . "<td><img src=\"{$img}\" width=\"80px\" heigth=\"auto\"/></td>"
                                            . "<td>{$status}</td>"
                                            . "<td>{$companies_registration}</td>"
                                            . "<td>"
                                            . "<a class=\"btn btn-primary\" type=\"submit\" name=\"editar\" value=\"editar\" title=\"Editar\" href=\"painel.php?exe=clients_old/update&id={$clients_id}\"><i class=\"fa fa-pencil\" aria-hidden=\"true\"></i></a>&nbsp;&nbsp;"
                                            . "<a class=\"btn btn-danger\" onclick=\"return confirm('Tem certeza que deseja deletar este registro?')\" type=\"submit\" name=\"excluir\" value=\"excluir\" title=\"Excluir\" href=\"painel.php?exe=clients_old/index&delete={$clients_id}\"><i class=\"fa fa-times\" aria-hidden=\"true\"></i></a>"
                                            . "</td>"
                                            . "</tr>";
                                        endforeach;
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
    <a type="submit" class="btn btn-success" value="" name="SendAlter" href="painel.php?exe=clients/create"/><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Novo Item</a>
</div>
