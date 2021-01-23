<?php $this->layout('admin/layout', [
    'page_title' => __('details') . ' | Administration'
]) ?>

<?php $this->start('page_content') ?>

<?php if (auth()->alerts && !empty($alerts)) : $this->insert('partials/alert', $alerts); endif ?>

<div class="card shadow-sm">
    <div class="card-header"><?= __('details') ?></div>

    <div class="card-body">
        <div class="form-group row">
            <p class="col-sm-2 col-form-label"><?= __('media') ?></p>
            <div class="col-form-label col-sm-10 font-weight-bold">
                <div class="card w-25">
                <?php if (in_array(get_file_extension($media->filename), \App\Database\Models\MediasModel::FORMATS[0])) : ?>
                <img class="card-img-top" src="<?= $media->url ?>" width="200" height="200">
                <?php elseif (in_array(get_file_extension($media->filename), \App\Database\Models\MediasModel::FORMATS[1])) : ?>
                <video class="card-img-top" width="200" height="200">
                    <source src="<?= $media->url ?>"></source>
                </video>
                <?php elseif (in_array(get_file_extension($media->filename), \App\Database\Models\MediasModel::FORMATS[2])) : ?>
                <audio controls class="card-img-top" width="200" height="200">
                    <source src="<?= $media->url ?>"></source>
                </audio>
                <?php endif ?>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <p class="col-sm-2 col-form-label"><?= __('filename') ?></p>
            <div class="col-form-label col-sm-10 font-weight-bold"><?= $media->filename ?></div>
        </div>

        <div class="form-group row">
            <p class="col-sm-2 col-form-label"><?= __('title') ?></p>
            <div class="col-form-label col-sm-10 font-weight-bold"><?= $media->title ?></div>
        </div>

        <div class="form-group row">
            <p class="col-sm-2 col-form-label"><?= __('description') ?></p>
            <div class="col-form-label col-sm-10 font-weight-bold"><?= $media->description ?></div>
        </div>

        <div class="form-group row">
            <p class="col-sm-2 col-form-label"><?= __('url') ?></p>
            <div class="col-form-label col-sm-10 font-weight-bold"><?= $media->url ?></div>
        </div>

        <div class="form-group row">
            <p class="col-sm-2 col-form-label"><?= __('created_at') ?></p>
            <div class="col-form-label col-sm-10 font-weight-bold">
                <?= \App\Helpers\DateHelper::format($media->created_at)->human() ?>
            </div>
        </div>

        <div class="form-group row">
            <p class="col-sm-2 col-form-label"><?= __('updated_at') ?></p>
            <div class="col-form-label col-sm-10 font-weight-bold">
                <?php $media->updated_at !== $media->created_at ? print(\App\Helpers\DateHelper::format($media->updated_at)->human()) : print('-') ?>
            </div>
        </div>
    </div>

    <div class="card-footer">
        <a href="<?= absolute_url('admin/resources/medias/edit/' . $media->id) ?>" class="btn btn-outline-dark">
            <?= __('edit') ?>
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
