<?php 
$this->layout('admin/layout', [
    'page_title' => 'New user | Administration',
    'page_description' => 'New user page'
]) 
?>

<?php $this->start('page_content') ?>

<nav class="d-flex justify-content-end">
    <ol class="breadcrumb bg-white">
        <li class="breadcrumb-item">
            <a href="<?= absolute_url('/admin') ?>">
                <i class="fa fa-home"></i> Dashboard
            </a>
        </li>

        <li class="breadcrumb-item">
            <a href="<?= absolute_url('/admin/users') ?>">Users</a>
        </li>

        <li class="breadcrumb-item active" aria-current="page">New</li>
    </ol>
</nav>

<?php 
if (session_has_flash_messages()) : 
    $this->insert('partials/flash', [
        'messages' => get_flash_messages()
    ]);
endif 
?>

<div class="card">
    <div class="card-header bg-dark text-white lead">New user</div>

    <form method="post" action="<?= absolute_url('/admin/users/create') ?>">
        <?= generate_csrf_token() ?>

        <div class="card-body">
            <div class="form-group row">
                <label for="name" class="col-sm-2 col-form-label">Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" id="name">
                </div>
            </div>

            <div class="form-group row">
                <label for="username" class="col-sm-2 col-form-label">Email address</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" name="email" id="email">
                </div>
            </div>

            <div class="form-group row">
                <label for="password" class="col-sm-2 col-form-label">Password</label>

                <div class="d-flex align-items-center col-sm-10">
                    <input type="password" id="password" name="password" class="form-control">

                    <span class="btn" id="password-toggler" title="Toggle display">
                        <i class="fa fa-eye-slash"></i>
                    </span>
                </div>
            </div>

            <fieldset class="form-group">
                <div class="row">
                    <legend class="col-form-label col-sm-2 pt-0">Role</legend>
                    <div class="col-sm-10">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input class="custom-control-input" type="radio" name="role" id="admin" value="admin">
                            <label class="custom-control-label" for="admin">Admin</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input class="custom-control-input" type="radio" name="role" id="user" value="user" checked>
                            <label class="custom-control-label" for="user">User</label>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary loading">Create</button>
            <button type="reset" class="btn btn-secondary mx-2">Reset</button>
            <a href="<?= absolute_url('/admin/users') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php $this->stop() ?>
