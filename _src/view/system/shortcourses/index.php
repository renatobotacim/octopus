<?php
//VERIFICA A EXISTÊNCIA DE UM LOGIM, CASO SIM, ELE SEMPRE DIRECIONA PARA O PAINEL, CASO NÃO, DIRECIONA A TELA DE LOGIN
$checkLevel = new Login(2);
if (!$checkLevel->CheckLevel()):
    $link = 'painel.php?restrito=true'; // especifica o endereço
    redireciona($link); // chama a função
endif;
$empty = filter_input(INPUT_GET, 'empty', FILTER_VALIDATE_BOOLEAN);
if ($empty):
    IMDErro("Você tentou editar um minicurso que não existe no sistema", IMD_INFOR);
endif;
$update = filter_input(INPUT_GET, 'update', FILTER_DEFAULT);
if ($update == 'sucess'):
    IMDErro("<i class=\"fa fa-check\" aria-hidden=\"true\"></i><br><b>Sucesso ao atualizar!</b><br>O minicurso foi atualizado no sistema.", IMD_ACCEPT);
endif;
$del = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT);
if ($del):
    require ('_models/AdminShortcourses.class.php');
    $deletar = new AdminShortcources();
    $deletar->ExeDelete($del);
    IMDErro($deletar->getError()[0], $deletar->getError()[1]);
endif;
//Box de estatisticas
$readContStatistc_1 = new Read;
$readContStatistc_1->ExeRead('inf_shortcourses', "WHERE shortcourses_id and shortcourses_companies_id = :shortcourses_companies_id", "shortcourses_companies_id={$userlogin['user_companies_id']}");
?>
<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header"><i class="fa fa-list"></i> &nbsp; Listagem de Minicursos</h3>
        <ol class="breadcrumb">
            <li>
                <i class="fa fa-dashboard"></i>  <a href="painel.php">Dashboard</a>
            </li>
            <li class="active">
                <i class="fa fa-user"></i> Minicursos
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
                <span class="pull-left">Minicursos Cadastrados</span>
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
                                        <th>ID</th>
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
                                    $readshorcourses = new Read;
                                    $readshorcourses->ExeRead('inf_shortcourses', "WHERE shortcourses_id and shortcourses_companies_id = :shortcourses_companies_id", "shortcourses_companies_id={$userlogin['user_companies_id']}");
                                    foreach ($readshorcourses->getResult() as $shortcourses_id):
                                        extract($shortcourses_id);
                                        if (empty($shortcourses_image)):
                                            $img = "https://www.w3schools.com/w3images/avatar2.png";
                                        else:
                                            $img = "../admin/uploads/{$shortcourses_image}";
                                        endif;
                                        $dateTime = date('d/m/Y H:i ', strtotime($shortcourses_datetime));
                                        echo "<tr>"
                                        . "<td>{$shortcourses_id}</td>"
                                        . "<td>{$shortcourses_title}</td>"
                                        . "<td>{$shortcourses_responsible}</td>"
                                        . "<td>{$dateTime}</td>"
                                        . "<td>{$shortcourses_hours}</td>"
                                        . "<td><img src=\"{$img}\" width=\"80px\" heigth=\"auto\"/></td>"
                                        . "<td>{$shortcourses_btntext}</td>"
                                        . "<td>{$shortcourses_link}</td>"
                                        . "<td>"
                                        . "<a class=\"btn btn-primary\" type=\"submit\" name=\"editar\" value=\"editar\" title=\"Editar\" href=\"painel.php?exe=shortcourses/update&id={$shortcourses_id}\"><i class=\"fa fa-pencil\" aria-hidden=\"true\"></i></a>&nbsp;&nbsp;"
                                        . "<a class=\"btn btn-danger\" onclick=\"return confirm('Tem certeza que deseja deletar este registro?')\" type=\"submit\" name=\"excluir\" value=\"excluir\" title=\"Excluir\" href=\"painel.php?exe=shortcourses/index&delete={$shortcourses_id}\"><i class=\"fa fa-times\" aria-hidden=\"true\"></i></a>"
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
    <a type="submit" class="btn btn-success" value="" name="SendAlter" href="painel.php?exe=shortcourses/create"/><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Novo Item</a>
</div>