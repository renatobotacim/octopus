<?php
// verifica o login e permissão do usuário.
$checkLevel = new Login(1);
if (!$checkLevel->CheckLevel()):
    redireciona('painel.php?restrito=true');
endif;

$navegacao = filter_input(INPUT_GET, 'exe', FILTER_DEFAULT);
$navegacao = explode("/", $navegacao);

require_once ('../Controllers/genealogiaController.class.php');
$controller = new genealogiaController();

$del = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT);
if ($del):
    $controller->delete($del);
endif;

/**
 * PARAMETROS DE FILTRO ESPECIFICO USADOS PARAMETROS DE PESQUISA 
 * Basta criar uma variável para cada item a ser fitrado e pegar seu valor
 * via URL
 */
// PARAMETROS DE FILTRO BASE USADOS PARAMETROS DE PESQUISA 
$entries = filter_input(INPUT_GET, 'entries', FILTER_VALIDATE_INT);
if (!$entries):
    $entries = 10;
endif;

$pag = filter_input(INPUT_GET, 'pag', FILTER_VALIDATE_INT);
if (!$pag):
    $pag = 1;
endif;

// PARAMETROS ESPECÍFICOS
$nome = filter_input(INPUT_GET, 'nome', FILTER_DEFAULT);
$pai = filter_input(INPUT_GET, 'pai', FILTER_DEFAULT);
$mae = filter_input(INPUT_GET, 'mae', FILTER_DEFAULT);
$conjuge = filter_input(INPUT_GET, 'conjuge', FILTER_DEFAULT);
//ARRAY COM OS PARAMENTROS QUE FORAM USADOS NO CAMPO DE PESQUISA: PERSONALIZAR DE ACORDO COM A NECESSIDADE!
$params = array('entries' => $entries, 'nome' => $nome, 'pai' => $pai, 'mae' => $mae, 'conjuge' => $conjuge);

// PERCORRE O ARRAY, VERIFICANDO CADA ITEM DO FILTRO
$url = '';
for ($index = 0; $index < sizeof($params); $index++):
    if (isset($params[array_keys($params)[$index]])):
        $url .= "&" . array_keys($params)[$index] . "={$params[array_keys($params)[$index]]}";
    endif;
endfor;

// RESPONSÁVEL POR RECEBER O FORM E ENVIAR PARA O CONTROLER
$filtros = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!empty($filtros)):
    unset($filtros['sendform']);
    $controller->_filters("painel.php?exe={$navegacao[0]}/index", $filtros, $params);
endif;

//CONSULTA O CONTROLLER OS DADOS DA QUANTIDADE DE PÁGINAS E DOS REGISTROS FILTRADOS
$Pager = new Pager("painel.php?exe={$navegacao[0]}/index" . $url . "&pag=", 'Primeira', 'Última', 5);
$Pager->ExePager($pag, $entries);
//AQUI SERÁ NECESSÁRIO INFORMAR O ID DA EMPRESA
$Data = $controller->readPagination($Pager->getLimit(), $Pager->getOffset(), $params, $userlogin['usuario_empresa_id']);
//AQUI SERÁ NECESSÁRIO INFORMAR O ID DA EMPRESA
$rows = $controller->countByEmp($userlogin['usuario_empresa_id'], $params);
$Pager->ExePaginatorRows($rows);
/**
 * FIM DA PARAMETRIZAÇÃO DOS FILTROS DE PESQUISA
 * Se não houver filtros de pequisa, manter SEMPRE o primeiro valor do array com
 * os elementos das entreies (quantidade de itens em exibição
 * ---------------------------------------------------------------------------------------------------
 */
