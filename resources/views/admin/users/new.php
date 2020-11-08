<?php $this->layout('admin/layout', [
    'page_title' => __('new') . ' | Administration'
]) ?>

<?php $this->start('page_content') ?>

<?php 
if (user_session()->alerts) :
    if (flash_messages()) :
        $this->insert('partials/alert', get_flash_messages());
    endif;
endif
?>

<div class="card shadow-sm">
    <div class="card-header"><?= __('new') ?></div>

    <form method="post" action="<?= absolute_url('/admin/resources/users/create') ?>">
        <?= generate_csrf_token() ?>

        <div class="card-body">
            <div class="form-group row">
                <label for="name" class="col-sm-2 col-form-label"><?= __('name') ?></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" id="name">
                </div>
            </div>

            <div class="form-group row">
                <label for="email" class="col-sm-2 col-form-label"><?= __('email') ?></label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" name="email" id="email">
                </div>
            </div>

            <div class="form-group row">
                <label for="password" class="col-sm-2 col-form-label"><?= __('password') ?></label>

                <div class="d-flex align-items-center col-sm-10">
                    <input type="password" id="password" name="password" class="form-control">

                    <span class="btn" id="password-toggler" title="Toggle display">
                        <i class="fa fa-eye-slash"></i>
                    </span>
                </div>
            </div>

            <fieldset class="form-group">
                <div class="row">
                    <legend class="col-form-label col-sm-2 pt-0"><?= __('role') ?></legend>
                    <div class="col-sm-10">
                        <?php foreach ($roles as $role) : ?>

                        <div class="custom-control custom-radio custom-control-inline">
                            <input class="custom-control-input" type="radio" name="role" id="<?= $role->slug ?>" value="<?= $role->slug ?>">
                            <label class="custom-control-label" for="<?= $role->slug ?>"><?= $role->title ?></label>
                        </div>
                        
                        <?php endforeach ?>
                    </div>
                </div>
            </fieldset>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary loading"><?= __('create') ?></button>
            <button type="reset" class="btn btn-secondary mx-2"><?= __('reset') ?></button>
            <a href="<?= absolute_url('/admin/resources/users') ?>" class="btn btn-secondary"><?= __('cancel') ?></a>
        </div>
    </form>
</div>

<?php $this->stop() ?>
