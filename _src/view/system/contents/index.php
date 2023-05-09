<?php
// verifica o login e permissão do usuário.
$checkLevel = new Login(1);
if (!$checkLevel->CheckLevel()):
  redireciona('painel.php?restrito=true');
endif;

$navegacao = filter_input(INPUT_GET, 'exe', FILTER_DEFAULT);
$navegacao = explode("/", $navegacao);

require_once('../Controllers/conteudosController.class.php');
$controller = new conteudosController();

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
$titulo = filter_input(INPUT_GET, 'titulo', FILTER_DEFAULT);
$pai = filter_input(INPUT_GET, 'pai', FILTER_DEFAULT);
//ARRAY COM OS PARAMENTROS QUE FORAM USADOS NO CAMPO DE PESQUISA: PERSONALIZAR DE ACORDO COM A NECESSIDADE!
$params = array('entries' => $entries, 'titulo' => $titulo, 'pai' => $pai);

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


    <div class="col-md-2">
      <a href="painel.php?exe=<?= $navegacao[0] ?>/create">
        <div class="info-box mb-3 bg-success">
          <span class="info-box-icon"><i class="nav-icon fas fa-plus"></i></span>
          <div class="info-box-content">
            <span class="info-box-number">CADASTRAR CONTEÚDO</span>
          </div>
        </div>
      </a>
    </div>


    <div class="card card-primary card-outline">
      <!--Título do box-->
      <div class="card-header"><h3 class="card-title">
          <i class="fas fa-list"></i>&nbsp;&nbspLista de Conteúdos</h3>
      </div>

      <div class="card-body">
        <form name="fileForm" action="" method="post" enctype="multipart/form-data">
          <div class="row">
            <div class="col-md-4 mt-2">
              <div class="form-group">
                <label>Título</label>
                <input class="form-control form-control-sm" type="text" name="titulo" id="titulo"
                       value="<?php if (isset($titulo)) echo $titulo ?>">
              </div>
            </div>
            <div class="col-md-4 mt-2">
              <div class="form-group">
                <label>Conteúdo pai</label>
                <select class="form-control select2 form-control-sm" id="pai" name="pai">
                  <option selected="selected" value=""> -- TODOS --</option>
                  <?php
                  $read = new Read;
                  $read->FullRead("select conteudo_titulo, conteudo_id from inf_conteudos WHERE conteudo_empresa_id = :id_emp", "id_emp=4");
                  foreach ($read->getResult() as $conteudo):
                    extract($conteudo);
                    echo "<option ";
                    if (isset($pai) && $pai == $conteudo_id):
                      echo "selected=\"selected\"";
                    endif;
                    echo "value=\"{$conteudo_id}\">{$conteudo_titulo}</option>";
                  endforeach;
                  ?>
                </select>
              </div>
            </div>
          </div>
          <div class="container">
            <div class="row justify-content-md-center">
              <div class="col col-md-3">
                <!--<button type="submit" value="Pesquisar" class="btn btn-info btn-block btn-sm btn-flat"><i class="fas fa-search"></i>&nbsp;&nbsp;Filtrar</button>-->
                <a href="painel.php?exe=<?= $navegacao[0] ?>/index" class="btn btn-warning btn-block btn-sm btn-flat"><i
                    class="fas fa-eraser"></i>&nbsp;Limpar Formulário</a>
              </div>
              <div class="col col-md-3">
                <button type="submit" value="Pesquisar" class="btn btn-info btn-block btn-sm btn-flat"><i
                    class="fas fa-search"></i>&nbsp;&nbsp;Filtrar
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="">
        <!--<div class="overlay-wrapper">-->
        <!--<div class="overlay"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>-->
        <form name="fileForm" action="" method="post" enctype="multipart/form-data">
          <div class="col-3" style="position: absolute;margin-top: 20px; margin-left: 15px;">
            <div class="input-group input-group">
              <select class="custom-select custom-select-sm form-control form-control-sm-sm form-flat "
                      id="datatable_length" name="entries" aria-controls="datatable" style="z-index: 100">
                <option <?php if ($entries == 10) echo 'selected' ?> value="10">10</option>
                <option <?php if ($entries == 25) echo 'selected' ?> value="25">25</option>
                <option <?php if ($entries == 50) echo 'selected' ?> value="50">50</option>
                <option <?php if ($entries == 100) echo 'selected' ?> value="100">100</option>
              </select>
              <span class="input-group-append">
                                <button type="submit" value="alterShowRegister" class="btn btn-info btn-sm btn-block"
                                        style="margin-top: 0px;">Registros por Página</button>
                            </span>
            </div>
          </div>
        </form>
        <div class="card-body">
          <table id="datatable" class="table table-bordered table-striped">
            <thead>
            <tr>
              <th>#</th>
              <th>Título</th>
              <th>Conteúdo Pai</th>
              <th>Ação</th>
            </tr>
            </thead>
            <tbody id="tabela-titulo">
            <?php
            if (!empty($Data)):
              foreach ($Data as $i):
                extract($i);
                $contPai = '';
                if ($conteudo_pai):
                  foreach ($read->getResult() as $c):
                    if ($c['conteudo_id'] == $conteudo_pai):
                      $contPai = $c['conteudo_titulo'];
                    endif;
                  endforeach;
                endif;
                echo "<tr>"
                  . "<td>{$conteudo_id}</td>"
                  . "<td>{$conteudo_titulo}</td>"
                  . "<td>{$contPai}</td>"
                  . "<td><a class=\"btn btn-flat btn-sm btn-action btn-primary \" href=\"painel.php?exe={$navegacao[0]}/update&id={$conteudo_id}\" title=\"Alterar Registro\"><i class=\"fas fa-edit\"></i></a>"
                  . "<a class=\"btn btn-sm btn-flat btn-action btn-danger\" onclick=\"return confirm('Deseja realmente excluir esse registro?')\" type=\"submit\" title=\"Excluir Registro\" href=\"painel.php?exe={$navegacao[0]}/index&delete={$conteudo_id}\"><i class=\"fas fa-trash-alt\"></i></a>"
                  . "</tr>";
              endforeach;
            else:
              IMDErro("<b>Desculpe!</b><br>Não há registros disponíveis com esses parâmetros de busca! ", IMD_ALERT);
            endif;
            ?>
            </tbody>
            <tfoot>
            <tr>
              <th>#</th>
              <th>Título</th>
              <th>Conteúdo Pai</th>
              <th>Ação</th>
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
