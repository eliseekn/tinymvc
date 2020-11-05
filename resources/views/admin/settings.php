<?php $this->layout('admin/layout', [
    'page_title' => __('settings') . ' | Administration'
]) ?>

<?php $this->start('page_content') ?>

<?php if (flash_messages()) :
    $this->insert('partials/alert', get_flash_messages());
endif ?>

<form method="post" action="<?= absolute_url('/admin/settings/update/' . user_session()->id) ?>">
    <?= generate_csrf_token() ?>

    <div class="row mb-4">
        <div class="col-12 col-lg-6 mb-4 mb-lg-0">
            <div class="card shadow-sm h-100">
                <div class="card-header "><?= __('profile') ?></div>

                <div class="card-body">
                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label"><?= __('name') ?></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="name" id="name" value="<?= $user->name ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-sm-3 col-form-label"><?= __('email') ?></label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" name="email" id="email" value="<?= $user->email ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header "><?= __('security') ?></div>

                <div class="card-body">
                    <div class="form-group row">
                        <label for="password" class="col-sm-3 col-form-label"><?= __('new_password') ?></label>

                        <div class="d-flex align-items-center col-sm-9">
                            <input type="password" id="password" name="password" class="form-control">

                            <span class="btn" id="password-toggler" title="Toggle display">
                                <i class="fa fa-eye-slash"></i>
                            </span>
                        </div>
                    </div>

                    <div class="form-group row d-flex align-items-center">
                        <label for="two-factor" class="col-sm-3 col-form-label">Two-Factor auth.</label>
                        <div class="col-sm-9">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="two-factor" id="two-factor" <?php if ($user->two_factor) : echo 'checked'; endif ?>>
                                <label class="custom-control-label" for="two-factor"></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12 col-lg-6 mb-4 mb-lg-0">
            <div class="card shadow-sm h-100">
                <div class="card-header "><?= __('preferences') ?></div>

                <div class="card-body">
                    <div class="form-group row">
                        <label for="lang" class="col-sm-3 col-form-label"><?= __('language') ?></label>

                        <div class="col-sm-9">
                            <select id="lang" name="lang" class="custom-select">
                                <option value="en" <?php if ($user->lang === 'en') : echo 'selected'; endif ?>>en</option>
                                <option value="fr" <?php if ($user->lang === 'fr') : echo 'selected'; endif ?>>fr</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="timezone" class="col-sm-3 col-form-label"><?= __('timezone') ?></label>

                        <div class="col-sm-9">
                            <timezone-picker selected="<?= $user->timezone ?>"></timezone-picker>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="currency" class="col-sm-3 col-form-label"><?= __('currency') ?></label>

                        <div class="col-sm-9">
                            <currency-picker selected="<?= $user->currency ?>"></currency-picker>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header "><?= __('dashboard') ?></div>

                <div class="card-body">
                    <div class="form-group row d-flex align-items-center">
                        <label for="theme" class="col-sm-5 col-form-label">Dark theme</label>
                        <div class="col-sm-7">
                            <theme-switch checked="<?php if ($user->theme === 'dark') : echo 'checked'; endif ?>"></theme-switch>
                        </div>
                    </div>

                    <div class="form-group row d-flex align-items-center">
                        <label for="notifications" class="col-sm-5 col-form-label"><?= __('display_notifications') ?></label>
                        <div class="col-sm-7">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="notifications" id="notifications" <?php if ($user->notifications) : echo 'checked'; endif ?>>
                                <label class="custom-control-label" for="notifications"></label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row d-flex align-items-center">
                        <label for="notifications-email" class="col-sm-5 col-form-label"><?= __('receive_email_notifications') ?></label>
                        <div class="col-sm-7">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="notifications-email" id="notifications-email" <?php if ($user->notifications_email) : echo 'checked'; endif ?>>
                                <label class="custom-control-label" for="notifications-email"></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="">
        <button type="submit" class="btn btn-primary loading"><?= __('save') ?></button>
    </div>
</form>

<?php $this->stop() ?>
