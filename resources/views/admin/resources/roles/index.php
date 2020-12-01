<?php $this->layout('admin/layout', [
    'page_title' => __('roles') . ' | Administration'
]) ?>

<?php $this->start('page_content') ?>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card card-metrics bg-light shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between">
                <p><i class="fa fa-user-shield fa-lg"></i> Total</p>
                <p class="font-weight-bold"><?= $roles->getTotalItems() ?></p>
            </div>
        </div>
    </div>
</div>

<?php if (user_session()->alerts && !empty($alerts)) : $this->insert('partials/alert', $alerts); endif ?>

<div class="card shadow-sm">
    <div class="card-header">
        <div class="d-flex flex-lg-row flex-column align-items-lg-center justify-content-lg-between">
            <span><?= __('roles') ?></span>

            <div class="d-flex flex-lg-row flex-column mt-lg-0 mt-2">
                <span class="mr-md-3">
                    <input type="search" class="form-control" id="filter" placeholder="<?= __('search') ?>">
                </span>

                <span class="mt-lg-0 mt-2">
                    <a href="<?= absolute_url('/admin/resources/roles/new') ?>" class="btn btn-outline-dark"><?= __('new') ?></a>
                    
                    <upload-modal 
                        action="<?= absolute_url('/admin/resources/roles/import') ?>" 
                        title="<?= __('import') ?>" 
                        modal_title="<?= __('upload_modal_title') ?>" 
                        modal_button_title="<?= __('submit') ?>" 
                        modal_button_cancel="<?= __('cancel') ?>" 
                        csrf_token='<?= csrf_token_input() ?>'>
                    </upload-modal>
                    
                    <export-modal 
                        action="<?= absolute_url('/admin/resources/roles/export') ?>" 
                        title="<?= __('export') ?>" 
                        modal_title="<?= __('export') ?>" 
                        modal_button_title="<?= __('export') ?>" 
                        modal_button_cancel="<?= __('cancel') ?>" 
                        csrf_token='<?= csrf_token_input() ?>'>
                    </export-modal>

                    <button class="btn btn-danger" id="bulk-delete" data-url="<?= absolute_url('/admin/resources/roles/delete') ?>">
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
                        <th scope="col"><i class="fa fa-sort"></i> <?= __('title') ?></th>
                        <th scope="col"><i class="fa fa-sort"></i> <?= __('slug') ?></th>
                        <th scope="col"><i class="fa fa-sort"></i> <?= __('description') ?></th>
                        <th scope="col"><i class="fa fa-sort"></i> <?= __('created_at') ?></th>
                        <th scope="col"><i class="fa fa-sort"></i> <?= __('updated_at') ?></th>
                        <th scope="col"></th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($roles as $key => $role) : ?>
                    <tr>
                        <td>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="<?= $role->id ?>" data-id="<?= $role->id ?>">
                                <label class="custom-control-label" for="<?= $role->id ?>"></label>
                            </div>
                        </td>

                        <td><?= $roles->getFirstItem() + $key + 1 ?></td>
                        <td><?= $role->title ?></td>
                        <td><?= $role->slug ?></td>
                        <td><?= html_entity_decode($role->description) ?></td>
                        <td><?= \App\Helpers\DateHelper::format($role->created_at)->human() ?></td>
                        <td><?php $role->updated_at !== $role->created_at ? print(\App\Helpers\DateHelper::format($role->updated_at)->human()) : print('-') ?></td>

                        <td>
                            <a class="btn text-dark p-1" href="<?= absolute_url('/admin/resources/roles/view/' . $role->id) ?>" title="View item">
                                <i class="fa fa-eye"></i>
                            </a>

                            <a class="btn text-dark p-1" href="<?= absolute_url('/admin/resources/roles/edit/' . $role->id) ?>" title="Edit item">
                                <i class="fa fa-edit"></i>
                            </a>

                            <confirm-delete 
                                type="icon" 
                                content='<i class="fa fa-trash-alt"></i>' 
                                action="<?= absolute_url('/admin/resources/roles/delete/' . $role->id) ?>">
                            </confirm-delete>
                        </td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer d-flex align-items-center justify-content-between">
        <span><?= __('total_results') ?> <span class="font-weight-bold"><?= $roles->getTotalItems() ?></span></span>
        <span>Showing <span class="font-weight-bold"><?= $roles->getPageTotalItems() === 0 ? $roles->getFirstItem() : $roles->getFirstItem() + 1 ?></span> to <span class="font-weight-bold"><?= $roles->getPageTotalItems() + $roles->getFirstItem() ?></span></span>

        <?php $this->insert('partials/pagination', [
            'pagination' => $roles
        ]) ?>
    </div>
</div>

<?php $this->stop() ?>