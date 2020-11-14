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

<?php 
if (user_session()->alerts) :
    if (flash_messages()) :
        $this->insert('partials/alert', get_flash_messages());
    endif;
endif
?>

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
                    
                    <upload-modal action="<?= absolute_url('/admin/resources/roles/import') ?>" title="<?= __('import') ?>" modal_title="<?= __('upload_modal_title') ?>" modal_button_title="<?= __('submit') ?>" modal_button_cancel="<?= __('cancel') ?>"></upload-modal>
                    <export-modal action="<?= absolute_url('/admin/resources/roles/export') ?>" title="<?= __('export') ?>" modal_title="<?= __('export') ?>" modal_button_title="<?= __('export') ?>" modal_button_cancel="<?= __('cancel') ?>"></export-modal>

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

                        <td><?= $key + 1 ?></td>
                        <td><?= $role->title ?></td>
                        <td><?= $role->slug ?></td>
                        <td><?= html_entity_decode($role->description) ?></td>
                        <td><?= \Carbon\Carbon::parse($role->created_at)->locale(user_session()->lang)->isoFormat('MMM Do, YYYY') ?></td>

                        <td>
                            <a class="btn text-dark p-1" href="<?= absolute_url('/admin/resources/roles/view/' . $role->id) ?>" title="View item">
                                <i class="fa fa-eye"></i>
                            </a>

                            <a class="btn text-dark p-1" href="<?= absolute_url('/admin/resources/roles/edit/' . $role->id) ?>" title="Edit item">
                                <i class="fa fa-edit"></i>
                            </a>

                            <confirm-delete type="icon" content='<i class="fa fa-trash-alt"></i>' action="<?= absolute_url('/admin/resources/roles/delete/' . $role->id) ?>" redirect="<?= current_url()?>"></confirm-delete>
                        </td>
                    </tr>

                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer d-flex align-items-center justify-content-between">
        <span><?= __('total_results') ?> <span class="font-weight-bold"><?= $roles->getTotalItems() ?></span></span>

        <?php $this->insert('partials/pagination', [
            'pagination' => $roles
        ]) ?>
    </div>
</div>

<?php $this->stop() ?>