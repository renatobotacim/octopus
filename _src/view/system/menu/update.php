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
    require ('_models/AdminMenu.class.php');
    $cadastra = new AdminMenu();
    $cadastra->ExeUpdate($id, $Data);
    IMDErro($cadastra->getError()[0], $cadastra->getError()[1]);
else:
    $read = new Read();
    $read->ExeRead("inf_menus", "WHERE menu_id = :id", "id={$id}");
    if (!$read->getResult()):
        header('Location:painel.php?exe=menu/index&empty=true');
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
                            <div class="col-xs-3">
                                <label>Nome</label>
                                <input type="text" name="menu_nome" id="menu_nome" class="form-control" placeholder="Nome do menu" value="<?php if (isset($Data)) echo $Data['menu_nome']; ?>">
                            </div>
                            <div class="col-xs-3">
                                <label>Permissão</label>
                                <select id="menu_permissao_id" class="form-control chzn-select" name="menu_permissao_id">
                                    <option disabled="disabled" selected="selected" value="null">Selecione</option>                                     
                                    <?php
                                    $readPermissao = new Read;
                                    $readPermissao->ExeRead("inf_permissoes", "WHERE permissao_id");
                                    foreach ($readPermissao->getResult() as $permissao_id):
                                        extract($permissao_id);
                                        echo"<option ";
                                        if ($Data['menu_permissao_id'] == $permissao_id):
                                            echo "selected=\"selected\"";
                                        endif;
                                        echo "value=\"{$permissao_id}\"> {$permissao_nome} </option>";
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                            <div class="col-xs-3">
                                <label>Classe do Icone</label>
                                <input type="text" name="menu_icone" id="menu_icone" class="form-control" placeholder="Icone Referente" value="<?php if (isset($Data)) echo $Data['menu_icone']; ?>">
                            </div>
                            <div class="col-xs-3">
                                <label>Navegação</label>
                                <input type="text" name="menu_navegacao" id="menu_navegacao" class="form-control" placeholder="Navegação" value="<?php if (isset($Data)) echo $Data['menu_navegacao']; ?>">
                            </div>
                        </div>
                        <br>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-success btn-flat" value="Salvar" name="SendAlter"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;Salvar</button>
                            <a type="submit" class="btn btn-info btn-flat" value="" name="SendAlter" href="painel.php?exe=menu/create"/><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Novo</a>
                            <a type="submit" class="btn btn-danger btn-flat" value="" name="SendAlter" href="painel.php?exe=menu/index"/><i class="fa fa-arrow-circle-left" aria-hidden="true"></i>&nbsp;Voltar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>