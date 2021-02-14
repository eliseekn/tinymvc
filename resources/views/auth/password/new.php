<?php $this->layout('layouts/auth', [
    'page_title' => __('reset_password', true)
]) ?>

<?php $this->start('page_content') ?>

<h1 class="pb-3 text-center"><?= __('reset_password', true) ?></h1>

<?php if (!empty($alerts)) : $this->insert('partials/alert', $alerts); endif ?>

<div class="card shadow p-4">
    <form method="post" action="<?= absolute_url('password/update') ?>">
        <div class="form-group">
            <label for="email"><?= __('email', true) ?></label>
            <input type="email" id="email" name="email" class="form-control" value="<?= $email ?>">
        </div>

        <div class="form-group">
            <label for="password"><?= __('new_password', true) ?></label>

            <div class="d-flex align-items-center">
                <input type="password" id="password" name="password" class="form-control">

                <span class="btn" id="password-toggler" title="Toggle display">
                    <i class="fa fa-eye-slash"></i>
                </span>
            </div>
        </div>

        <button type="submit" class="btn btn-block btn-primary loading"><?= __('submit', true) ?></button>
    </form>
</div>

<?php $this->stop() ?>