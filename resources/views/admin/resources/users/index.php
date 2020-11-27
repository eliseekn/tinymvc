<?php $this->layout('admin/layout', [
    'page_title' => __('users') . ' | Administration'
]) ?>

<?php $this->start('page_content') ?>

<div class="row mb-4">
    <div class="col-md-4 mb-md-0 mb-4">
        <div class="card card-metrics bg-light shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between">
                <p><i class="fa fa-users fa-lg"></i> <?= __('total') ?></p>
                <p class="font-weight-bold"><?= $users->getTotalItems() ?></p>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-md-0 mb-4">
        <div class="card card-metrics bg-light shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between">
                <p><i class="fa fa-user-check fa-lg"></i> <?= __('active') ?></p>
                <p class="font-weight-bold"><?= count($active_users) ?></p>
            </div>
        </div>
    </div>
</div>

<?php if (user_session()->alerts && !empty($alerts)) : $this->insert('partials/alert', $alerts); endif ?>

<div class="card shadow-sm">
    <div class="card-header">
        <div class="d-flex flex-lg-row flex-column align-items-lg-center justify-content-lg-between">
            <span><?= __('users') ?></span>

            <div class="d-flex flex-lg-row flex-column mt-lg-0 mt-2">
                <span class="mr-md-3">
                    <input type="search" class="form-control" id="filter" placeholder="<?= __('search') ?>">
                </span>

                <span class="mt-lg-0 mt-2">
                    <a href="<?= absolute_url('/admin/resources/users/new') ?>" class="btn btn-outline-dark"><?= __('new') ?></a>
                    
                    <upload-modal 
                        action="<?= absolute_url('/admin/resources/users/import') ?>" 
                        title="<?= __('import') ?>" 
                        modal_title="<?= __('upload_modal_title') ?>" 
                        modal_button_title="<?= __('submit') ?>" 
                        modal_button_cancel="<?= __('cancel') ?>" 
                        csrf_token='<?= csrf_token_input() ?>'>
                    </upload-modal>
                    
                    <export-modal 
                        action="<?= absolute_url('/admin/resources/users/export') ?>" 
                        title="<?= __('export') ?>" 
                        modal_title="<?= __('export') ?>" 
                        modal_button_title="<?= __('export') ?>" 
                        modal_button_cancel="<?= __('cancel') ?>" 
                        csrf_token='<?= csrf_token_input() ?>'>
                    </export-modal>

                    <button class="btn btn-danger" id="bulk-delete" data-url="<?= absolute_url('/admin/resources/users/delete') ?>">
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
                        <th scope="col"><i class="fa fa-sort"></i> <?= __('name') ?></th>
                        <th scope="col"><i class="fa fa-sort"></i> <?= __('email') ?></th>
                        <th scope="col"><i class="fa fa-sort"></i> <?= __('phone') ?></th>
                        <th scope="col"><i class="fa fa-sort"></i> <?= __('company') ?></th>
                        <th scope="col"><i class="fa fa-sort"></i> <?= __('role') ?></th>
                        <th scope="col"><i class="fa fa-sort"></i> <?= __('status') ?></th>
                        <th scope="col"><i class="fa fa-sort"></i> <?= __('created_at') ?></th>
                        <th scope="col"><i class="fa fa-sort"></i> <?= __('updated_at') ?></th>
                        <th scope="col"></th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($users as $key => $user) : ?>
                    <tr>
                        <td>
                            <?php if ($user->role !== 'admin') : ?>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="<?= $user->id ?>" data-id="<?= $user->id ?>">
                                <label class="custom-control-label" for="<?= $user->id ?>"></label>
                            </div>
                            <?php endif ?>
                        </td>

                        <td><?= $users->getFirstItem() + $key + 1 ?></td>
                        <td><?= $user->name ?></td>
                        <td><?= $user->email ?></td>
                        <td><?= $user->phone ?></td>
                        <td><?= $user->company ?></td>
                        <td><?= $user->role ?></td>

                        <td>
                            <?php if ($user->active) : ?>
                            <span class="badge badge-success"><?= __('active') ?></span>
                            <?php else : ?>
                            <span class="badge badge-danger"><?= __('inactive') ?></span>
                            <?php endif ?>
                        </td>

                        <td><?= \Carbon\Carbon::parse($user->created_at, user_session()->timezone)->locale(user_session()->lang)->isoFormat('MMM Do, YYYY') ?></td>
                        <td><?php $user->updated_at !== $user->created_at ? print(\Carbon\Carbon::parse($user->updated_at, user_session()->timezone)->locale(user_session()->lang)->isoFormat('MMM Do, YYYY')) : print('-') ?></td>

                        <td>
                            <?php if ($user->role !== 'administrator') : ?>
                            <a class="btn text-dark p-1" href="<?= absolute_url('/admin/resources/users/view/' . $user->id) ?>" title="View item">
                                <i class="fa fa-eye"></i>
                            </a>

                            <a class="btn text-dark p-1" href="<?= absolute_url('/admin/resources/users/edit/' . $user->id) ?>" title="Edit item">
                                <i class="fa fa-edit"></i>
                            </a>

                            <confirm-delete 
                                type="icon" 
                                content='<i class="fa fa-trash-alt"></i>' 
                                action="<?= absolute_url('/admin/resources/users/delete/' . $user->id) ?>">
                            </confirm-delete>
                            <?php endif ?>
                        </td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer d-flex align-items-center justify-content-between">
        <span><?= __('total_results') ?> <span class="font-weight-bold"><?= $users->getTotalItems() ?></span></span>
        <span>Showing <span class="font-weight-bold"><?= $users->getPageTotalItems() === 0 ? $users->getFirstItem() : $users->getFirstItem() + 1 ?></span> to <span class="font-weight-bold"><?= $users->getPageTotalItems() + $users->getFirstItem() ?></span></span>

        <?php $this->insert('partials/pagination', [
            'pagination' => $users
        ]) ?>
    </div>
</div>

<?php $this->stop() ?>
