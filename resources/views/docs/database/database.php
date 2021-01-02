<?php $this->layout('docs/layout', [
    'page_title' => 'Database | Documentation'
]) ?>

<?php $this->start('page_content') ?>

<div class="card mb-5" id="basic-routing">
    <div class="card-header ">
        <span class=" lead">Database</span>
    </div>

    <div class="card-body">

    </div>

    <div class="card-footer d-flex justify-content-between">
        <span>Next: <a href="<?= absolute_url('docs/database/query-builder') ?>">Query Builder</a></span>
        <span>Previous: <a href="<?= absolute_url('docs/guides/redirections') ?>">URL Redirections</a></span>
    </div>
</div>

<?php $this->stop() ?>