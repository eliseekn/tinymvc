<?php 
$this->layout('admin/layout', [
    'page_title' => 'View user | Administration'
]) 
?>

<?php $this->start('page_content') ?>

<?php
$this->insert('partials/breadcrumb', [
    'items' => [
        'Users' => absolute_url('/admin/users'),
        'View' => ''
    ]
]);
?>

<?php 
if (flash_messages()) : 
    $this->insert('partials/notifications', get_flash_messages());
endif 
?>

<div class="card">
    <div class="card-header bg-dark text-white lead">View user</div>

    <div class="card-body">
        <div class="form-group row">
            <p class="col-sm-2 col-form-label">ID</p>
            <div class="col-form-label col-sm-10 font-weight-bold"><?= $user->id ?></div>
        </div>

        <div class="form-group row">
            <p class="col-sm-2 col-form-label">Name</p>
            <div class="col-form-label col-sm-10 font-weight-bold"><?= $user->name ?></div>
        </div>

        <div class="form-group row">
            <p class="col-sm-2 col-form-label">Email address</p>
            <div class="col-form-label col-sm-10 font-weight-bold"><?= $user->email ?></div>
        </div>

        <div class="form-group row">
            <p class="col-sm-2 col-form-label">Created at</p>
            <div class="col-form-label col-sm-10 font-weight-bold"><?= date('Y-m-d', strtotime($user->created_at)) ?></div>
        </div>

        <div class="form-group row">
            <p class="col-form-label col-sm-2">Role</p>
            <div class="col-form-label col-sm-10 font-weight-bold"><?= $user->role ?></div>
        </div>

        <div class="form-group row">
            <p class="col-form-label col-sm-2">Account</p>
            <div class="col-form-label col-sm-10 font-weight-bold">
                <?php if ($user->active) : ?>
                <span class="badge badge-pill badge-success">Active</span>
                <?php else : ?>
                <span class="badge badge-pill badge-danger">Inactive</span>
                <?php endif ?>
            </div>
        </div>

        <div class="form-group row">
            <p class="col-form-label col-sm-2">Status</p>
            <div class="col-form-label col-sm-10 font-weight-bold">
                <?php if ($user->online) : ?>
                <span class="badge badge-pill badge-success">Online</span>
                <?php else : ?>
                <span class="badge badge-pill badge-danger">Offline</span>
                <?php endif ?>
            </div>
        </div>
    </div>

    <div class="card-footer">
        <a href="<?= absolute_url('/admin/users/edit/' . $user->id) ?>" class="btn btn-primary mr-2">Edit</a>
        <confirm-delete type="text" content="Delete" action="<?= absolute_url('/admin/users/delete/' . $user->id) ?>" redirect="<?= current_url() ?>"></confirm-delete>
        <a href="<?= absolute_url('/admin/users') ?>" class="btn btn-secondary ml-2">Cancel</a>
    </div>
</div>

<?php $this->stop() ?>
