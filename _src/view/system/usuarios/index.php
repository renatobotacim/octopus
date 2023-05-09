<?php
// verifica o login e permissão do usuário.
$checkLevel = new Login(2);
if (!$checkLevel->CheckLevel()):
    redireciona('painel.php?restrito=true');
endif;

$navegacao = filter_input(INPUT_GET, 'exe', FILTER_DEFAULT);
$navegacao = explode("/", $navegacao);

require_once ('../Controllers/usuarioController.class.php');
$controller = new usuarioController();

//CHAMA A FUNÇÃO DE HABILITAR E DESABILITAR O USUÁRIO.
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id):
    $status = filter_input(INPUT_GET, 'status', FILTER_VALIDATE_INT);
    $controller->status($id, $status);
endif;

//FILTROS USADOS NO PARAMETROS DE PESQUISA 
$entries = filter_input(INPUT_GET, 'entries', FILTER_VALIDATE_INT);
if (!$entries):
    $entries = 10;
endif;
$pag = filter_input(INPUT_GET, 'pag', FILTER_VALIDATE_INT);
if (!$pag):
    $pag = 1;
endif;

//Paremetros de pesquisa
$nome = filter_input(INPUT_GET, 'nome', FILTER_VALIDATE_INT);
$setor = filter_input(INPUT_GET, 'setor', FILTER_VALIDATE_INT);
$status = filter_input(INPUT_GET, 'status', FILTER_DEFAULT);

//ARRAY COM OS PARAMENTROS QUE FORAM USADOS NO CAMPO DE PESQUISA: PERSONALIZAR DE ACORDO COM A NECESSIDADE!
$listParams = array('nome', 'setor', 'status');
$params = array('nome' => $nome, 'setor' => $setor, 'status' => $status);

$url = '';
for ($index = 0; $index < sizeof($listParams); $index++):
    if (isset($params[$listParams[$index]])):
        $url .= "&{$listParams[$index]}={$params[$listParams[$index]]}";
    endif;
endfor;

$Pager = new Pager("painel.php?exe={$navegacao[0]}/index" . $url . "&pag=", 'Primeira', 'Última', 5);
$Pager->ExePager($pag, $entries);
$Data = $controller->readPagination($Pager->getLimit(), $Pager->getOffset(), $params, $userlogin['usuario_empresa_id']);
$rows = $controller->countByEmp($userlogin['usuario_empresa_id'], $params);
$Pager->ExePaginatorRows($rows);

