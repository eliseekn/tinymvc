<?php $this->layout('admin/layout', [
    'page_title' => 'TinyMVC | Administration dashboard'
]) ?>

<?php $this->start('styles') ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">

<?php $this->stop() ?>

<?php $this->start('page_content') ?>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm mb-4 mb-md-0">
            <div class="card-header d-flex justify-content-between align-items-center ">
                <span class=""><?= __('total_users') ?></span>

                <a href="<?= absolute_url('/admin/users') ?>" title="Users">
                    <i class="fa fa-dot-circle text-dark"></i>
                </a>
            </div>

            <div class="card-body">
                <donut-chart el="total-users-donut" data=<?= json_encode([
                    ['label' => 'Total', 'value' => count($users)],
                    ['label' => 'Online', 'value' => count($online_users)],
                    ['label' => 'Offline', 'value' => count($users) - count($online_users)],
                    ['label' => 'Active', 'value' => count($active_users)],
                    ['label' => 'Inactive', 'value' => count($users) - count($active_users)]
                ]) ?>>
                    <div id="total-users-donut" style="height: 200px"></div>
                </donut-chart>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center ">
                <span class=""><?= __('registered_users') ?></span>

                <a href="<?= absolute_url('/admin/users/new') ?>" title="New user">
                    <i class="fa fa-dot-circle text-dark"></i>
                </a>
            </div>

            <div class="card-body">
                <bars-chart el="users-count-bars" data=<?= json_encode($users_metrics) ?> xkey="month" ykeys=<?= json_encode(['value']) ?> labels=<?= json_encode(['Count']) ?>>
                    <div id="users-count-bars" style="height: 200px"></div>
                </bars-chart>
            </div>
        </div>
    </div>
</div>

<?php $this->stop() ?>

<?php $this->start('scripts') ?>

<script defer src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>

<?php $this->stop() ?>