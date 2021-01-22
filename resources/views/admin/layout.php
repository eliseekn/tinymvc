<!DOCTYPE html>
<html lang="<?= config('app.lang') ?>">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noindex, nofollow">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <?= $this->section('styles') ?>
    <link rel="stylesheet" href="<?= absolute_url('public/css/admin.css') ?>">
    <title><?= $page_title ?></title>
</head>

<body>

    <?php if (auth()->alerts && !empty($alerts)) : $this->insert('partials/alert', $alerts); endif ?>

    <?= csrf_token_input() ?>

    <div class="wrapper">
        <div class="wrapper__sidebar border-right shadow-sm bg-white">
            <div class="sidebar-title bg-light d-flex align-items-center">
                <avatar-icon name="<?= auth()->name ?>"></avatar-icon>

                <button class="btn ml-auto d-none sidebar-close p-0">
                    <i class="fa fa-times text-dark"></i>
                </button>
            </div>

            <div class="list-group list-group-flush">
                <a href="<?= absolute_url('admin/dashboard') ?>" class="list-group-item list-group-item-action border-bottom">
                    <i class="fa fa-home <?php if (in_url('dashboard')) : echo 'text-primary'; endif ?>"></i> <?= __('dashboard') ?>
                </a>

                <button class="list-group-item list-group-item-action" id="dropdown-btn" data-target="resources-dropdown-menu">
                    <i class="fa fa-layer-group <?php if (in_url('resources')) : echo 'text-primary'; endif ?>"></i> <?= __('resources') ?>

                    <span class="float-right dropdown-caret">
                        <?php if (in_url('resources')) : ?>
                        <i class="fa fa-caret-up"></i>
                        <?php else : ?>
                        <i class="fa fa-caret-down"></i>
                        <?php endif ?>
                    </span>
                </button>

                <div class="<?php if (!in_url('resources')) : echo 'd-none'; endif ?> border-bottom" id="resources-dropdown-menu">
                    <?php if (auth()->role === \App\Database\Models\RolesModel::ROLES[0]) :?>
                    <a href="<?= absolute_url('admin/resources/roles') ?>" class="list-group-item list-group-item-action border-0 dropdown-menu-item">
                        <i class="fa fa-dot-circle <?php if (in_url('roles')) : echo 'text-primary'; endif ?>"></i> <?= __('roles') ?>
                    </a>
                    <a href="<?= absolute_url('admin/resources/users') ?>" class="list-group-item list-group-item-action border-0 dropdown-menu-item">
                        <i class="fa fa-dot-circle <?php if (in_url('users')) : echo 'text-primary'; endif ?>"></i> <?= __('users') ?>
                    </a>
                    <?php endif ?>

                    <a href="<?= absolute_url('admin/resources/medias') ?>" class="list-group-item list-group-item-action border-0 dropdown-menu-item">
                        <i class="fa fa-dot-circle <?php if (in_url('medias')) : echo 'text-primary'; endif ?>"></i> <?= __('medias') ?>
                    </a>
                </div>

                <button class="list-group-item list-group-item-action" id="dropdown-btn" data-target="account-dropdown-menu">
                    <i class="fa fa-user <?php if (in_url('account')) : echo 'text-primary'; endif ?>"></i> <?= __('account') ?>

                    <span class="float-right dropdown-caret">
                        <?php if (in_url('account')) : ?>
                        <i class="fa fa-caret-up"></i>
                        <?php else : ?>
                        <i class="fa fa-caret-down"></i>
                        <?php endif ?>
                    </span>
                </button>

                <div class="<?php if (!in_url('account')) : echo 'd-none'; endif ?> border-bottom" id="account-dropdown-menu">
                    <a href="<?= absolute_url('admin/account/notifications') ?>" class="list-group-item list-group-item-action border-0 dropdown-menu-item">
                        <i class="fa fa-dot-circle <?php if (in_url('notifications')) : echo 'text-primary'; endif ?>"></i> <?= __('notifications') ?>
                    </a>
                    <a href="<?= absolute_url('admin/account/messages') ?>" class="list-group-item list-group-item-action border-0 dropdown-menu-item">
                        <i class="fa fa-dot-circle <?php if (in_url('messages')) : echo 'text-primary'; endif ?>"></i> <?= __('messages') ?>
                    </a>
                    <a href="<?= absolute_url('admin/account/activities') ?>" class="list-group-item list-group-item-action border-0 dropdown-menu-item">
                        <i class="fa fa-dot-circle <?php if (in_url('activities')) : echo 'text-primary'; endif ?>"></i> <?= __('activities') ?>
                    </a>
                    <a href="<?= absolute_url('admin/account/settings', auth()->id) ?>" class="list-group-item list-group-item-action border-0 dropdown-menu-item">
                        <i class="fa fa-dot-circle <?php if (in_url('settings')) : echo 'text-primary'; endif ?>"></i> <?= __('settings') ?>
                    </a>
                </div>
            </div>
        </div>

        <div class="wrapper__content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm px-5 sticky-top">
                <button class="btn px-1 sidebar-toggler" title="Toggle sidebar">
                    <i class="fa fa-bars"></i>
                </button>

                <div class="ml-auto d-flex align-items-center">
                    <div id="messages-icon"></div>

                    <div id="notifications-bell"></div>

                    <a class="btn btn-sm" href="<?= absolute_url('admin/account/settings', auth()->id) ?>" title="<?= __('settings') ?>">
                        <i class="fa fa-cog fa-lg"></i>
                    </a>

                    <a class="btn btn-sm" href="<?= absolute_url('logout') ?>" title="<?= __('logout') ?>">
                        <i class="fa fa-power-off fa-lg text-danger"></i>
                    </a>
                </div>
            </nav>

            <div class="p-5">
                <?= $this->section('page_content') ?>
            </div>
        </div>
    </div>

    <script defer src="https://use.fontawesome.com/releases/v5.13.0/js/all.js"></script>
    <script defer src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script defer src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <?= $this->section('scripts') ?>
    <script defer src="<?= absolute_url('public/js/index.js') ?>"></script>

    <?php if (auth()->dark_theme) : ?>
    <script defer src="<?= absolute_url('public/js/theme.js') ?>"></script>
    <?php endif ?>
</body>

</html>