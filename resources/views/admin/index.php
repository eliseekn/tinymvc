<?php

use App\Helpers\MetricsHelper;

$this->layout('admin/layout', [
    'page_title' => 'TinyMVC | Administration dashboard'
])
?>

<?php $this->start('styles') ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">

<?php $this->stop() ?>

<?php $this->start('page_content') ?>

<div class="card-columns">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center bg-dark lead">
            <span class="text-white">Users</span>

            <a href="<?= absolute_url('/admin/users') ?>">
                <i class="fa fa-dot-circle text-white"></i>
            </a>
        </div>

        <div class="card-body">
            <!-- <div class="mb-3">
                <p class="card-text">Total: <span class="font-weight-bold"><?= count($users) ?></span></p>
                <p class="card-text">Online: <span class="font-weight-bold"><?= count($online_users) ?></span></p>
                <p class="card-text">Latest registered: <span class="font-weight-bold font-italic"><?= $users[0]->name ?></span> - <span class="font-italic"><?= get_time_elapsed($users[0]->created_at, 1) ?></span></p>
            </div> -->

            <div class="row">
                <div class="col">
                    <div id="donut-chart" style="height: 200px"></div>
                </div>

                <div class="col">
                    <div id="bars-chart" style="height: 200px; width: 700px"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->stop() ?>

<?php $this->start('scripts') ?>

<script defer src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        new Morris.Bar({
            element: 'bars-chart',
            resize: true,
            data: <?= json_encode(MetricsHelper::getCount('users', 'id', 'months')) ?>,
            xkey: 'month',
            ykeys: ['value'],
            labels: ['Value']
        })

        new Morris.Donut({
            element: 'donut-chart',
            resize: true,
            data: [
                {label: "Total", value: <?= count($users) ?>},
                {label: "Online", value: <?= count($online_users) ?>},
                {label: "Offline", value: <?= count($users) - count($online_users) ?>}
            ]
        })
    })
</script>

<?php $this->stop() ?>