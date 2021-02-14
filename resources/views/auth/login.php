<?php $this->layout('layouts/auth', [
    'page_title' => __('login', true)
]) ?>

<?php $this->start('page_content') ?>

<h1 class="pb-3 text-center"><?= __('login', true) ?></h1>

<?php if (!empty($alerts)) : $this->insert('partials/alert', $alerts); endif ?>

<?php if(auth_attempts_exceeded()) : 
    $this->insert('partials/alert', [
        'type' => 'danger',
        'message' => __('auth_attempts_exceeded', true),
        'display' => 'default',
        'dismiss' => false
    ]);
endif ?>

<div class="card shadow p-4">
    <form method="post" action="<?= absolute_url('authenticate') ?>">
        <?= csrf_token_input() ?>

        <div class="form-group">
            <label for="email"><?= __('email', true) ?></label>
            <input type="email" id="email" name="email" class="form-control <?php isset($errors->email) ? print('is-invalid') : print('') ?>" value="<?= $inputs->email ?? '' ?>" aria-describedby="email-error">

            <?php if(isset($errors->email)) : ?>
            <div id="email-error" class="invalid-feedback">
                <?= $errors->email ?>
            </div>
            <?php endif ?>
        </div>

        <div class="form-group">
            <label for="password"><?= __('password', true) ?></label>

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

        <div class="d-flex flex-column flex-lg-row justify-content-lg-between justify-content-center mb-3 mb-lg-0 mx-auto">
            <div class="form-group custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="remember" name="remember">
                <label class="custom-control-label" for="remember"><?= __('remember', true) ?></label>
            </div>

            <a href="<?= absolute_url('password/forgot') ?>"><?= __('forgot_password', true) ?></a>
        </div>

        <button type="submit" class="btn btn-block btn-primary loading" <?php auth_attempts_exceeded() ? print('disabled') : print('') ?>>
            <?= __('submit', true) ?>
        </button>
    </form>

    <p class="mt-4 text-center">
        <?= __('no_account', true) ?> <a href="<?= absolute_url('signup') ?>"><?= __('signup_here', true) ?></a>
    </p>
</div>

<?php $this->stop() ?>