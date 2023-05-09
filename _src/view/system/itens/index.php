<?php
// verifica o login e permissão do usuário.
$checkLevel = new Login(1);
if (!$checkLevel->CheckLevel()):
    redireciona('painel.php?restrito=true');
endif;

$navegacao = filter_input(INPUT_GET, 'exe', FILTER_DEFAULT);
$navegacao = explode("/", $navegacao);

if (filter_input(INPUT_GET, 'create', FILTER_VALIDATE_BOOLEAN)):
    IMDErro('<b>Sucesso</b><br>A despesa foi registrada com sucesso!', IMD_ACCEPT);
endif;

require_once ('../Controllers/produtosController.class.php');
$controller = new produtosController();

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
//$name = filter_input(INPUT_GET, 'name', FILTER_DEFAULT);
//ARRAY COM OS PARAMENTROS QUE FORAM USADOS NO CAMPO DE PESQUISA: PERSONALIZAR DE ACORDO COM A NECESSIDADE!
$params = array('entries' => $entries);

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

        <a href="painel.php?exe=<?= $navegacao[0] ?>/create"> 
            <button type="button" name="alterStatus" class="btn btn-success btn-block btn-flat btn-sm mb-3"><i class="fas fa-plus"></i>&nbsp;&nbsp;Cadastrar Produto</button>
        </a>
        <div class="">
            <div class="card card-primary card-outline">
                <div class="card-header"><h3 class="card-title">
                        <i class="fas fa-list"></i>&nbsp;&nbsp;Lista de Produtos</h3>
                </div>

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
                    <table id="datatable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Códigos</th>
                                <th>Nome / Marca</th>
                                <th>Valor</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="tabela-titulo">
                            <?php
                            if (!empty($Data)):
                                foreach ($Data as $i):
                                    extract($i);
                                    if (intval($estoque_uso) == 0):
                                        $usado = 0;
                                    else:
                                        $usado = (intval($estoque_uso) * 100) / intval($estoque_quantidade);
                                    endif;
                                    $progress = "<div class=\"progress\" title=\"Relação de uso: Você está usando {$estoque_uso} unidade do seu estoque de {$estoque_quantidade} em suas ordens de serviço e/ou venda, que ainda não foram finalizadas!\"><div class=\"progress-bar bg-primary progress-bar-striped\" role=\"progressbar\" aria-valuenow=\"40\" aria-valuemin=\"0\"  aria-valuemax=\"{$estoque_quantidade}\" style=\"width: {$usado}%\"></div></div>";
                                    echo "<tr>"
                                    . "<td>{$produto_id}</td>"
                                    . "<td>Interno: {$produto_codigo_interno}<br>Fabricante: {$produto_codigo_externo}</td>"
                                    . "<td>{$produto_nome}({$produto_unidade})<br>{$produto_fabricante}</td>"
                                    . "<td class=\"real\">{$produto_valor_venda}</td>"
                                    . "<td><a class=\"btn btn-flat btn-sm btn-action btn-primary \" href=\"painel.php?exe={$navegacao[0]}/update&id={$produto_id}\" title=\"Alterar Produto\"><i class=\"fas fa-edit\"></i></a>"
                                    . "<a class=\"btn btn-sm btn-flat btn-action btn-danger\" onclick=\"return confirm('Deseja excluir esse produto?')\" type=\"submit\" title=\"Deletar Produto\" href=\"painel.php?exe={$navegacao[0]}/index&delete={$produto_id}\"><i class=\"fas fa-trash-alt\"></i></a>"
                                    . "</tr>";
                                endforeach;
                            else:
                                IMDErro("<b>Sorry!</b><br>But there is no data to display on this page", IMD_ALERT);
                            endif;
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Códigos</th>
                                <th>Nome / Marca</th>
                                <th>Valor</th>
                                <th>Ações</th>
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
