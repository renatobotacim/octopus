<?php
// verifica o login e permissão do usuário.
$checkLevel = new Login(1);
if (!$checkLevel->CheckLevel()):
    redireciona('painel.php?restrito=true');
endif;

//função para navegação por url
$icone = "fas fa-users";
require_once 'includes/navegador.php';


require_once ('../Controllers/userController.class.php');
$controller = new userController;

$del = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT);
if ($del):
    $controller->delete($del);
endif;

$entries = filter_input(INPUT_GET, 'entries', FILTER_VALIDATE_INT);
$search = filter_input(INPUT_GET, 'search', FILTER_DEFAULT);
$pag = filter_input(INPUT_GET, 'pag', FILTER_VALIDATE_INT);

if (!$pag):
    $pag = 1;
endif;

if (!$entries == 25 || !$entries == 50 || !$entries == 100):
    $entries = 10;
endif;

if (empty($search)):
    $page = new Pager("painel.php?exe={$navegacao[0]}/index&entries={$entries}&pag=", 'First', 'Last', 5);
else:
    $page = new Pager("painel.php?exe={$navegacao[0]}/index&entries={$entries}&search={$search}&pag=", 'First', 'Last', 5);
endif;

$page->ExePager($pag, $entries);
$page->ExePaginatorData($controller->readPagination(0, 0, $search, null));
$Data = $controller->readPagination($page->getLimit(), $page->getOffset(), $search, $userlogin);

$AlterStatus = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!empty($AlterStatus['alterStatus'])):
    $pag = 1;
    if (!empty($AlterStatus['search'])):
        redireciona("painel.php?exe={$navegacao[0]}/index&entries={$entries}&search={$AlterStatus['search']}&pag={$pag}");
    else:
        redireciona("painel.php?exe={$navegacao[0]}/index&entries={$entries}&pag={$pag}");
    endif;
    unset($AlterStatus);
endif;

$AlterShowEntries = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!empty($AlterShowEntries['showRegister'])):
    $entries = ($AlterShowEntries['datatable_length']);
    $pag = 1;
    if ($search):
        redireciona("painel.php?exe={$navegacao[0]}/index&entries={$entries}&search={$search}&pag={$pag}");
    else:
        redireciona("painel.php?exe={$navegacao[0]}/index&entries={$entries}&pag={$pag}");
    endif;
    unset($AlterShowEntries);
endif;


?>
<section class="content">
    <div class="container-fluid">
        <?php
        require_once 'includes/statistics_estimates.php';
        ?>
        <div class="card card-primary card-outline">
            <!--Título do box-->
            <div class="card-header"><h3 class="card-title"><i class="fas fa-search"></i>&nbsp;&nbsp;Search Parameters</h3></div>
            <!--início do corpo do box-->
            <div class="card-body">
                <form name="fileForm" action="" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>User Name</label>
                                <input class="form-control" type="text" name="search" id="search" value="<?php if (isset($search)) echo $search ?>">
                            </div>
                        </div>
                    </div>    
                    <div class="container">
                        <div class="row justify-content-md-center">
                            <div class="col col-md-4">
                                <button type="submit" value="Pesquisar" name="alterStatus" class="btn btn-info btn-block btn-flat"><i class="fas fa-search"></i>&nbsp;Search</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card card-primary card-outline">
            <!--Título do box-->
            <div class="card-header"><h3 class="card-title">
                    <i class="fas fa-briefcase"></i>&nbsp;&nbsp;Users List</h3>
                <div class="float-right">
                    <a type="submit" class="btn btn-success btn-sm btn-flat" value="" name="SendAlter" href="painel.php?exe=<?= $navegacao[0] ?>/create"/><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;New Register</a>
                </div>
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
                                <button type="submit" value="alterShowRegister" name="showRegister" class="btn btn-info btn-sm btn-block" style="margin-top: 0px;">Show entries per pages</button>
                            </span>
                        </div>
                    </div>
                </form>
                <div class="card-body">   
                    <table id="datatable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>email</th>
                                <th>login</th>
                                <th>Company</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tabela-titulo">
                            <?php
                            if (!empty($Data)):
                                require_once ('../Controllers/vendorController.class.php');
                                $controllerVendor = new vendorController();
                                foreach ($Data as $i):
                                    extract($i);
                                    $vendor = $controllerVendor->read($company);
                                    echo "<tr>"
                                    . "<td>{$id_user}</td>"
                                    . "<td>{$user_name}</td>"
                                    . "<td>{$email}</td>"
                                    . "<td>{$login}</td>"
                                    . "<td>{$vendor['company_name']}</td>"
                                    . "<td><a class=\"btn btn-flat btn-sm btn-action btn-primary \" href=\"painel.php?exe={$navegacao[0]}/update&id={$id_user}\" title=\"Update Register\"><i class=\"fas fa-edit\"></i></a>"
                                    . "<a class=\"btn btn-sm btn-flat btn-action btn-danger\" onclick=\"return confirm('Do you really want to delete this record?')\" type=\"submit\" title=\"Delete Register\" href=\"painel.php?exe={$navegacao[0]}/index&delete={$id_user}\"><i class=\"fas fa-trash-alt\"></i></a>"
                                    . "</tr>";
                                    unset($vendor);
                                endforeach;
                            else:
                                IMDErro("<b>Sorry!</b><br>But there is no data to display on this page", IMD_ALERT);
                            endif;
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>email</th>
                                <th>login</th>
                                <th>Company</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="card-tools">
                        <?php echo $page->getPaginator(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