$icone = "fas fa-users";
require_once 'includes/navegador.php';
?>
<section class="content">
    <div class="container-fluid">
        <!--        <div class="card card-primary card-outline">
                    Título do box
                    <div class="card-header"><h3 class="card-title"><i class="fas fa-search"></i>&nbsp;&nbsp;Parâmetros de Busca</h3></div>
                    início do corpo do box
                    <div class="card-body">
                        <form name="fileForm" action="" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Nome do Usuário</label>
                                        <input class="form-control form-control-sm" type="text" name="search" id="search" value="<?php if (isset($search)) echo $search ?>">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Setor</label>
                                         <select class="form-control form-control-sm" id="parecer_status" name="parecer_status">
                                            <option disabled="disabled" selected="selected" value="null">Selecione</option>                                     
                                            <option value="1"  <?php if (isset($Data['parecer_status']) && $Data['parecer_status'] == 1) echo 'selected' ?>>Finanças</option>                                   
                                            <option value="2"  <?php if (isset($Data['parecer_status']) && $Data['parecer_status'] == 2) echo 'selected' ?>>Meio Ambiente</option>                                   
                                            <option value="3"  <?php if (isset($Data['parecer_status']) && $Data['parecer_status'] == 3) echo 'selected' ?>>Obras e Postura</option>                                   
                                            <option value="4"  <?php if (isset($Data['parecer_status']) && $Data['parecer_status'] == 4) echo 'selected' ?>>Vigilância Sanitária </option>                                   
                                            <option value="4"  <?php if (isset($Data['parecer_status']) && $Data['parecer_status'] == 5) echo 'selected' ?>>Corpo de Bombeiros</option>                                   
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Status</label>
                                         <select class="form-control form-control-sm" id="parecer_status" name="parecer_status">
                                            <option value="1"  <?php if ($setor == 1) echo 'selected' ?>>Ativo</option>                                   
                                            <option value="2"  <?php if ($setor == 2) echo 'selected' ?>>Inativo</option>                                   
                                            <option value="3"  <?php if ($setor == 3) echo 'selected' ?>>Todos</option>                                   
                                        </select>
                                    </div>
                                </div>
                            </div>    
                            <div class="container">
                                <div class="row justify-content-md-center">
                                    <div class="col col-md-4">
                                        <button type="submit" value="Pesquisar" name="alterStatus" class="btn btn-info btn-sm btn-block btn-flat"><i class="fas fa-search"></i>&nbsp;&nbsp;Filtar</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>-->
        <div class="">

            <a type="submit" class="btn btn-success btn-sm btn-flat btn-block mt-4 mb-2" value="" name="SendAlter" href="painel.php?exe=<?= $navegacao[0] ?>/create"/><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;Cadastrar Novo Usuário</a>
        </div>
        <div class="card card-primary card-outline">
            <!--Título do box-->
            <div class="card-header"><h3 class="card-title">
                    <i class="fas fa-users"></i>&nbsp;&nbsp;Listagem de Usuários</h3>

            </div>
            <div class="">
                <!--<div class="overlay-wrapper">-->
                    <!--<div class="overlay"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>-->
                <form name="fileForm" action="" method="post" enctype="multipart/form-data">
                    <div class="col-3" style="position: absolute;margin-top: 20px; margin-left: 15px;">
                        <div class="input-group input-group">
                            <select class="custom-select custom-select-sm form-control-sm" id="datatable_length" name="datatable_length" aria-controls="datatable">
                                <option <?php if ($entries == 10) echo 'selected' ?> value="10" >10</option>
                                <option <?php if ($entries == 25) echo 'selected' ?> value="25">25</option>
                                <option <?php if ($entries == 50) echo 'selected' ?> value="50">50</option>
                                <option <?php if ($entries == 100) echo 'selected' ?> value="100">100</option>
                            </select>
                            <span class="input-group-append">
                                <button type="submit" value="alterShowRegister" name="showRegister" class="btn btn-info btn-sm btn-block" style="margin-top: 0px;">Registros por Página</button>
                            </span>
                        </div>
                    </div>
                </form>
                <div class="card-body">   
                    <table id="datatable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Usuario</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="tabela-titulo">
                            <?php
                            if (!empty($Data)):
                                foreach ($Data as $i):
                                    extract($i);
                                    if ($usuario_status == 1):
                                        $usuario_status = "Ativo";
                                        $btnStatus = "<a class=\"btn btn-sm btn-flat btn-action btn-danger\" onclick=\"return confirm('Deseja desatiar esse usuário?')\" type=\"submit\" title=\"Desativar Usuário\" href=\"painel.php?exe={$navegacao[0]}/index&id={$usuario_id}&status=2\"><i class=\"fas fa-power-off\"></i></a>";
                                    else:
                                        $usuario_status = "Inativo";
                                        $btnStatus = "<a class=\"btn btn-sm btn-flat btn-action btn-success\" onclick=\"return confirm('Deseja ativar esse usuário?')\" type=\"submit\" title=\"Ativar Usuário\" href=\"painel.php?exe={$navegacao[0]}/index&id={$usuario_id}&status=1\"><i class=\"fas fa-power-off\"></i></a>";
                                    endif;
                                    echo "<tr>"
                                    . "<td>{$usuario_id}</td>"
                                    . "<td>{$usuario_nome}</td>"
                                    . "<td>{$usuario_email}</td>"
                                    . "<td>{$usuario_usuario}</td>"
                                    . "<td>{$usuario_status}</td>"
                                    . "<td><a class=\"btn btn-flat btn-sm btn-action btn-warning \" href=\"painel.php?exe={$navegacao[0]}/password&id={$usuario_id}\" title=\"Trocar Senha\"><i class=\"fas fa-key\"></i></a>"
                                    . "<a class=\"btn btn-flat btn-sm btn-action btn-primary \" href=\"painel.php?exe={$navegacao[0]}/update&id={$usuario_id}\" title=\"Editar Usuário Register\"><i class=\"fas fa-edit\"></i></a>"
                                    . "{$btnStatus}"
                                    . "</tr>";
                                    unset($vendor);
                                endforeach;
                            else:
                                IMDErro("<b>Desculpe!</b><br>Não foi encontrado nenhum registro!", IMD_ALERT);
                            endif;
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                             <th>#</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Usuario</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="card-tools">
                        <?php
                        $b = $pag * $entries;
                        $a = $b - ($entries - 1);
                        if ($b > $rows):
                            $b = $rows;
                        endif;
                        echo "Mostrando de <b>{$a}</b> a <b>{$b}</b> de um total de <b>{$rows}</b> registros!";
                        echo $Pager->getPaginator();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
