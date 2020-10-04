<?php

use App\Database\Models\UsersModel;
use App\Helpers\MetricsHelper;

$this->layout('admin/layout', [
    'page_title' => 'TinyMVC | Administration dashboard'
])
?>

<?php $this->start('styles') ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">

<?php $this->stop() ?>

<?php $this->start('page_content') ?>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center bg-dark lead">
                <span class="text-white">Total users</span>

                <a href="<?= absolute_url('/admin/users') ?>" title="Users">
                    <i class="fa fa-dot-circle text-white"></i>
                </a>
            </div>

            <div class="card-body">
                <div id="total-users-donut" style="height: 200px"></div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center bg-dark lead">
                <span class="text-white">Users count</span>

                <a href="<?= absolute_url('/admin/users/new') ?>" title="New user">
                    <i class="fa fa-dot-circle text-white"></i>
                </a>
            </div>

            <div class="card-body">
                <div id="users-count-bars" style="height: 200px"></div>
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
        new Morris.Donut({
            element: 'total-users-donut',
            resize: true,
            data: [
                {label: 'Total', value: <?= count($users) ?>},
                {label: 'Online', value: <?= count($online_users) ?>},
                {label: 'Offline', value: <?= count($users) - count($online_users) ?>},
                {label: 'Active', value: <?= count($active_users) ?>},
                {label: 'Inactive', value: <?= count($users) - count($active_users) ?>}
            ]
        })

        new Morris.Bar({
            element: 'users-count-bars',
            resize: true,
            data: <?= json_encode($users_metrics) ?>,
            xkey: 'month',
            ykeys: ['value'],
            labels: ['Count']
        })
    })
</script>

<?php $this->stop() ?>