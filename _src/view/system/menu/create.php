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
if (!empty($Data['sendMenu'])):
    unset($Data['sendMenu']);
    require ('_models/AdminMenu.class.php');
    $cadastra = new AdminMenu();
    $cadastra->ExeCreate($Data);
    if (!$cadastra->getResult()):
        IMDErro($cadastra->getError()[0], $cadastra->getError()[1]);
    else:
        IMDErro($cadastra->getError()[0], $cadastra->getError()[1]);
        $link = 'painel.php?exe=menu/update&create=true&id=' . $cadastra->getResult(); // especifica o endereço
        redireciona($link); // chama a função
    endif;
    
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
                    <h3 class="box-title"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php if($navegacao[1] == "create"): echo "Cadastro de {$navegacaoRef}";elseif($navegacao[1] == "update"): echo"Alteração de {$navegacaoRef}"; else: echo "Listagem de {$navegacaoRef}";endif;?></h3>
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
                        <div class="text-right">
                            <button type="submit" value="Cadastrar" name="sendMenu" class="btn btn-success btn-flat"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;Cadastrar</button>
                            <a href="painel.php?exe=menu/create" class="btn btn-info btn-flat"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Novo</a>
                            <button type="reset" class="btn btn-warning btn-flat"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Limpar Forumário</button>
                            <a href="painel.php?exe=menu/index" class="btn btn-danger btn-flat"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i>&nbsp;Voltar</a>
                        </div>
                    </form>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
</section>