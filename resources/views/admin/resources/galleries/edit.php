<?php $this->layout('admin/layout', [
    'page_title' => __('edit') . ' | Administration'
]) ?>

<?php $this->start('page_content') ?>

<?php if (auth()->alerts && !empty($alerts)) : $this->insert('partials/alert', $alerts); endif ?>

<div class="card shadow-sm">
    <div class="card-header"><?= __('edit') ?></div>

    <form method="post" action="<?= absolute_url('admin/resources/galleries/update/' . $gallerie->id) ?>">
        <?= csrf_token_input() ?>

        <div class="card-body"></div>

        <div class="card-footer">
            <button type="submit" class="btn btn-outline-dark loading"><?= __('update') ?></button>
            <button type="reset" class="btn btn-outline-dark mx-2"><?= __('reset') ?></button>
            <a href="<?= absolute_url('admin/resources/galleries') ?>" class="btn btn-outline-dark"><?= __('cancel') ?></a>
        </div>
    </form>
</div>

<?php $this->stop() ?>
