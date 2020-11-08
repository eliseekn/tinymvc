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
            <div class="col-form-label col-sm-10 font-weight-bold"><?= $role->id ?></div>
        </div>

        <div class="form-group row">
            <p class="col-sm-2 col-form-label"><?= __('title') ?></p>
            <div class="col-form-label col-sm-10 font-weight-bold"><?= $role->title ?></div>
        </div>

        <div class="form-group row">
            <p class="col-sm-2 col-form-label"><?= __('slug') ?></p>
            <div class="col-form-label col-sm-10 font-weight-bold"><?= $role->slug ?></div>
        </div>

        <div class="form-group row">
            <p class="col-sm-2 col-form-label"><?= __('description') ?></p>
            <div class="col-form-label col-sm-10 font-weight-bold"><?= html_entity_decode($role->description) ?></div>
        </div>

        <div class="form-group row">
            <p class="col-sm-2 col-form-label"><?= __('created_at') ?></p>
            <div class="col-form-label col-sm-10 font-weight-bold">
                <?= \Carbon\Carbon::parse($role->created_at)->locale(user_session()->lang)->isoFormat('MMM Do, YYYY') ?>
            </div>
        </div>
    </div>

    <div class="card-footer">
        <a href="<?= absolute_url('/admin/resources/roles/edit/' . $role->id) ?>" class="btn btn-primary"><?= __('edit') ?></a>
        <confirm-delete type="text" content="<?= __('delete') ?>" action="<?= absolute_url('/admin/resources/roles/delete/' . $role->id) ?>" redirect="<?= current_url() ?>"></confirm-delete>
        <a href="<?= absolute_url('/admin/resources/roles') ?>" class="btn btn-secondary ml-2"><?= __('cancel') ?></a>
    </div>
</div>

<?php $this->stop() ?>