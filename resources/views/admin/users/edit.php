<?php $this->layout('admin/layout', [
    'page_title' => 'Edit user | Administration'
]) ?>

<?php $this->start('page_content') ?>

<?php $this->insert('partials/breadcrumb', [
    'items' => [
        'Users' => absolute_url('/admin/users'),
        'Edit' => ''
    ]
]) ?>

<?php if (flash_messages()) : 
    $this->insert('partials/notifications', get_flash_messages());
endif ?>

<div class="card">
    <div class="card-header bg-dark text-white lead">Edit user</div>

    <form method="post" action="<?= absolute_url('/admin/users/update/' . $user->id) ?>">
        <?= generate_csrf_token() ?>

        <div class="card-body">
            <div class="form-group row">
                <label for="name" class="col-sm-2 col-form-label">Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" id="name" value="<?= $user->name ?>">
                </div>
            </div>

            <div class="form-group row">
                <label for="email" class="col-sm-2 col-form-label">Email address</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" name="email" id="email" value="<?= $user->email ?>">
                </div>
            </div>

            <div class="form-group row">
                <label for="password" class="col-sm-2 col-form-label">New password</label>

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
                        <?php foreach ($roles as $role) : ?>

                        <div class="custom-control custom-radio custom-control-inline">
                            <input class="custom-control-input" type="radio" name="role" id="<?= $role->slug ?>" value="<?= $role->slug ?>" <?php if ($user->role === $role->slug) : echo 'checked'; endif ?>>
                            <label class="custom-control-label" for="<?= $role->slug ?>"><?= $role->title ?></label>
                        </div>

                        <?php endforeach ?>
                    </div>
                </div>
            </fieldset>

            <?php if ($user->role !== 'admin') : ?>
            
            <fieldset class="form-group">
                <div class="row">
                    <legend class="col-form-label col-sm-2 pt-0">Account state</legend>
                    <div class="col-sm-10">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input class="custom-control-input" type="radio" name="account_state" id="account_active" value="1" <?php if ($user->active) : echo 'checked'; endif ?>>
                            <label class="custom-control-label" for="account_active">Active</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input class="custom-control-input" type="radio" name="account_state" id="account_incative" value="0" <?php if (!$user->active) : echo 'checked'; endif ?>>
                            <label class="custom-control-label" for="account_incative">Inactive</label>
                        </div>
                    </div>
                </div>
            </fieldset>

            <?php endif ?>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary loading">Update</button>
            <button type="reset" class="btn btn-secondary mx-2">Reset</button>
            <a href="<?= absolute_url('/admin/users') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php $this->stop() ?>
