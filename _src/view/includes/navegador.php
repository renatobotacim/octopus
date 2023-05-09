<?php
$navegacao = filter_input(INPUT_GET, 'exe', FILTER_DEFAULT);
$navegacao = explode("/", $navegacao);

switch ($navegacao[1]) {
    case "create":
        $navegacao[1] = "<i class=\"fas fa-plus\"></i>&nbsp;Create";
        break;
    case "update":
        $navegacao[1] = "<i class=\"fas fa-edit\"></i>&nbsp;Update";
        break;
}
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6 text-uppercase">
                <h1><?= $navegacao[0] ?></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item text-capitalize"><a href="painel.php"><i class="fas fa-tachometer-alt"></i>&nbsp;Dashboard</a></li>
                    <li class="breadcrumb-item text-capitalize"><a href="painel.php?exe=<?= $navegacao[0] ?>/index"><i class="<?= $icone ?>"></i>&nbsp;<?= $navegacao[0] ?></a></li>
                        <?php
                        if (!($navegacao[1] == 'index')):
                            echo "<li class=\"breadcrumb-item text-capitalize active\">{$navegacao[1]}</li>";
                        endif;
                        ?>
                </ol>
            </div>
        </div>
    </div>
</section>
