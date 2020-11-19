<?php $this->layout('admin/layout', [
    'page_title' => __('edit') . ' | Administration'
]) ?>

<?php $this->start('styles') ?>

<link rel="stylesheet" href="https://cdn.quilljs.com/1.3.6/quill.snow.css">

<?php $this->stop() ?>

<?php $this->start('page_content') ?>

<?php if (user_session()->alerts) :
    if (session_alerts()) :
        $this->insert('partials/alert', get_alerts());
    endif;
endif ?>

<div class="card shadow-sm">
    <div class="card-header"><?= __('edit') ?></div>

    <form id="update-role" data-url="<?= absolute_url('/admin/resources/roles/update/' . $role->id) ?>">
        <?= csrf_token_input() ?>

        <div class="card-body">
            <div class="form-group row">
                <label for="title" class="col-sm-2 col-form-label"><?= __('title') ?></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="title" id="title" value="<?= $role->title ?>">
                </div>
            </div>

            <div class="form-group row">
                <label for="editor" class="col-sm-2 col-form-label"><?= __('description') ?></label>
                <div class="col-sm-10  mb-md-4 mb-5">
                    <text-editor form="#update-role" content="<?= $role->description ?>"></text-editor>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-outline-dark loading"><?= __('update') ?></button>
            <button type="reset" class="btn btn-outline-dark mx-2"><?= __('reset') ?></button>
            <a href="<?= absolute_url('/admin/resources/roles') ?>" class="btn btn-outline-dark"><?= __('cancel') ?></a>
        </div>
    </form>
</div>

<?php $this->stop() ?>

<?php $this->start('scripts') ?>

<script defer src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<?php $this->stop() ?>
