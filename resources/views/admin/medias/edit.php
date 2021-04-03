<?php $this->layout('layouts/admin', [
    'page_title' => __('edit') . ' | Administration'
]) ?>

<?php $this->start('page_content') ?>

<?php if (auth()->alerts && !empty($alerts)) : $this->insert('partials/alert', $alerts); endif ?>

<div class="card shadow-sm">
    <div class="card-header"><?= __('edit') ?></div>

    <form method="post" action="<?= absolute_url('admin/resources/medias/update/' . $media->id) ?>">
        <?= csrf_token_input() ?>
        <?= method_input('patch') ?>

        <div class="card-body">
            <div class="form-group row">
                <label for="filename" class="col-sm-2 col-form-label"><?= __('filename') ?></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control <?php isset($errors->filename) ? print('is-invalid') : print('') ?>" name="filename" id="filename" value="<?= $inputs->filename ?? $media->filename ?>" aria-describedby="filename-error">

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
                    <input type="text" class="form-control <?php isset($errors->title) ? print('is-invalid') : print('') ?>" name="title" id="title" value="<?= $inputs->title ?? $media->title ?>" aria-describedby="title-error">
                
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
                    <input type="text" class="form-control <?php isset($errors->description) ? print('is-invalid') : print('') ?>" name="description" id="description" value="<?= $inputs->description ?? $media->description ?>" aria-describedby="description-error">

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
            <a href="<?= absolute_url('admin/resources/medias') ?>" class="btn btn-outline-dark"><?= __('cancel') ?></a>
        </div>
    </form>
</div>

<?php $this->stop() ?>