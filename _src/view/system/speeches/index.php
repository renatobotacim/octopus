<?php
//VERIFICA A EXISTÊNCIA DE UM LOGIM, CASO SIM, ELE SEMPRE DIRECIONA PARA O PAINEL, CASO NÃO, DIRECIONA A TELA DE LOGIN
$checkLevel = new Login(2);
if (!$checkLevel->CheckLevel()):
    $link = 'painel.php?restrito=true'; // especifica o endereço
    redireciona($link); // chama a função
endif;
$empty = filter_input(INPUT_GET, 'empty', FILTER_VALIDATE_BOOLEAN);
if ($empty):
    IMDErro("Você tentou editar um palestra que não existe no sistema", IMD_INFOR);
endif;
$update = filter_input(INPUT_GET, 'update', FILTER_DEFAULT);
if ($update == 'sucess'):
    IMDErro("<i class=\"fa fa-check\" aria-hidden=\"true\"></i><br><b>Sucesso ao atualizar!</b><br>O palestra foi atualizado no sistema.", IMD_ACCEPT);
endif;
$del = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT);
if ($del):
    require ('_models/AdminSpeeches.class.php');
    $deletar = new AdminSpeeches();
    $deletar->ExeDelete($del);
    IMDErro($deletar->getError()[0], $deletar->getError()[1]);
endif;
//Box de estatisticas
$readContStatistc_1 = new Read;
$readContStatistc_1->ExeRead('inf_speeches', "WHERE speeches_id and speeches_companies_id = :speeches_companies_id", "speeches_companies_id={$userlogin['user_companies_id']}");
?>
<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header"><i class="fa fa-list"></i> &nbsp; Listagem de Palestras</h3>
        <ol class="breadcrumb">
            <li>
                <i class="fa fa-dashboard"></i>  <a href="painel.php">Dashboard</a>
            </li>
            <li class="active">
                <i class="fa fa-user"></i> Palestras
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
                <span class="pull-left">Palestras Cadastrados</span>
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
                                        <th>Título</th>
                                        <th>Responsável</th>
                                        <th>Data e Hora</th>
                                        <th>Carga horária</th>
                                        <th>Banner</th>
                                        <th>Texto do Botão</th>
                                        <th>Link do Botão</th>
                                    </tr>
                                </thead>    
                                <tbody>
                                    <?php
                                    $readspeeches = new Read;
                                    $readspeeches->ExeRead('inf_speeches', "WHERE speeches_id and speeches_companies_id = :speeches_companies_id", "speeches_companies_id={$userlogin['user_companies_id']}");
                                    foreach ($readspeeches->getResult() as $speeches_id):
                                        extract($speeches_id);
                                        if (empty($speeches_image)):
                                            $img = "https://www.w3schools.com/w3images/avatar2.png";
                                        else:
                                            $img = "../admin/uploads/{$speeches_image}";
                                        endif;
                                        $dateTime = date('d/m/Y H:i ', strtotime($speeches_datetime));
                                        echo "<tr>"
                                        . "<td>{$speeches_title}</td>"
                                        . "<td>{$speeches_responsible}</td>"
                                        . "<td>{$dateTime}</td>"
                                        . "<td>{$speeches_hours}</td>"
                                        . "<td><img src=\"{$img}\" width=\"80px\" heigth=\"auto\"/></td>"
                                        . "<td>{$speeches_btntext}</td>"
                                        . "<td>{$speeches_link}</td>"
                                        . "<td>"
                                        . "<a class=\"btn btn-primary\" type=\"submit\" name=\"editar\" value=\"editar\" title=\"Editar\" href=\"painel.php?exe=speeches/update&id={$speeches_id}\"><i class=\"fa fa-pencil\" aria-hidden=\"true\"></i></a>&nbsp;&nbsp;"
                                        . "<a class=\"btn btn-danger\" onclick=\"return confirm('Tem certeza que deseja deletar este registro?')\" type=\"submit\" name=\"excluir\" value=\"excluir\" title=\"Excluir\" href=\"painel.php?exe=speeches/index&delete={$speeches_id}\"><i class=\"fa fa-times\" aria-hidden=\"true\"></i></a>"
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
    <a type="submit" class="btn btn-success" value="" name="SendAlter" href="painel.php?exe=speeches/create"/><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Novo Item</a>
</div>