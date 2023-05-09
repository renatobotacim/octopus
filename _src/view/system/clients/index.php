<?php
// verifica o login e permissão do usuário.
$checkLevel = new Login(1);
if (!$checkLevel->CheckLevel()):
    redireciona('painel.php?restrito=true');
endif;


if (filter_input(INPUT_GET, 'padrao', FILTER_VALIDATE_BOOLEAN)):
    IMDErro('<b>Desculpe!</b><br>O cliente "CONSUMIDOR" é um cadastro padrão e não pode ser alterado!', IMD_INFOR);
endif;

$navegacao = filter_input(INPUT_GET, 'exe', FILTER_DEFAULT);
$navegacao = explode("/", $navegacao);

require_once ('../Controllers/clientsController.class.php');
$controller = new clientsController();

$del = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT);
if ($del):
    $controller->delete($del, $userlogin['usuario_empresa_id']);
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
$name = filter_input(INPUT_GET, 'name', FILTER_DEFAULT);
$ident = filter_input(INPUT_GET, 'ident', FILTER_DEFAULT);

//ARRAY COM OS PARAMENTROS QUE FORAM USADOS NO CAMPO DE PESQUISA: PERSONALIZAR DE ACORDO COM A NECESSIDADE!
$params = array('entries' => $entries, 'name' => $name, 'ident' => $ident);

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
    if (!empty($filtros['sendform'])):
        unset($filtros['sendform']);
        $controller->verificaCliente($filtros['cpf_cnpj'], $userlogin['usuario_empresa_id']);
    else:
        $controller->_filters("painel.php?exe={$navegacao[0]}/index", $filtros, $params);
        unset($filtros);
    endif;
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

        <button type="button" name="alterStatus" class="btn btn-success btn-block btn-flat btn-sm mb-3"  data-toggle="modal" data-target="#modal-sm"/><i class="fas fa-plus"></i>&nbsp;&nbsp;Cadastrar Clientes</button>



        <div class="modal fade" id="modal-sm">
            <div class="modal-dialog modal-sm">
                <form name="fileForm" action="" method="post" enctype="multipart/form-data">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Consultar Cliente</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>CPF / CNPJ</label>
                                <input class="form-control form-control-sm" type="text"   data-inputmask="'mask': ['999.999.999-99', '99.999.999/9999-99']" data-mask name="cpf_cnpj" id="cpf_cnpj" value="<?php if (isset($cpf_cnpj)) echo $cpf_cnpj ?>">
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="submit" value="Cadastrar" name="sendform" class="btn btn-block btn-success btn-flat"><i class="fa fa-check-square" aria-hidden="true"></i>&nbsp;&nbsp;Consultar CPF ou CNPJ</button>
                            <button type="button" class="btn btn-block btn-default btn-flat" data-dismiss="modal"><i class="fa fa-close" aria-hidden="true"></i>&nbsp;&nbsp;Cancelar</button>
                        </div>
                    </div>
                </form>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <!-- /.modal -->

        
                    <!-- Bar chart -->
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-filter"></i>&nbsp;&nbsp;Filtros de Pesquisa</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                 <form name="fileForm" action="" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label>Nome</label>
                                <input class="form-control form-control-sm" type="text" name="name" id="name" value="<?php if (isset($name)) echo $name ?>">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label>CPF / CNPJ</label>
                                <input class="form-control form-control-sm" type="text" name="ident" id="ident" value="<?php if (isset($ident)) echo $ident ?>"
                                       data-inputmask="'mask': ['999.999.999-99', '99.999.999/9999-99']" data-mask>
                            </div>
                        </div>

                    </div>   
                    <div class="container">
                        <div class="row justify-content-md-center">
                            <div class="col-md-4">
                                <a href="painel.php?exe=clientes/index">
                                    <button type="button" value="clear" name="clear" class="btn btn-warning btn-block btn-sm btn-flat"><i class="fas fa-eraser"></i>&nbsp;&nbsp;Limpar Formulário</button>
                                </a>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" value="Pesquisar" class="btn btn-info btn-block btn-sm btn-flat"><i class="fas fa-search"></i>&nbsp;&nbsp;Filtrar</button>
                            </div>
                        </div>
                    </div>
                </form>
              </div>
            </div>
        


        <div class="card card-primary card-outline">
            <!--Título do box-->
            <div class="card-header"><h3 class="card-title">
                    <i class="fas fa-list"></i>&nbsp;&nbsp;Listagem de Clientes</h3>
            </div>
            <div class="">
                <!--<div class="overlay-wrapper">-->
                    <!--<div class="overlay"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>-->
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
                                <th>CPF/CNPJ</th>
                                <th>Nome</th>
                                <th>Telefone</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tabela-titulo">
                            <?php
                            if (!empty($Data)):
                                foreach ($Data as $i):
                                    extract($i);
                                    if (!empty($cliente_telefone)):
                                        $tel = "<span data-inputmask=\"'mask': ['(99) 9999-9999', '(99) 99999-9999']\" data-mask>{$cliente_telefone}</span> - <span data-inputmask=\"'mask': ['(99) 9999-9999', '(99) 99999-9999']\" data-mask>{$cliente_celular}</span>";
                                    else:
                                        $tel = "<span data-inputmask=\"'mask': ['(99) 9999-9999', '(99) 99999-9999']\" data-mask>{$cliente_celular}</span>";
                                    endif;
                                    echo "<tr>"
                                    . "<td data-inputmask=\"'mask': ['999.999.999-99', '99.999.999/9999-99']\" data-mask>{$cliente_cpf_cnpj}</td>"
                                    . "<td>{$cliente_nome}</td>"
                                    . "<td>{$tel}</td>"
                                    . "<td>{$cliente_email}</td>"
                                    . "<td><a class=\"btn btn-flat btn-sm btn-action btn-primary \" href=\"painel.php?exe={$navegacao[0]}/update&id={$cliente_id}\" title=\"Alterar Registro\"><i class=\"fas fa-edit\"></i></a>"
                                    . "<a class=\"btn btn-sm btn-flat btn-action btn-danger\" onclick=\"return confirm('Deseja realmente desvincular esse cliente da sua empresa?')\" type=\"submit\" title=\"Deletar Cliente\" href=\"painel.php?exe={$navegacao[0]}/index&delete={$cliente_id}\"><i class=\"fas fa-trash-alt\"></i></a>"
                                    . "</tr>";
                                endforeach;
                            else:
                                IMDErro("<b>Desculpe!</b><br>Não há registros disponíveis com esses parâmetros de busca! ", IMD_ALERT);
                            endif;
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>CPF/CNPJ</th>
                                <th>Nome</th>
                                <th>Telefone</th>
                                <th>Email</th>
                                <th>Action</th>
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
