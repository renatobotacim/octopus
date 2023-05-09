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
    require ('_models/AdminEmpresas.class.php');
    $deletar = new AdminEmpresas();
    $deletar->ExeDelete($del);
    IMDErro($deletar->getError()[0], $deletar->getError()[1]);
endif;
////Box de estatisticas
//$readContStatistc_1 = new Read;
//$readContStatistc_1->ExeRead('inf_empresas', "WHERE empresa_id");
$navegacao = filter_input(INPUT_GET, 'exe', FILTER_DEFAULT);
$navegacao = explode("/", $navegacao);
$navegacaoRef = "Empresas";
$navegacaoIco = "fa fa-list";
require_once 'includes/navegador.php';
?>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-danger">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php if ($navegacao[1] == "create"): echo "Cadastro de {$navegacaoRef}";
elseif ($navegacao[1] == "update"): echo"Alteração de {$navegacaoRef}";
else: echo "Listagem de {$navegacaoRef}";
endif; ?></h3>
                </div>
                <div class="box-body">
                    <table id="DataTebles" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Link</th>
                                <th>Email</th>
                                <th>Telefone</th>
                                <th>Data Registro</th>
                                <th>Ações</th>
                            </tr>
                        </thead>    
                        <tbody>
                            <?php
                            $readEmpresas = new Read;
                            $readEmpresas->FullRead('select * from inf_empresas');
                            foreach ($readEmpresas->getResult() as $empresa_id):
                                extract($empresa_id);
                                echo "<tr>"
                                . "<td>{$empresa_id}</td>"
                                . "<td>{$empresa_nome}</td>"
                                . "<td>{$empresa_email}</td>"
                                . "<td>{$empresa_site}</td>"
                                . "<td>{$empresa_telefone}</td>"
                                . "<td>{$empresa_cnpj}</td>"
                                . "<td>"
                                . "<a class=\"btn btn-primary btn-flat\" type=\"submit\" name=\"editar\" value=\"editar\" title=\"Editar\" href=\"painel.php?exe=companies/update&id={$empresa_id}\"><i class=\"fas fa-edit\" aria-hidden=\"true\"></i></a>&nbsp;&nbsp;"
                                . "<a class=\"btn btn-danger btn-flat\" onclick=\"return confirm('Tem certeza que deseja deletar este registro?')\" type=\"submit\" name=\"excluir\" value=\"excluir\" title=\"Excluir\" href=\"painel.php?exe=companies/index&delete={$empresa_id}\"><i class=\"fas fa-trash\" aria-hidden=\"true\"></i></a>"
                                . "</td>"
                                . "</tr>";
                            endforeach;
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Link</th>
                                <th>Email</th>
                                <th>Telefone</th>
                                <th>Data Registro</th>
                                <th>Ações</th>
                            </tr>
                        </tfoot>
                    </table>
                     <div class="text-right">
                        <a type="submit" class="btn btn-success btn-flat" value="" name="SendAlter" href="painel.php?exe=<?= $navegacao[0] ?>/create"/><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Novo Item</a>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
</section>