$icone = "fas fa-users";
require_once 'includes/navegador.php';
?>
<section class="content">
    <div class="container-fluid">
        <div class="row mb-4">

            <div class="col-md-3">
                <a href="painel.php?exe=<?= $navegacao[0] ?>/create">
                    <div class="info-box mb-3 bg-success">
                        <span class="info-box-icon"><i class="nav-icon fas fa-user-plus"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-number">Inserir Membro</span>
                            <!--<span class="info-box-text"> </span>-->
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </a>
            </div>
        </div>
        <div class="card card-primary card-outline">
            <!--Título do box-->
            <div class="card-header"><h3 class="card-title">
                    <i class="fas fa-list"></i>&nbsp;&nbsp;Pessoas Cadastradas</h3>
            </div>


            <div class="card-body">
                <form name="fileForm" action="" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-3 mt-2">
                            <div class="form-group">
                                <label>Nome</label>
                                <input class="form-control form-control-sm" type="text" name="nome" id="numero" value="<?php if (isset($nome)) echo $nome ?>">
                            </div>
                        </div>
                        <div class="col-md-3 mt-2">
                            <div class="form-group">
                                <label>Pai</label>
                                <input class="form-control form-control-sm" type="text" name="pai" id="pai" value="<?php if (isset($pai)) echo $pai ?>">
                            </div>
                        </div>
                        <div class="col-md-3 mt-2">
                            <div class="form-group">
                                <label>Mãe</label>
                                <input class="form-control form-control-sm" type="text" name="mae" id="mae" value="<?php if (isset($mae)) echo $mae ?>">
                            </div>
                        </div>
                        <div class="col-md-3 mt-2">
                            <div class="form-group">
                                <label>Conjuge</label>
                                <input class="form-control form-control-sm" type="text" name="conjuge" id="conjuge" value="<?php if (isset($conjuge)) echo $conjuge ?>">
                            </div>
                        </div>


                        <!--                        <div class="col-md-2 mt-2">
                                                    <div class="form-group">
                                                        <label>Situação</label>
                                                        <select class="form-control select2 form-control-sm" id="situacao" name="situacao">
                                                            <option  selected="selected" value=""> -- TODOS -- </option>   
                        <?php
                        $read = new Read;
                        $read->FullRead("select genealogia_id, nome  from inf_genealogias WHERE genealogia_empresa_id = :id_emp", "id_emp={$userlogin['usuario_empresa_id']}");
                        foreach ($read->getResult() as $genealogia_id):
                            extract($genealogia_id);
                            echo"<option ";
                            if (isset($mae) && $mae == $genealogia_id):
                                echo "selected=\"selected\"";
                            endif;
                            echo "value=\"{$genealogia_id}\">{$nome}</option>";
                        endforeach;
                        ?>
                                                        </select>
                                                    </div>
                                                </div>-->

                    </div>    
                    <div class="container">
                        <div class="row justify-content-md-center">
                            <div class="col col-md-3">
                                <!--<button type="submit" value="Pesquisar" class="btn btn-info btn-block btn-sm btn-flat"><i class="fas fa-search"></i>&nbsp;&nbsp;Filtrar</button>-->
                                <a href="painel.php?exe=<?= $navegacao[0] ?>/index" class="btn btn-warning btn-block btn-sm btn-flat"><i class="fas fa-eraser"></i>&nbsp;Limpar Formulário</a>
                            </div>
                            <div class="col col-md-3">
                                <button type="submit" value="Pesquisar" class="btn btn-info btn-block btn-sm btn-flat"><i class="fas fa-search"></i>&nbsp;&nbsp;Filtrar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="">
                <form name="fileForm" action="" method="post" enctype="multipart/form-data">
                    <div class="col-3" style="position: absolute;margin-top: 20px; margin-left: 15px;">
                        <div class="input-group input-group">
                            <select class="custom-select custom-select-sm form-control form-control-sm-sm form-flat " id="datatable_length" name="entries" aria-controls="datatable" style="z-index: 100">
                                <option <?php if ($entries == 10) echo 'selected' ?> value="10" >10</option>
                                <option <?php if ($entries == 25) echo 'selected' ?> value="25">25</option>
                                <option <?php if ($entries == 50) echo 'selected' ?> value="50">50</option>
                                <option <?php if ($entries == 100) echo 'selected' ?> value="100">100</option>
                            </select>
                            <span class="input-group-append">
                                <button type="submit" value="alterShowRegister" class="btn btn-info btn-sm btn-block" style="margin-top: 0px;">Registros por Página</button>
                            </span>
                        </div>
                    </div>
                </form>

                <div class="card-body">   
                    <table id="datatable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>NOME</th>
                                <th>NASCIMENTO</th>
                                    <th>STATUS</th>
                                <th>PAI</th>
                                <th>MÃE</th>
                                <th>CONJUGE</th>
                                <th>AÇÕES</th>
                            </tr>
                        </thead>
                        <tbody >
                            <?php
                            if (!empty($Data)):
                                foreach ($Data as $i):
                                    extract($i);
//                                    if (status == 2) : echo 'Ativo': echo 'Inativo'
//                                        $btnStatus = "<div class=\"btn btn-sm btn-flat btn-success mr-2\"><i class=\"fas fa-power-off\"></i>&nbsp;&nbsp;Ativa</div>";
//                                    else:
//                                        $btnStatus = "<div class=\"btn btn-sm btn-flat btn-danger mr-2\"><i class=\"fas fa-power-off\"></i>&nbsp;&nbsp;Inativa</div>";
//                                    endif;
                                    $data_nascimento = dateFormat($data_nascimento);
                                    $status = ($status == 2) ? "Ativo" : "Inativo";
                                    echo "<tr>"
                                    . "<td>{$genealogia_id}</td>"
                                    . "<td>{$nome}</td>"
                                    . "<td>{$data_nascimento}</td>"
                                    . "<td>{$status}</td>"
                                    . "<td>{$pai}</td>"
                                    . "<td>{$mae}</td>"
                                    . "<td>{$conjuge}</td>"
                                    . "<td>"
                                    . "<a class=\"btn btn-flat btn-sm btn-action btn-primary \" href=\"painel.php?exe={$navegacao[0]}/update&id={$genealogia_id}\" title=\"Alterar Registro\"><i class=\"fas fa-edit\"></i></a>"
                                    . "<a class=\"btn btn-sm btn-flat btn-action btn-danger\" onclick=\"return confirm('Deseja realmente excluir esse registro?')\" type=\"submit\" title=\"Excluir Registro\" href=\"painel.php?exe={$navegacao[0]}/index&delete={$genealogia_id}\"><i class=\"fas fa-trash-alt\"></i></a>"
                                    . "</tr>";
                                endforeach;
                            else:
                                IMDErro("<b>Desculpe!</b><br>Não há registros disponíveis com esses parâmetros de busca! ", IMD_ALERT);
                            endif;
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>NOME</th>
                                <th>NASCIMENTO</th>
                                <th>STATUS</th>
                                <th>PAI</th>
                                <th>MÃE</th>
                                <th>CONJUGE</th>
                                <th>AÇÕES</th>
                            </tr>
                        </tfoot>
                    </table>
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
</section>
