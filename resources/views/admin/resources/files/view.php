<?php $this->layout('admin/layout', [
    'page_title' => __('details') . ' | Administration'
]) ?>

<?php $this->start('page_content') ?>

<?php if (auth()->alerts && !empty($alerts)) : $this->insert('partials/alert', $alerts); endif ?>

<div class="card shadow-sm">
    <div class="card-header"><?= __('details') ?></div>

    <div class="card-body">
        <div class="form-group row">
            <p class="col-sm-2 col-form-label"><?= __('file') ?></p>
            <div class="col-form-label col-sm-10 font-weight-bold">
                <div class="card w-25">
                <?php if (in_array(get_file_extension($file->filename), \App\Database\Models\FilesModel::TYPE[0])) : ?>
                <img class="card-img-top" src="<?= $file->url ?>" width="200" height="200">
                <?php elseif (in_array(get_file_extension($file->filename), \App\Database\Models\FilesModel::TYPE[1])) : ?>
                <video class="card-img-top" width="200" height="200">
                    <source src="<?= $file->url ?>"></source>
                </video>
                <?php elseif (in_array(get_file_extension($file->filename), \App\Database\Models\FilesModel::TYPE[2])) : ?>
                <audio controls class="card-img-top" width="200" height="200">
                    <source src="<?= $file->url ?>"></source>
                </audio>
                <?php else : ?>
                <a href="<?= $file->url ?>" target="_blank">
                    <i class="fa fa-file-alt fa-5x"></i>
                </a>
                <?php endif ?>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <p class="col-sm-2 col-form-label"><?= __('filename') ?></p>
            <div class="col-form-label col-sm-10 font-weight-bold"><?= $file->filename ?></div>
        </div>

        <div class="form-group row">
            <p class="col-sm-2 col-form-label"><?= __('title') ?></p>
            <div class="col-form-label col-sm-10 font-weight-bold"><?= $file->title ?></div>
        </div>

        <div class="form-group row">
            <p class="col-sm-2 col-form-label"><?= __('description') ?></p>
            <div class="col-form-label col-sm-10 font-weight-bold"><?= $file->description ?></div>
        </div>

        <div class="form-group row">
            <p class="col-sm-2 col-form-label"><?= __('url') ?></p>
            <div class="col-form-label col-sm-10 font-weight-bold"><?= $file->url ?></div>
        </div>

        <div class="form-group row">
            <p class="col-sm-2 col-form-label"><?= __('created_at') ?></p>
            <div class="col-form-label col-sm-10 font-weight-bold">
                <?= \App\Helpers\DateHelper::format($file->created_at)->human() ?>
            </div>
        </div>

        <div class="form-group row">
            <p class="col-sm-2 col-form-label"><?= __('updated_at') ?></p>
            <div class="col-form-label col-sm-10 font-weight-bold">
                <?php $file->updated_at !== $file->created_at ? print(\App\Helpers\DateHelper::format($file->updated_at)->human()) : print('-') ?>
            </div>
        </div>
    </div>

    <div class="card-footer">
        <a href="<?= absolute_url('admin/resources/files/edit/' . $file->id) ?>" class="btn btn-outline-dark">
            <?= __('edit') ?>
        </a>

        <confirm-delete 
            type="text" 
            content="<?= __('delete') ?>" 
            action="<?= absolute_url('admin/resources/files/delete/' . $file->id) ?>">
        </confirm-delete>
        
        <a href="<?= absolute_url('admin/resources/files') ?>" class="btn btn-outline-dark ml-2">
            <?= __('cancel') ?>
        </a>
    </div>
</div>

<?php $this->stop() ?>
