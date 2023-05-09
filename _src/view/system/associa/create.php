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
if (!empty($Data['sendForm'])):
    unset($Data['sendForm']);
    $Data['associa_menus'] = implode(",", $Data['associa_menus']);
    require ('_models/AdminAssociacao.class.php');
    $cadastra = new AdminAssociacao();
    $cadastra->ExeCreate($Data);
    if (!$cadastra->getResult()):
        IMDErro($cadastra->getError()[0], $cadastra->getError()[1]);
    else:
        IMDErro($cadastra->getError()[0], $cadastra->getError()[1]);
        $link = 'painel.php?exe=associa/update&create=true&id=' . $cadastra->getResult(); // especifica o endereço
        redireciona($link); // chama a função
    endif;

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
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php
                        if ($navegacao[1] == "create"): echo "Cadastro de {$navegacaoRef}";
                        elseif ($navegacao[1] == "update"): echo"Alteração de {$navegacaoRef}";
                        else: echo "Listagem de {$navegacaoRef}";
                        endif;
                        ?></h3>
                </div>
                <div class="box-body">
                    <form name="fileForm" action="" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-xs-12">
                                <label>Empresa</label>
                                <select id="associa_empresa_id" class="form-control chzn-select" name="associa_empresa_id">
                                    <option disabled="disabled" selected="selected" value="null">Selecione</option>                                     
                                    <?php
                                    $readEmpresa = new Read;
                                    $readEmpresa->ExeRead("inf_empresas", "WHERE empresa_id");
                                    foreach ($readEmpresa->getResult() as $empresa_id):
                                        extract($empresa_id);
                                        echo"<option ";
                                        if ($Data['associa_empresa_id'] == $empresa_id):
                                            echo "selected=\"selected\"";
                                        endif;
                                        echo "value=\"{$empresa_id}\"> {$empresa_nome} </option>";
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                            <div class="col-xs-12">
                                <label>Menus</label>
                                <div class="checkbox">
                                    <?php
                                    $menus = new Read;
                                    $menus->ExeRead("inf_menus", "WHERE menu_id");
                                    foreach ($menus->getResult() as $menu_id):
                                        extract($menu_id);
                                        echo " <label>";
//                                        if ($Data['menu_permissao_id'] == $permissao_id):
//                                            echo "selected=\"selected\"";
//                                        endif;
                                        echo "<input class=\"flat-red\" name=\"associa_menus[]\" value=\"{$menu_id}\" type=\"checkbox\" id=\"{$menu_id}\"> {$menu_nome} </label><br>";
                                    endforeach;
                                    ?>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="text-right">
                            <button type="submit" value="Cadastrar" name="sendForm" class="btn btn-success btn-flat"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;Cadastrar</button>
                            <a href="painel.php?exe=<?= $navegacao[0] ?>/create" class="btn btn-info btn-flat"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Novo</a>
                            <button type="reset" class="btn btn-warning btn-flat"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Limpar Forumário</button>
                            <a href="painel.php?exe=<?= $navegacao[0] ?>/index" class="btn btn-danger btn-flat"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i>&nbsp;Voltar</a>
                        </div>
                    </form>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
</section>