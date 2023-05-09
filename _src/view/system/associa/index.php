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
    require ('_models/AdminAssociacao.class.php');
    $deletar = new AdminAssociacao();
    $deletar->ExeDelete($del);
    IMDErro($deletar->getError()[0], $deletar->getError()[1]);
endif;

$navegacao = filter_input(INPUT_GET, 'exe', FILTER_DEFAULT);
$navegacao = explode("/", $navegacao);
$navegacaoRef = "Associação de Menus";
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
                                <th>Empresa</th>
                                <th>Menus Associados</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $readList = new Read;
                            $readList->FullRead('select * from inf_associacoes');
                            foreach ($readList->getResult() as $associa_id):
                                extract($associa_id);
                                $readEmpresas = new Read;
                                $readEmpresas->ExeRead('inf_empresas', "WHERE empresa_id = :idempresas", "idempresas={$associa_empresa_id}");
                                foreach ($readEmpresas->getResult() as $empresa_id):
                                    extract($empresa_id);
                                endforeach;

                                echo "<tr>"
                                . "<td>{$associa_id}</td>"
                                . "<td>{$empresa_nome}</td>"
                                . "<td>";

                                $menusAssociados = explode(",", $associa_menus);
                                $readMenus = new Read;
                                $readMenus->FullRead("select menu_id, menu_nome from inf_menus");
                                $cont = 0;        
                                foreach ($readMenus->getResult() as $menu_id): 
                                    extract($menu_id);
                                    $comparacao = array_intersect($readMenus->getResult()[$cont++], $menusAssociados);
                                    if (!empty($comparacao)):
                                        echo"<button type=\"button\" class=\"btn btn-xs btn-success\" data-dismiss=\"modal\">{$menu_nome}</button>&nbsp;&nbsp;";                                        
                                    endif;
                                endforeach;
                                echo"</td><td><a class=\"btn btn-primary\" type=\"submit\" name=\"editar\" value=\"editar\" title=\"Editar\" href=\"painel.php?exe={$navegacao[0]}/update&id={$associa_id}\"><i class=\"fas fa-edit\" aria-hidden=\"true\"></i></a>&nbsp;&nbsp;"
                                . "<a class=\"btn btn-danger\" onclick=\"return confirm('Tem certeza que deseja deletar este registro?')\" type=\"submit\" name=\"excluir\" value=\"excluir\" title=\"Excluir\" href=\"painel.php?exe={$navegacao[0]}/index&delete={$associa_id}\"><i class=\"fas fa-trash-alt\" aria-hidden=\"true\"></i></a>"
                                . "</td>"
                                . "</tr>";
                            endforeach;
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Empresa</th>
                                <th>Menus Associados</th>
                                <th>Ações</th>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="text-right">
                        <a type="submit" class="btn btn-success  btn-flat" value="" name="SendAlter" href="painel.php?exe=<?= $navegacao[0] ?>/create"/><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Novo Item</a>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
</section>