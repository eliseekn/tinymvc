<?php $this->layout('admin/layout', [
    'page_title' => 'New role | Administration'
]) ?>

<?php $this->start('styles') ?>

<link rel="stylesheet" href="https://cdn.quilljs.com/1.3.6/quill.snow.css">

<?php $this->stop() ?>

<?php $this->start('page_content') ?>

<?php $this->insert('partials/breadcrumb', [
    'items' => [
        'Roles' => absolute_url('/admin/roles'),
        'New' => ''
    ]
]) ?>

<?php if (flash_messages()) : 
    $this->insert('partials/notifications', get_flash_messages());
endif ?>

<div class="card">
    <div class="card-header bg-dark text-white lead">New role</div>

    <form id="create-role" data-url="<?= absolute_url('/admin/roles/create') ?>">
        <?= generate_csrf_token() ?>

        <div class="card-body">
            <div class="form-group row">
                <label for="title" class="col-sm-2 col-form-label">Title</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="title" id="title">
                </div>
            </div>

            <div class="form-group row">
                <label for="editor" class="col-sm-2 col-form-label">Description</label>
                <div class="col-sm-10 mb-4">
                    <text-editor form="#create-role" content=""></text-editor>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary loading">Create</button>
            <button type="reset" class="btn btn-secondary mx-2">Reset</button>
            <a href="<?= absolute_url('/admin/roles') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php $this->stop() ?>

<?php $this->start('scripts') ?>

<script defer src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<?php $this->stop() ?>
