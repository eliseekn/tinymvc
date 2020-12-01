<?php $this->layout('admin/layout', [
    'page_title' => 'TinyMVC | Dashboard'
]) ?>

<?php $this->start('styles') ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">

<?php $this->stop() ?>

<?php $this->start('page_content') ?>

<?php if (user_session()->alerts && !empty($alerts)) : $this->insert('partials/alert', $alerts); endif ?>

<?php if (user_session()->role !== 'customer') :?>
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm mb-4 mb-md-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><?= __('all_users') ?></span>

                <a href="<?= absolute_url('/admin/resources/users') ?>" class="btn btn-outline-dark">
                    <?= __('view_all') ?>
                </a>
            </div>

            <div class="card-body">
                <donut-chart 
                    el="users-donut" 
                    data=<?= json_encode([
                        ['label' => 'Total', 'value' => $total_users],
                        ['label' => 'Active', 'value' => $active_users],
                        ['label' => 'Inactive', 'value' => $inactive_users]
                    ]) ?>>
                    <div id="users-donut" style="height: 200px"></div>
                </donut-chart>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center ">
                <span><?= __('registered_users') ?></span>

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
<?php endif ?>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm mb-4 mb-md-0">
            <div class="card-header">
                <div class="d-flex flex-lg-row flex-column align-items-lg-center justify-content-lg-between">
                    <span><?= __('latest_messages') ?></span>

                    <div class="d-flex flex-lg-row flex-column mt-lg-0 mt-2">
                        <send-message
                            type="button" 
                            action="<?= absolute_url('/admin/account/messages/create') ?>" 
                            title="<?= __('new') ?>" 
                            content='<?= __('new') ?>'
                            recipient="0"
                            modal_title="<?= __('new') ?>" 
                            modal_button_title="<?= __('submit') ?>" 
                            modal_button_cancel="<?= __('cancel') ?>" 
                            csrf_token='<?= csrf_token_input() ?>'>
                        </send-message>

                        <a href="<?= absolute_url('/admin/account/messages') ?>" class="btn btn-outline-dark">
                            <?= __('view_all') ?>
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <table class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th scope="col"><i class="fa fa-sort"></i> <?= __('sender') ?></th>
                            <th scope="col"><i class="fa fa-sort"></i> <?= __('message') ?></th>
                            <th scope="col"><i class="fa fa-sort"></i> <?= __('created_at') ?></th>
                            <th scope="col"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($messages as $message) : ?>
                        <tr>
                            <td><?= $message->sender_email ?></td>
                            <td><?= $message->message ?></td>
                            <td><?= time_elapsed(\App\Helpers\DateHelper::format($message->created_at)->get(), 1) ?></td>
                            <td>
                                <a class="btn text-dark p-1 <?php if ($message->recipient_status === 'read') : echo 'disabled'; endif ?>" href="<?= absolute_url('/admin/account/messages/update/' . $message->id) ?>" <?php if ($message->recipient_status === 'unread') : echo 'title="' . __("mark_as_read") . '"'; endif ?>>
                                    <?php if ($message->recipient_status === 'unread') : ?>
                                    <i class="fa fa-eye-slash"></i>
                                    <?php else : ?>
                                    <i class="fa fa-eye"></i>
                                    <?php endif ?>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center ">
                <span><?= __('latest_notifications') ?></span>

                <div class="d-flex flex-lg-row flex-column mt-lg-0 mt-2">
                    <create-notification 
                        title="<?= __('create') ?>"
                        action="<?= absolute_url('/admin/account/notifications/create') ?>" 
                        modal_title="<?= __('create_notification') ?>" 
                        modal_button_title="<?= __('submit') ?>" 
                        modal_button_cancel="<?= __('cancel') ?>" 
                        csrf_token='<?= csrf_token_input() ?>'>
                    </create-notification>

                    <a href="<?= absolute_url('/admin/account/notifications') ?>" class="btn btn-outline-dark">
                        <?= __('view_all') ?>
                    </a>
                </div>
            </div>

            <div class="card-body">
                <table class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th scope="col"><i class="fa fa-sort"></i> <?= __('message') ?></th>
                            <th scope="col"><i class="fa fa-sort"></i> <?= __('created_at') ?></th>
                            <th scope="col"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($notifications as $notification) : ?>
                        <tr>
                            <td><?= $notification->message ?></td>
                            <td><?= time_elapsed(\App\Helpers\DateHelper::format($notification->created_at)->get(), 1) ?></td>
                            <td>
                                <a class="btn text-dark p-1 <?php if ($notification->status === 'read') : echo 'disabled'; endif ?>" href="<?= absolute_url('/admin/account/notifications/update/' . $notification->id) ?>" <?php if ($notification->status === 'unread') : echo 'title="' . __("mark_as_read") . '"'; endif ?>>
                                    <?php if ($notification->status === 'unread') : ?>
                                    <i class="fa fa-eye-slash"></i>
                                    <?php else : ?>
                                    <i class="fa fa-eye"></i>
                                    <?php endif ?>
                                </a>
                            </td>
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