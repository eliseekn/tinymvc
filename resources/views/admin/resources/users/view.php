<?php $this->layout('admin/layout', [
    'page_title' => __('details') . ' | Administration'
]) ?>

<?php $this->start('page_content') ?>

<?php if (user_session()->alerts && !empty($alerts)) : $this->insert('partials/alert', $alerts); endif ?>

<div class="card shadow-sm">
    <div class="card-header"><?= __('details') ?></div>

    <div class="card-body">
        <div class="form-group row">
            <p class="col-sm-2 col-form-label">ID</p>
            <div class="col-form-label col-sm-10 font-weight-bold"><?= $user->id ?></div>
        </div>

        <div class="form-group row">
            <p class="col-sm-2 col-form-label"><?= __('name') ?></p>
            <div class="col-form-label col-sm-10 font-weight-bold"><?= $user->name ?></div>
        </div>

        <div class="form-group row">
            <p class="col-sm-2 col-form-label"><?= __('email') ?></p>
            <div class="col-form-label col-sm-10 font-weight-bold"><?= $user->email ?></div>
        </div>

        <div class="form-group row">
            <p class="col-sm-2 col-form-label"><?= __('phone') ?></p>
            <div class="col-form-label col-sm-10 font-weight-bold"><?= $user->phone ?></div>
        </div>

        <div class="form-group row">
            <p class="col-sm-2 col-form-label"><?= __('company') ?></p>
            <div class="col-form-label col-sm-10 font-weight-bold"><?= $user->company ?></div>
        </div>

        <div class="form-group row">
            <p class="col-sm-2 col-form-label"><?= __('created_at') ?></p>
            <div class="col-form-label col-sm-10 font-weight-bold">
                <?= \App\Helpers\DateHelper::format($user->created_at)->human() ?>
            </div>
        </div>

        <div class="form-group row">
            <p class="col-sm-2 col-form-label"><?= __('updated_at') ?></p>
            <div class="col-form-label col-sm-10 font-weight-bold">
                <?php $user->updated_at !== $user->created_at ? print(\App\Helpers\DateHelper::format($user->updated_at)->human()) : print('-') ?>
            </div>
        </div>

        <div class="form-group row">
            <p class="col-form-label col-sm-2"><?= __('role') ?></p>
            <div class="col-form-label col-sm-10 font-weight-bold"><?= $user->role ?></div>
        </div>

        <div class="form-group row">
            <p class="col-form-label col-sm-2"><?= __('status') ?></p>

            <div class="col-form-label col-sm-10 font-weight-bold">
                <?php if ($user->active) : ?>
                <span class="badge badge-success"><?= __('active') ?></span>
                <?php else : ?>
                <span class="badge badge-danger"><?= __('inactive') ?></span>
                <?php endif ?>
            </div>
        </div>
    </div>

    <div class="card-footer">
        <a href="<?= absolute_url('/admin/resources/users/edit/' . $user->id) ?>" class="btn btn-outline-dark">
            <?= __('edit') ?>
        </a>

        <a href="<?= absolute_url('/admin/resources/users/new') ?>" class="btn btn-outline-dark ml-2">
            <?= __('new') ?>
        </a>
        
        <confirm-delete 
            type="text" 
            content="<?= __('delete') ?>" 
            action="<?= absolute_url('/admin/resources/users/delete/' . $user->id) ?>">
        </confirm-delete>

        <a href="<?= absolute_url('/admin/resources/users') ?>" class="btn btn-outline-dark ml-2">
            <?= __('back') ?>
        </a>
    </div>
</div>

<?php $this->stop() ?>
