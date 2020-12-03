<?php $this->layout('admin/layout', [
    'page_title' => __('messages') . ' | Administration'
]) ?>

<?php $this->start('page_content') ?>

<div class="row mb-4">
    <div class="col-md-4 mb-md-0 mb-4">
        <div class="card card-metrics bg-light shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between">
                <p><i class="fa fa-envelope fa-lg"></i> Total</p>
                <p class="font-weight-bold"><?= $messages->getTotalItems() ?></p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-metrics bg-light shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between">
                <p><i class="fa fa-eye-slash fa-lg"></i> <?= __('unread') ?></p>
                <p class="font-weight-bold"><?= $messages_unread ?></p>
            </div>
        </div>
    </div>
</div>

<?php if (user_session()->alerts && !empty($alerts)) : $this->insert('partials/alert', $alerts); endif ?>

<div class="card shadow-sm">
    <div class="card-header">
        <div class="d-flex flex-lg-row flex-column align-items-lg-center justify-content-lg-between">
            <span><?= __('messages') ?></span>

            <div class="d-flex flex-lg-row flex-column mt-lg-0 mt-2">
                <span class="mr-md-3">
                    <input type="search" class="form-control" id="filter" placeholder="<?= __('search') ?>">
                </span>

                <span class="mt-lg-0 mt-2">
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

                    <button class="btn btn-outline-dark" id="bulk-read" data-url="<?= absolute_url('/admin/account/messages/update') ?>">
                        <?= __('mark_as_read') ?>
                    </button>

                    <export-modal 
                        action="<?= absolute_url('/admin/account/messages/export') ?>" 
                        title="<?= __('export') ?>" 
                        modal_title="<?= __('export') ?>" 
                        modal_button_title="<?= __('export') ?>" 
                        modal_button_cancel="<?= __('cancel') ?>" 
                        csrf_token='<?= csrf_token_input() ?>'>
                    </export-modal>

                    <button class="btn btn-danger" id="bulk-delete" data-url="<?= absolute_url('/admin/account/messages/delete') ?>">
                        <?= __('delete') ?>
                    </button>
                </span>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th scope="col">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="select-all">
                                <label class="custom-control-label" for="select-all"></label>
                            </div>
                        </th>

                        <th scope="col"><i class="fa fa-sort"></i> #</th>
                        <th scope="col"><i class="fa fa-sort"></i> <?= __('sender') ?></th>
                        <th scope="col"><i class="fa fa-sort"></i> <?= __('recipient') ?></th>
                        <th scope="col"><i class="fa fa-sort"></i> <?= __('message') ?></th>
                        <th scope="col"><i class="fa fa-sort"></i> <?= __('created_at') ?></th>
                        <th scope="col"></th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($messages as $key => $message) : ?>
                    <tr>
                        <td>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="<?= $message->id ?>" data-id="<?= $message->id ?>">
                                <label class="custom-control-label" for="<?= $message->id ?>"></label>
                            </div>
                        </td>

                        <td><?= $messages->getFirstItem() + $key + 1 ?></td>
                        <td><?= $message->sender_email ?></td>
                        <td><?= $message->recipient_email ?></td>
                        <td><?= $message->message ?></td>
                        <td><?= time_elapsed(\App\Helpers\DateHelper::format($message->created_at)->get(), 1) ?></td>

                        <td>
                            <?php if ($message->sender_email !== user_session()->email) : ?>
                            <a class="btn text-dark p-1 <?php if ($message->recipient_status === 'read') : echo 'disabled'; endif ?>" href="<?= absolute_url('/admin/account/messages/update/' . $message->id) ?>" <?php if ($message->recipient_status === 'unread') : echo 'title="' . __("mark_as_read") . '"'; endif ?>>
                                <?php if ($message->recipient_status === 'unread') : ?>
                                <i class="fa fa-eye-slash"></i>
                                <?php else : ?>
                                <i class="fa fa-eye"></i>
                                <?php endif ?>
                            </a>

                            <send-message
                                type="icon" 
                                action="<?= absolute_url('/admin/account/messages/reply') ?>" 
                                title="<?= __('reply') ?>" 
                                content='<i class="fa fa-reply-all"></i>'
                                recipient="<?= $message->sender ?>"
                                modal_title="<?= __('reply') ?>" 
                                modal_button_title="<?= __('submit') ?>" 
                                modal_button_cancel="<?= __('cancel') ?>" 
                                csrf_token='<?= csrf_token_input() ?>'>
                            </send-message>
                            <?php endif ?>

                            <confirm-delete 
                                type="icon" 
                                content='<i class="fa fa-trash-alt"></i>' 
                                action="<?= absolute_url('/admin/account/messages/delete/' . $message->id) ?>">
                            </confirm-delete>
                        </td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer d-flex align-items-center justify-content-between">
        <span><?= __('total_results') ?> <span class="font-weight-bold"><?= $messages->getTotalItems() ?></span></span>
        <span>Showing <span class="font-weight-bold"><?= $messages->getPageTotalItems() === 0 ? $messages->getFirstItem() : $messages->getFirstItem() + 1 ?></span> to <span class="font-weight-bold"><?= $messages->getPageTotalItems() + $messages->getFirstItem() ?></span></span>

        <?php $this->insert('partials/pagination', [
            'pagination' => $messages
        ]) ?>
    </div>
</div>

<?php $this->stop() ?>
