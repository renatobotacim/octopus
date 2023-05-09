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
    require ('_models/AdminAssociacao.class.php');
    $Data['associa_menus'] = implode(",", $Data['associa_menus']);
    $cadastra = new AdminAssociacao();
    $cadastra->ExeUpdate($id, $Data);
    IMDErro($cadastra->getError()[0], $cadastra->getError()[1]);
else:
    $read = new Read();
    $read->ExeRead("inf_associacoes", "WHERE associa_id = :id", "id={$id}");
    if (!$read->getResult()):
        header('Location:painel.php?exe=associa/index&empty=true');
    else:
        $Data = $read->getResult()[0];
    endif;
endif;
$checkCreate = filter_input(INPUT_GET, 'create', FILTER_VALIDATE_BOOLEAN);
if ($checkCreate):
    IMDErro("<i class=\"fa fa-check\" aria-hidden=\"true\"></i><br><b>Sucesso ao Cadastrar!</b><br>O menu <b>{$Data['menu_nome']}</b> foi criada com sucesso no sistema! <br>Continue atualizando o mesmo.", IMD_ACCEPT);
endif;
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
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Empresa</label>
                                    <select id="associa_empresa_id" class="form-control chzn-select" readonly="true" name="associa_empresa_id">
                                        <?php
                                        $readEmpresa = new Read;
                                        $readEmpresa->FullRead("select empresa_id, empresa_nome from inf_empresas");
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
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Menus</label>
                                    <div class="checkbox" style="margin-left: 10px;  ">
                                        <?php
                                        $menus = new Read;
                                        $menus->ExeRead("inf_menus", "WHERE menu_id");
                                        foreach ($menus->getResult() as $menu_id):
                                            extract($menu_id);
                                        endforeach;
                              
                                        $menusSelecteds = explode(",", $Data['associa_menus']);
                                        $readMenus = new Read;
                                        $readMenus->FullRead("select menu_id, menu_nome from inf_menus");
                                        $cont = 0;
                                        foreach ($readMenus->getResult() as $menu_id):
                                            extract($menu_id);
                                            $comparacao = array_intersect($readMenus->getResult()[$cont++], $menusSelecteds);
                                            if (!empty($comparacao)):
                                                echo "<input checked class=\"flat-red\"name=\"associa_menus[]\" value=\"{$menu_id}\" type=\"checkbox\" id=\"{$menu_id}\"> {$menu_nome} </label><br>";
                                            else:
                                                echo "<input class=\"flat-red\" name=\"associa_menus[]\" value=\"{$menu_id}\" type=\"checkbox\" id=\"{$menu_id}\"> {$menu_nome} </label><br>";
                                            endif;
                                        endforeach;
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="text-right">
                            <button type="submit" value="Salvar" name="SendAlter" class="btn btn-success btn-flat"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;Salvar</button>
                            <a href="painel.php?exe=<?= $navegacao[0] ?>/create" class="btn btn-info btn-flat"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Novo</a>
                            <a href="painel.php?exe=<?= $navegacao[0] ?>/index" class="btn btn-danger btn-flat"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i>&nbsp;Voltar</a>
                        </div>
                    </form>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
</section>