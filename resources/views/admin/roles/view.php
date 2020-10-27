<?php $this->layout('admin/layout', [
    'page_title' => 'View role | Administration'
]) ?>

<?php $this->start('page_content') ?>

<?php $this->insert('partials/breadcrumb', [
    'items' => [
        'Dashboard' => absolute_url('/admin/dashboard'),
        'Roles' => absolute_url('/admin/roles'),
        'View' => ''
    ]
]) ?>

<?php if (flash_messages()) :
    $this->insert('partials/alert', get_flash_messages());
endif ?>

<div class="card">
    <div class="card-header bg-dark text-white lead">View role</div>

    <div class="card-body">
        <div class="form-group row">
            <p class="col-sm-2 col-form-label">ID</p>
            <div class="col-form-label col-sm-10 font-weight-bold"><?= $role->id ?></div>
        </div>

        <div class="form-group row">
            <p class="col-sm-2 col-form-label">Title</p>
            <div class="col-form-label col-sm-10 font-weight-bold"><?= $role->title ?></div>
        </div>

        <div class="form-group row">
            <p class="col-sm-2 col-form-label">Slug</p>
            <div class="col-form-label col-sm-10 font-weight-bold"><?= $role->slug ?></div>
        </div>

        <div class="form-group row">
            <p class="col-sm-2 col-form-label">Description</p>
            <div class="col-form-label col-sm-10 font-weight-bold"><?= html_entity_decode($role->description) ?></div>
        </div>

        <div class="form-group row">
            <p class="col-sm-2 col-form-label">Created at</p>
            <div class="col-form-label col-sm-10 font-weight-bold"><?= \Carbon\Carbon::parse($role->created_at)->format('Y-m-d') ?></div>
        </div>
    </div>

    <div class="card-footer">
        <a href="<?= absolute_url('/admin/roles/edit/' . $role->id) ?>" class="btn btn-primary mr-2">Edit</a>
        <confirm-delete type="text" content="Delete" action="<?= absolute_url('/admin/roles/delete/' . $role->id) ?>" redirect="<?= current_url() ?>"></confirm-delete>
        <a href="<?= absolute_url('/admin/roles') ?>" class="btn btn-secondary ml-2">Cancel</a>
    </div>
</div>

<?php $this->stop() ?>