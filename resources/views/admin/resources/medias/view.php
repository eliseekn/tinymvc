<?php $this->layout('admin/layout', [
    'page_title' => __('details') . ' | Administration'
]) ?>

<?php $this->start('page_content') ?>

<?php if (auth()->alerts && !empty($alerts)) : $this->insert('partials/alert', $alerts); endif ?>

<div class="card shadow-sm">
    <div class="card-header"><?= __('details') ?></div>

    <div class="card-body">
        <div class="form-group row">
            <p class="col-sm-2 col-form-label">ID</p>
            <div class="col-form-label col-sm-10 font-weight-bold"><?= $media->id ?></div>
        </div>

        <div class="form-group row">
            <p class="col-sm-2 col-form-label"><?= __('created_at') ?></p>
            <div class="col-form-label col-sm-10 font-weight-bold">
                <?= \App\Helpers\DateHelper::format($media->created_at)->human() ?>
            </div>
        </div>
    </div>

    <div class="card-footer">
        <a href="<?= absolute_url('admin/resources/medias/edit/' . $media->id) ?>" class="btn btn-outline-dark">
            <?= __('edit') ?>
        </a>

        <a href="<?= absolute_url('admin/resources/medias/new') ?>" class="btn btn-outline-dark ml-2">
            <?= __('new') ?>
        </a>

        <confirm-delete 
            type="text" 
            content="<?= __('delete') ?>" 
            action="<?= absolute_url('admin/resources/medias/delete/' . $media->id) ?>">
        </confirm-delete>
        
        <a href="<?= absolute_url('admin/resources/medias') ?>" class="btn btn-outline-dark ml-2">
            <?= __('cancel') ?>
        </a>
    </div>
</div>

<?php $this->stop() ?>
