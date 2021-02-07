<?php $this->layout('admin/layout', [
    'page_title' => __('edit') . ' | Administration'
]) ?>

<?php $this->start('page_content') ?>

<?php if (auth()->alerts && !empty($alerts)) : $this->insert('partials/alert', $alerts); endif ?>

<div class="card shadow-sm">
    <div class="card-header"><?= __('edit') ?></div>

    <form method="post" action="<?= absolute_url('admin/resources/files/update/' . $file->id) ?>">
        <?= csrf_token_input() ?>

        <div class="card-body">
            <div class="form-group row">
                <p class="col-sm-2 col-form-label"><?= __('file') ?></p>
                <div class="col-sm-10">
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
                        <?= $file->filename ?>
                    </a>
                    <?php endif ?>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label for="filename" class="col-sm-2 col-form-label"><?= __('filename') ?></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control <?php isset($errors->filename) ? print('is-invalid') : print('') ?>" name="filename" id="filename" value="<?= $inputs->filename ?? $file->filename ?>" aria-describedby="filename-error">

                    <?php if(isset($errors->filename)) : ?>
                    <div id="filename-error" class="invalid-feedback">
                        <?= $errors->filename ?>
                    </div>
                    <?php endif ?>
                </div>
            </div>

            <div class="form-group row">
                <label for="title" class="col-sm-2 col-form-label"><?= __('title') ?></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control <?php isset($errors->title) ? print('is-invalid') : print('') ?>" name="title" id="title" value="<?= $inputs->title ?? $file->title ?>" aria-describedby="title-error">
                
                    <?php if(isset($errors->title)) : ?>
                    <div id="title-error" class="invalid-feedback">
                        <?= $errors->title ?>
                    </div>
                    <?php endif ?>
                </div>
            </div>

            <div class="form-group row">
                <label for="description" class="col-sm-2 col-form-label"><?= __('description') ?></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control <?php isset($errors->description) ? print('is-invalid') : print('') ?>" name="description" id="description" value="<?= $inputs->description ?? $file->description ?>" aria-describedby="description-error">

                    <?php if(isset($errors->description)) : ?>
                    <div id="description-error" class="invalid-feedback">
                        <?= $errors->description ?>
                    </div>
                    <?php endif ?>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-outline-dark loading"><?= __('update') ?></button>
            <button type="reset" class="btn btn-outline-dark mx-2"><?= __('reset') ?></button>
            <a href="<?= absolute_url('admin/resources/files') ?>" class="btn btn-outline-dark"><?= __('cancel') ?></a>
        </div>
    </form>
</div>

<?php $this->stop() ?>
