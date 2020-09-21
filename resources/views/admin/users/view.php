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
if (session_has_flash_messages()) : 
    $this->insert('partials/notifications', [
        'messages' => get_flash_messages(),
        'display' => 'toast'
    ]);
endif 
?>

<div class="card">
    <div class="card-header bg-dark text-white lead">View user</div>

    <div class="card-body">
        <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">ID</label>
            <div class="col-form-label col-sm-10 font-weight-bold"><?= $user->id ?></div>
        </div>

        <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">Name</label>
            <div class="col-form-label col-sm-10 font-weight-bold"><?= $user->name ?></div>
        </div>

        <div class="form-group row">
            <label for="username" class="col-sm-2 col-form-label">Email address</label>
            <div class="col-form-label col-sm-10 font-weight-bold"><?= $user->email ?></div>
        </div>

        <div class="form-group row">
            <legend class="col-form-label col-sm-2">Role</legend>
            <div class="col-form-label col-sm-10 font-weight-bold"><?php $user->role === 'admin' ? print('Admin') : print('User') ?></div>
        </div>

        <div class="form-group row">
            <legend class="col-form-label col-sm-2">Status</legend>
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
