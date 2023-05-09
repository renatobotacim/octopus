<?php
//VERIFICA A EXISTÊNCIA DE UM LOGIM, CASO SIM, ELE SEMPRE DIRECIONA PARA O PAINEL, CASO NÃO, DIRECIONA A TELA DE LOGIN
$checkLevel = new Login(2);
if (!$checkLevel->CheckLevel()):
    $link = 'painel.php?restrito=true'; // especifica o endereço
    redireciona($link); // chama a função
endif;
$empty = filter_input(INPUT_GET, 'empty', FILTER_VALIDATE_BOOLEAN);
if ($empty):
    IMDErro("Você tentou editar um colaborador que não existe no sistema", IMD_INFOR);
endif;
$update = filter_input(INPUT_GET, 'update', FILTER_DEFAULT);
if ($update == 'sucess'):
    IMDErro("<i class=\"fa fa-check\" aria-hidden=\"true\"></i><br><b>Sucesso ao atualizar!</b><br>O Colaborador foi ATUALIZADO com sucesso!", IMD_ACCEPT);
endif;
$del = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT);
if ($del):
    require ('_models/AdminColaboradores.class.php');
    $deletar = new AdminColaboradores();
    $deletar->ExeDelete($del);
    IMDErro($deletar->getError()[0], $deletar->getError()[1]);
endif;
$navegacao = filter_input(INPUT_GET, 'exe', FILTER_DEFAULT);
$navegacao = explode("/", $navegacao);
$navegacaoRef = "Colaboradores";
$navegacaoIco = "fas fa-people-carry";
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
                                <th>Função</th>
                                <th>Telefone</th>
                                <th>Foto</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $id = 0;
                            $readConteudos = new Read;
                            $readConteudos->ExeRead('inf_colaboradores', "WHERE colaborador_id and colaborador_empresa_id = :empresaId", "empresaId={$userlogin['usuario_empresa_id']}");
                            foreach ($readConteudos->getResult() as $colaborador_id):
                                extract($colaborador_id);
                                $id = $colaborador_id;
                                if (empty($colaborador_imagem)):
                                    $img = "https://www.w3schools.com/w3images/avatar2.png";
                                else:
                                    $img = "./uploads/{$colaborador_imagem}";
                                endif;
                                echo "<tr>"
                                . "<td>{$colaborador_id}</td>"
                                . "<td>{$colaborador_nome}</td>"
                                . "<td>{$colaborador_funcao}</td>"
                                . "<td>{$colaborador_telefone}</td>"
                                . "<td><img src=\"{$img}\" width=\"80px\" heigth=\"auto\"/></td>"
                                . "<td>"
                                . "<a class=\"btn btn-primary btn-flat\" type=\"submit\" name=\"editar\" value=\"editar\" title=\"Editar\" href=\"painel.php?exe={$navegacao[0]}/update&id={$id}\"><i class=\"fas fa-edit\" aria-hidden=\"true\"></i></a>&nbsp;&nbsp;"
                                . "<a class=\"btn btn-danger btn-flat\" onclick=\"return confirm('Tem certeza que deseja deletar este registro?') \" type=\"submit\" name=\"excluir\" value=\"excluir\" title=\"Excluir\" href=\"painel.php?exe={$navegacao[0]}/index&delete={$id}\"><i class=\"fas fa-trash-alt\"></i></a>"
                                . "</td>"
                                . "</tr>";
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
                                    <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancelar</button>
                                    <a href="painel.php?exe=<?= $navegacao[0] ?>/index&delete=<?= $id ?>"><button type="button" class="btn btn-outline">Confirmar Excusão</button></a>
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