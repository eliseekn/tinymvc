<?php $this->layout('admin/layout', [
    'page_title' => __('new') . ' | Administration'
]) ?>

<?php $this->start('styles') ?>

<link rel="stylesheet" href="https://cdn.quilljs.com/1.3.6/quill.snow.css">

<?php $this->stop() ?>

<?php $this->start('page_content') ?>

<?php if (flash_messages()) : 
    $this->insert('partials/alert', get_flash_messages());
endif ?>

<div class="card shadow-sm">
    <div class="card-header"><?= __('new') ?></div>

    <form id="create-role" data-url="<?= absolute_url('/admin/roles/create') ?>">
        <?= generate_csrf_token() ?>

        <div class="card-body">
            <div class="form-group row">
                <label for="title" class="col-sm-2 col-form-label"><?= __('title') ?></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="title" id="title">
                </div>
            </div>

            <div class="form-group row">
                <label for="editor" class="col-sm-2 col-form-label"><?= __('description') ?></label>
                <div class="col-sm-10 mb-md-4 mb-5">
                    <text-editor form="#create-role" content=""></text-editor>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary loading"><?= __('create') ?></button>
            <button type="reset" class="btn btn-secondary mx-2"><?= __('reset') ?></button>
            <a href="<?= absolute_url('/admin/roles') ?>" class="btn btn-secondary"><?= __('cancel') ?></a>
        </div>
    </form>
</div>

<?php $this->stop() ?>

<?php $this->start('scripts') ?>

<script defer src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<?php $this->stop() ?>
