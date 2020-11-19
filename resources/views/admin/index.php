<?php $this->layout('admin/layout', [
    'page_title' => 'TinyMVC | Administration dashboard'
]) ?>

<?php $this->start('styles') ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">

<?php $this->stop() ?>

<?php $this->start('page_content') ?>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm mb-4 mb-md-0 h-100">
            <div class="card-header d-flex justify-content-between align-items-center ">
                <span class=""><?= __('all_users') ?></span>

                <a href="<?= absolute_url('/admin/resources/users') ?>" title="<?= __('view_all') ?>">
                    <i class="fa fa-dot-circle text-dark"></i>
                </a>
            </div>

            <div class="card-body">
                <donut-chart 
                    el="users-donut" 
                    data=<?= json_encode([
                        ['label' => 'Total', 'value' => count($users)],
                        ['label' => 'Active', 'value' => count($active_users)],
                        ['label' => 'Inactive', 'value' => count($users) - count($active_users)]
                    ]) ?>>
                    <div id="users-donut" style="height: 200px"></div>
                </donut-chart>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-center ">
                <span class=""><?= __('registered_users') ?></span>

                <div class="d-flex">
                    <select id="users-trends-bars" name="users-trends-bars" class="custom-select" data-url="<?= absolute_url('/api/metrics/users') ?>">
                        <option value="weeks">This Week</option>
                        <option value="months" selected>This Year</option>
                    </select>
                </div>
            </div>

            <div class="card-body">
                <bars-chart 
                    data=<?= json_encode($users_metrics) ?> 
                    xkey="month" 
                    ykeys=<?= json_encode(['value']) ?> 
                    labels=<?= json_encode(['Count']) ?>>
                </bars-chart>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm mb-4 mb-md-0 h-100">
            <div class="card-header d-flex justify-content-between align-items-center ">
                <span class=""><?= __('resources') ?></span>
            </div>

            <div class="card-body">
                <a href="<?= absolute_url('/admin/resources/users/new') ?>" class="btn btn-block btn-outline-dark">
                    <?= __('new_user') ?>
                </a>

                <a href="<?= absolute_url('/admin/resources/roles/new') ?>" class="btn btn-block btn-outline-dark">
                    <?= __('new_role') ?>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm mb-4 mb-md-0 h-100">
            <div class="card-header d-flex justify-content-between align-items-center ">
                <span class=""><?= __('latest_notifications') ?></span>

                <div class="d-flex flex-lg-row flex-column mt-lg-0 mt-2">
                    <a href="<?= absolute_url('/admin/account/notifications') ?>" class="btn btn-outline-dark">
                        <?= __('view_all') ?>
                    </a>

                    <create-notification 
                        title="<?= __('create') ?>"
                        action="<?= absolute_url('/admin/account/notifications/create') ?>" 
                        modal_title="<?= __('create_notification') ?>" 
                        modal_button_title="<?= __('submit') ?>" 
                        modal_button_cancel="<?= __('cancel') ?>" 
                        csrf_token='<?= csrf_token_input() ?>'>
                    </create-notification>
                </div>
            </div>

            <div class="card-body">
                <table class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th scope="col"><i class="fa fa-sort"></i> <?= __('message') ?></th>
                            <th scope="col"><i class="fa fa-sort"></i> <?= __('created_at') ?></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($notifications as $notification) : ?>
                        <tr>
                            <td><?= $notification->message ?></td>
                            <td><?= time_elapsed(\Carbon\Carbon::parse($notification->created_at, user_session()->timezone)->locale(user_session()->lang), 1) ?></td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>

                
            </div>
        </div>
    </div>
</div>

<?php $this->stop() ?>

<?php $this->start('scripts') ?>

<script defer src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>

<?php $this->stop() ?>