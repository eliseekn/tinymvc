<?php $this->layout('admin/layout', [
    'page_title' => __('new') . ' | Administration'
]) ?>

<?php $this->start('page_content') ?>

<?php if (auth()->alerts && !empty($alerts)) : $this->insert('partials/alert', $alerts); endif ?>

<div class="card shadow-sm">
    <div class="card-header"><?= __('new') ?></div>

    <form method="post" action="<?= absolute_url('admin/resources/galleries/create') ?>">
        <?= csrf_token_input() ?>

        <div class="card-body">
            <div class="form-group row">
                <label for="title" class="col-sm-2 col-form-label"><?= __('title') ?></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control <?php isset($errors->title) ? print('is-invalid') : print('') ?>" value="<?= $inputs->title ?? '' ?>" aria-describedby="title-error" name="title" id="title">
                    
                    <?php if(isset($errors->title)) : ?>
                    <div id="title-error" class="invalid-feedback">
                        <?= $errors->title ?>
                    </div>
                    <?php endif ?>
                </div>
            </div>

            <div class="form-group row">
                <label for="medias" class="col-sm-2 col-form-label"><?= __('medias') ?></label>
                <div class="col-sm-10">
                    <?php $this->insert('partials/medias', ['medias' => $medias]) ?>

                    <gallery-modal></gallery-modal>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-outline-dark loading"><?= __('create') ?></button>
            <button type="reset" class="btn btn-outline-dark mx-2"><?= __('reset') ?></button>
            <a href="<?= absolute_url('admin/resources/galleries') ?>" class="btn btn-outline-dark"><?= __('cancel') ?></a>
        </div>
    </form>
</div>

<?php $this->stop() ?>
