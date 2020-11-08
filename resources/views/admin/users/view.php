<?php $this->layout('admin/layout', [
    'page_title' => __('summary') . ' | Administration'
]) ?>

<?php $this->start('page_content') ?>

<?php 
if (user_session()->alerts) :
    if (flash_messages()) :
        $this->insert('partials/alert', get_flash_messages());
    endif;
endif
?>

<div class="card shadow-sm">
    <div class="card-header"><?= __('summary') ?></div>

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
            <p class="col-sm-2 col-form-label"><?= __('created_at') ?></p>
            <div class="col-form-label col-sm-10 font-weight-bold">
                <?= \Carbon\Carbon::parse($user->created_at)->locale(user_session()->lang)->isoFormat('MMM Do, YYYY') ?>
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
        <a href="<?= absolute_url('/admin/resources/users/edit/' . $user->id) ?>" class="btn btn-primary"><?= __('edit') ?></a>

        <?php if (!\App\Helpers\AuthHelper::hasRole('administrator')) : ?>
        <confirm-delete type="text" content="<?= __('delete') ?>" action="<?= absolute_url('/admin/resources/users/delete/' . $user->id) ?>" redirect="<?= current_url() ?>"></confirm-delete>
        <?php endif ?>
        
        <a href="<?= absolute_url('/admin/resources/users') ?>" class="btn btn-secondary ml-2"><?= __('cancel') ?></a>
    </div>
</div>

<?php $this->stop() ?>
