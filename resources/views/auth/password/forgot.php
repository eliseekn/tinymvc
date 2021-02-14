<?php $this->layout('layouts/auth', [
    'page_title' => __('forgot_password', true)
]) ?>

<?php $this->start('page_content') ?>

<h1 class="text-center"><?= __('forgot_password', true) ?></h1>

<div class="card my-3 mb-3">
    <div class="card-body bg-light">
        <p class="card-text">
            <?= __('reset_password_instructions', true) ?>
        </p>
    </div>
</div>

<?php if (!empty($alerts)) : $this->insert('partials/alert', $alerts); endif ?>

<div class="card shadow p-4">
    <form method="post" action="<?= absolute_url('password/notify') ?>">
        <div class="form-group">
            <label for="email"><?= __('email', true) ?></label>
            <input type="email" id="email" name="email" class="form-control">
        </div>

        <button type="submit" class="btn btn-block btn-primary loading"><?= __('submit', true) ?></button>
    </form>
</div>

<?php $this->stop() ?>