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
    require ('_models/AdminMenu.class.php');
    $deletar = new AdminMenu();
    $deletar->ExeDelete($del);
    IMDErro($deletar->getError()[0], $deletar->getError()[1]);
endif;
//Box de estatisticas
$readContStatistc_1 = new Read;
$readContStatistc_1->ExeRead('inf_menus', "WHERE menu_id");

$navegacao = filter_input(INPUT_GET, 'exe', FILTER_DEFAULT);
$navegacao = explode("/", $navegacao);
$navegacaoRef = "Menus";
$navegacaoIco = "fa fa-list";
require_once 'includes/navegador.php';
?>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-danger">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php
                        if ($navegacao[1] == "create"): echo "Cadastro de {$navegacaoRef}";
                        elseif ($navegacao[1] == "update"): echo"Alteração de {$navegacaoRef}";
                        else: echo "Listagem de {$navegacaoRef}";
                        endif;
                        ?></h3>
                </div>
                <div class="box-body">
                    <table id="DataTebles" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Level</th>
                                <th>Icone</th>
                                <th>Navegação</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $menuDelete = null;
                            $readList = new Read;
                            $readList->FullRead('select * from inf_menus');
                            foreach ($readList->getResult() as $menu_id):
                                extract($menu_id);
                                $readLevel = new Read;
                                $readLevel->ExeRead('inf_permissoes', "WHERE permissao_id = :idpermissao", "idpermissao={$menu_permissao_id}");
                                foreach ($readLevel->getResult() as $permissao_id):
                                    extract($permissao_id);
                                endforeach;

                                echo "<tr>"
                                . "<td>{$menu_id}</td>"
                                . "<td>{$menu_nome}</td>"
                                . "<td>{$permissao_nome}</td>"
                                . "<td><i class=\"{$menu_icone}\"></i></td>"
                                . "<td>{$menu_navegacao}</td>"
                                . "<td>"
                                . "<a class=\"btn btn-primary btn-flat\" type=\"submit\" name=\"editar\" value=\"editar\" title=\"Editar\" href=\"painel.php?exe={$navegacao[0]}/update&id={$menu_id}\"><i class=\"fas fa-edit\" aria-hidden=\"true\"></i></a>&nbsp;&nbsp;"
//                                . "<button type=\"button\" class=\"btn btn-danger btn-flat\" data-toggle=\"modal\" data-target=\"#modal-warning\"><i class=\"fas fa-trash-alt\" aria-hidden=\"true\"></i></button>"
                                . "<button type=\"button\" class=\"btn btn-danger btn-flat\" data-toggle=\"modal\" data-href=\"painel.php?exe=$navegacao[0]/index&delete={$menu_id}\" data-target=\"#modal-warning\"><i class=\"fas fa-trash-alt\" aria-hidden=\"true\"></i></button>"
                                . "</td>"
                                . "</tr>";
                                $menuDelete = $menu_id;
                            endforeach;
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Level</th>
                                <th>Icone</th>
                                <th>Navegação</th>
                                <th>Ações</th>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="text-right">
                        <a type="submit" class="btn btn-success  btn-flat" value="" name="SendAlter" href="painel.php?exe=<?= $navegacao[0] ?>/create"/><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Novo Item</a>
                    </div>

                    <!--inicio do modal bolado-->
                    <div class="modal modal-warning fade" id="modal-warning">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header text-center">
                                    <h4 class="modal-title">Atenção!!!</h4>
                                </div>
                                <div class="modal-body">
                                    <p>Caro usuário, você tem certeza que deseja realmente excluir esse registro??</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline btn-flat pull-left" data-dismiss="modal">Cancelar</button>
                                    <a type="button" class="btn btn-outline btn-flat btn-ok">Confirmar Excusão</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--fim do modal bolado-->

                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
</section>





