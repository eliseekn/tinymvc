<?php $this->layout('layouts/admin', [
    'page_title' => __('edit') . ' | Administration'
]) ?>

<?php $this->start('page_content') ?>

<?php if (auth()->alerts && !empty($alerts)) : $this->insert('partials/alert', $alerts); endif ?>

<div class="card shadow-sm">
    <div class="card-header"><?= __('edit') ?></div>

    <form method="post" action="<?= absolute_url('admin/resources/users/update', $user->id) ?>">
        <?= csrf_token_input() ?>
        <?= method_input('patch') ?>

        <div class="card-body">
            <div class="form-group row">
                <label for="name" class="col-sm-2 col-form-label"><?= __('name') ?></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control <?php isset($errors->name) ? print('is-invalid') : print('') ?>" name="name" id="name" value="<?= $inputs->name ?? $user->name ?>" aria-describedby="name-error">
                
                    <?php if(isset($errors->name)) : ?>
                    <div id="name-error" class="invalid-feedback">
                        <?= $errors->name ?>
                    </div>
                    <?php endif ?>
                </div>
            </div>

            <div class="form-group row">
                <label for="email" class="col-sm-2 col-form-label"><?= __('email') ?></label>
                <div class="col-sm-10">
                    <input type="email" class="form-control <?php isset($errors->email) ? print('is-invalid') : print('') ?>" name="email" id="email" value="<?= $inputs->email ?? $user->email ?>" aria-describedby="email-error">

                    <?php if(isset($errors->email)) : ?>
                    <div id="email-error" class="invalid-feedback">
                        <?= $errors->email ?>
                    </div>
                    <?php endif ?>
                </div>
            </div>

            <div class="form-group row">
                <label for="phone" class="col-sm-2 col-form-label"><?= __('phone') ?></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control <?php isset($errors->phone) ? print('is-invalid') : print('') ?>" name="phone" id="phone" value="<?= $inputs->phone ?? $user->phone ?>" aria-describedby="phone-error">

                    <?php if(isset($errors->phone)) : ?>
                    <div id="phone-error" class="invalid-feedback">
                        <?= $errors->phone ?>
                    </div>
                    <?php endif ?>
                </div>
            </div>

            <div class="form-group row">
                <label for="company" class="col-sm-2 col-form-label"><?= __('company') ?></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="company" id="company" value="<?= $user->company ?>">
                </div>
            </div>

            <div class="form-group row">
                <label for="password" class="col-sm-2 col-form-label"><?= __('new_password') ?></label>

                <div class="d-flex align-items-center col-sm-10">
                    <input type="password" id="password" name="password" class="form-control">

                    <span class="btn" id="password-toggler" title="Toggle display">
                        <i class="fa fa-eye-slash"></i>
                    </span>
                </div>
            </div>

            <fieldset class="form-group">
                <div class="row">
                    <legend class="col-form-label col-sm-2 pt-0"><?= __('role') ?></legend>
                    <div class="col-sm-10">
                        <?php foreach ($roles as $role) : ?>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input class="custom-control-input" type="radio" name="role" id="<?= $role->name ?>" value="<?= $role->name ?>" <?php if ($user->role === $role->name) : echo 'checked'; endif ?>>
                            <label class="custom-control-label" for="<?= $role->name ?>"><?= $role->name ?></label>
                        </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </fieldset>
            
            <fieldset class="form-group">
                <div class="row">
                    <legend class="col-form-label col-sm-2 pt-0"><?= __('status') ?></legend>
                    <div class="col-sm-10">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input class="custom-control-input" type="radio" name="account_state" id="account_active" value="1" <?php if ($user->active) : echo 'checked'; endif ?>>
                            <label class="custom-control-label" for="account_active"><?= __('active') ?></label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input class="custom-control-input" type="radio" name="account_state" id="account_incative" value="0" <?php if (!$user->active) : echo 'checked'; endif ?>>
                            <label class="custom-control-label" for="account_incative"><?= __('inactive') ?></label>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-dark loading"><?= __('update') ?></button>
            <button type="reset" class="btn btn-outline-dark mx-2"><?= __('reset') ?></button>
            <a href="<?= absolute_url('admin/resources/users') ?>" class="btn btn-outline-dark"><?= __('cancel') ?></a>
        </div>
    </form>
</div>

<?php $this->stop() ?>
