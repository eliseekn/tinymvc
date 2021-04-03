<?php $this->layout('layouts/admin', [
    'page_title' => __('users') . ' | Administration'
]) ?>

<?php $this->start('page_content') ?>

<metrics-cards>
    <metric-card-item 
        title="<?= __('total') ?>" 
        icon="fa fa-users fa-lg" 
        data="<?= $users->getTotalItems() ?>"
        columns="4">
    </metric-card-item>

    <metric-card-item 
        title="<?= __('active') ?>" 
        icon="fa fa-user-check fa-lg" 
        data="<?= $active_users ?>"
        columns="4">
    </metric-card-item>
</metrics-cards>

<?php if (auth()->alerts && !empty($alerts)) : $this->insert('partials/alert', $alerts); endif ?>

<div class="card shadow-sm">
    <div class="card-header">
        <div class="d-flex flex-lg-row flex-column align-items-lg-center justify-content-lg-between">
            <span><?= __('users') ?></span>

            <div class="d-flex flex-lg-row flex-column">
                <span class="mr-md-3 mt-lg-0 mt-2">
                    <input type="search" class="form-control" id="filter" placeholder="<?= __('search') ?>">
                </span>

                <span class="mt-lg-0 mt-2">
                    <a href="<?= absolute_url('admin/resources/users/new') ?>" class="btn btn-outline-dark">
                        <?= __('new') ?>
                    </a>
                    
                    <export-modal action="<?= absolute_url('admin/resources/users/export') ?>">
                        <button class="btn btn-outline-dark mx-2"><?= __('export') ?></button>
                    </export-modal>

                    <button class="btn btn-danger mt-lg-0 mt-2" id="bulk-delete" data-url="<?= absolute_url('admin/resources/users/delete') ?>">
                        <?= __('bulk_delete') ?>
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

                        <th scope="col"><i class="fa fa-sort"></i> ID</th>
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
                    <?php foreach ($users as $user) : ?>
                    <tr>
                        <td>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="<?= $user->id ?>" data-id="<?= $user->id ?>">
                                <label class="custom-control-label" for="<?= $user->id ?>"></label>
                            </div>
                        </td>

                        <td><?= $user->id ?></td>

                        <td>
                            <avatar-icon name="<?= $user->name ?>"></avatar-icon>
                        </td>

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

                        <td><?= date_helper($user->created_at)->human() ?></td>
                        <td><?php $user->updated_at !== $user->created_at ? print(date_helper($user->updated_at)->human()) : print('-') ?></td>

                        <td>
                            <a class="btn text-dark p-1" href="<?= absolute_url('admin/resources/users/read', $user->id) ?>" title="<?= __('details') ?>">
                                <i class="fa fa-eye"></i>
                            </a>

                            <a class="btn text-dark p-1" href="<?= absolute_url('admin/resources/users/edit', $user->id) ?>" title="<?= __('edit') ?>">
                                <i class="fa fa-edit"></i>
                            </a>

                            <delete-item action="<?= absolute_url('admin/resources/users/delete', $user->id) ?>">
                                <a class="btn text-danger p-1" title="<?= __('delete') ?>">
                                    <i class="fa fa-trash-alt"></i>
                                </a>
                            </delete-item>
                        </td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer d-flex align-items-center justify-content-between">
        <span><?= __('total_results') ?> <span class="font-weight-bold"><?= $users->getTotalItems() ?></span></span>
        <span><?= __('showing') ?> <span class="font-weight-bold"><?= $users->getPageTotalItems() === 0 ? $users->getFirstItem() : $users->getFirstItem() + 1 ?></span> <?= __('to') ?> <span class="font-weight-bold"><?= $users->getPageTotalItems() + $users->getFirstItem() ?></span></span>

        <?php $this->insert('partials/pagination', ['pagination' => $users]) ?>
    </div>
</div>

<?php $this->stop() ?>
