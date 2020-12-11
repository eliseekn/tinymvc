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
                <?= \App\Helpers\DateHelper::format($role->created_at)->human() ?>
            </div>
        </div>

        <div class="form-group row">
            <p class="col-sm-2 col-form-label"><?= __('updated_at') ?></p>
            <div class="col-form-label col-sm-10 font-weight-bold">
                <?php $role->updated_at !== $role->created_at ? print(\App\Helpers\DateHelper::format($role->updated_at)->human()) : print('-') ?>
            </div>
        </div>
    </div>

    <div class="card-footer">
        <a href="<?= absolute_url('/admin/resources/roles/edit/' . $role->id) ?>" class="btn btn-outline-dark">
            <?= __('edit') ?>
        </a>

        <a href="<?= absolute_url('/admin/resources/roles/new') ?>" class="btn btn-outline-dark ml-2">
            <?= __('new') ?>
        </a>
        
        <confirm-delete 
            type="text" 
            content="<?= __('delete') ?>" 
            action="<?= absolute_url('/admin/resources/roles/delete/' . $role->id) ?>">
        </confirm-delete>

        <a href="<?= absolute_url('/admin/resources/roles') ?>" class="btn btn-outline-dark ml-2">
            <?= __('back') ?>
        </a>
    </div>
</div>

<?php $this->stop() ?>