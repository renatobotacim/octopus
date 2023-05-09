<?php
require_once ('../Controllers/vendorController.class.php');
$controller = new vendorController();
$metrics = $controller->_getMetrics($userlogin['user_level']);
foreach ($metrics as $n):
    extract($n);
    if ($n['company_status'] == 2):
        $qtd_pending = $n['count'];
    elseif ($n['company_status'] == 1):
        $qtd_rejected = $n['count'];
    elseif ($n['company_status'] == 3):
        $qtd_active = $n['count'];
    endif;
endforeach;

if (!isset($qtd_active)):
    $qtd_rejected = 0;
endif;
if (!isset($qtd_rejected)):
    $qtd_rejected = 0;
endif;
if (!isset($qtd_pending)):
    $qtd_pending = 0;
endif;

$qtd_all = $qtd_pending + $qtd_rejected + !empty($qtd_active);
?>
<div class="row">
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= $qtd_all ?></h3>
                <p>Vendors Records</p>
            </div>
            <div class="icon">
                <i class="ion ion-person"></i>
            </div>
            <a href="painel.php?exe=vendors/index&entries=10&type=Active&pag=1" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= round(($qtd_active * 100 / $qtd_all), 3) ?><sup style="font-size: 20px">%</sup></h3>
                <p>pass rate of vendors</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a href="painel.php?exe=vendors/index&entries=10&type=3&pag=1" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?= $qtd_pending ?></h3>
                <p>Vendors Pending</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="painel.php?exe=vendors/index&entries=10&type=2&pag=1" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?= $qtd_rejected ?></h3>
                <p>Vendors Rejecteds</p>
            </div>
            <div class="icon">
                <i class="ion ion-person"></i>
            </div>
            <a href="painel.php?exe=vendors/index&entries=10&type=1&pag=1" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
</div>
