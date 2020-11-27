<?php $this->layout('admin/layout', [
    'page_title' => __('new') . ' | Administration'
]) ?>

<?php $this->start('page_content') ?>

<?php if (user_session()->alerts && !empty($alerts)) : $this->insert('partials/alert', $alerts); endif ?>

<div class="card shadow-sm">
    <div class="card-header"><?= __('new') ?></div>

    <form method="post" action="<?= absolute_url('/admin/resources/users/create') ?>">
        <?= csrf_token_input() ?>

        <div class="card-body">
            <div class="form-group row">
                <label for="name" class="col-sm-2 col-form-label"><?= __('name') ?></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control <?php isset($errors->name) ? print('is-invalid') : print('') ?>" value="<?= $inputs->name ?? '' ?>" aria-describedby="name-error" name="name" id="name">
                    
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
                    <input type="email" class="form-control <?php isset($errors->email) ? print('is-invalid') : print('') ?>" value="<?= $inputs->email ?? '' ?>" aria-describedby="email-error" name="email" id="email">

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
                    <input type="text" class="form-control <?php isset($errors->phone) ? print('is-invalid') : print('') ?>" value="<?= $inputs->phone ?? '' ?>" aria-describedby="phone-error" name="phone" id="phone">

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
                    <input type="text" class="form-control" name="company" id="company" value="<?= $inputs->company ?? '' ?>">
                </div>
            </div>

            <div class="form-group row">
                <label for="password" class="col-sm-2 col-form-label"><?= __('password') ?></label>

                <div class="col-sm-10">
                    <div class="d-flex align-items-center">
                        <input type="password" id="password" name="password" class="form-control <?php isset($errors->password) ? print('is-invalid') : print('') ?>" value="<?= $inputs->password ?? '' ?>" aria-describedby="password-error">

                        <span class="btn" id="password-toggler" title="Toggle display">
                            <i class="fa fa-eye-slash"></i>
                        </span>
                    </div>
                    
                    <?php if(isset($errors->password)) : ?>
                    <div id="password-error" class="invalid-feedback d-block">
                        <?= $errors->password ?>
                    </div>
                    <?php endif ?>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-outline-dark loading"><?= __('create') ?></button>
            <button type="reset" class="btn btn-outline-dark mx-2"><?= __('reset') ?></button>
            <a href="<?= absolute_url('/admin/resources/users') ?>" class="btn btn-outline-dark"><?= __('cancel') ?></a>
        </div>
    </form>
</div>

<?php $this->stop() ?>
