<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noindex, nofollow">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <?= $this->section('styles') ?>
    <link rel="stylesheet" href="<?= absolute_url('/public/css/admin.css') ?>">
    <title><?= $page_title ?></title>
</head>

<body>

    <?php if (flash_messages()) :
        $this->insert('partials/alert', get_flash_messages());
    endif ?>

    <div class="d-flex" id="wrapper">
        <div class="border-right shadow-sm min-vh-100" id="sidebar-wrapper">
            <div class="sidebar-title bg-light">
                <?= config('app.name') ?>
            </div>

            <div class="list-group list-group-flush">
                <a href="<?= absolute_url('/admin/dashboard') ?>" class="list-group-item list-group-item-action">
                    <i class="fa fa-home <?php if (url_exists('dashboard')) : echo 'text-primary'; endif ?>"></i> <?= __('dashboard') ?>
                </a>
                <a href="<?= absolute_url('/admin/roles') ?>" class="list-group-item list-group-item-action">
                    <i class="fa fa-dot-circle <?php if (url_exists('roles')) : echo 'text-primary'; endif ?>"></i> <?= __('roles') ?>
                </a>
                <a href="<?= absolute_url('/admin/users') ?>" class="list-group-item list-group-item-action">
                    <i class="fa fa-dot-circle <?php if (url_exists('users')) : echo 'text-primary'; endif ?>"></i> <?= __('users') ?>
                </a>
            </div>
        </div>

        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm px-4">
                <button class="btn border-dark" id="sidebar-toggler" title="Toggle sidebar">
                    <i class="fa fa-bars"></i>
                </button>

                <div class="ml-auto d-flex">
                    <?php 
                    if (get_user_session()->notifications) : 
                        $notifications = \App\Database\Models\NotificationsModel::select()->where('status', 'unread')->firstOf(4); 
                    ?>
                    <div class="dropdown mr-3">
                        <button class="btn" type="button" id="dropdown-notifications" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bell fa-lg"></i>

                            <?php if (count($notifications) > 0) : ?>
                            <span class="bg-danger notifications-icon"></span>
                            <?php endif ?>
                        </button>

                        <div class="dropdown-menu dropdown-menu-right py-0" aria-labelledby="dropdown-notifications" style="z-index: 1111">
                            <p class="font-weight-bold mb-0 px-4 py-2">Notifications (<?= count($notifications) ?>)</p>

                            <div class="dropdown-divider my-0"></div>

                            <?php foreach ($notifications as $notification) : ?>
                            <div class="dropdown-item py-2" style="width: 400px;">
                                <p class="mb-0 text-wrap">
                                    <?= $notification->message ?>
                                </p>
                                
                                <div class="d-flex align-items-center justify-content-between small">
                                    <span class="text-muted">
                                        <?= time_elapsed(\Carbon\Carbon::parse($notification->created_at, get_user_session()->timezone)->locale(get_user_session()->lang), 1)?>
                                    </span>

                                    <a class="text-primary" href="<?= absolute_url('/admin/notifications/update/' . $notification->id) ?>">
                                        <?= __('mark_as_read') ?>
                                    </a>
                                </div>
                            </div>
                            <?php endforeach ?>

                            <div class="dropdown-divider my-0"></div>

                            <div class="px-4 py-2">
                                <a class="text-primary" href="<?= absolute_url('/admin/notifications') ?>">
                                    <?= __('view_more') ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endif ?>

                    <div class="dropdown">
                        <button class="btn btn-danger dropdown-toggle" type="button" id="dropdown-menu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?= get_user_session()->name ?>
                        </button>

                        <div class="dropdown-menu dropdown-menu-right py-0" aria-labelledby="dropdown-menu">
                            <a class="dropdown-item py-2" href="<?= absolute_url('/admin/settings/' . get_user_session()->id) ?>">
                                <i class="fa fa-cog"></i> <?= __('settings') ?>
                            </a>

                            <div class="dropdown-divider my-0"></div>

                            <a class="dropdown-item py-2" href="<?= absolute_url('/logout') ?>">
                            <i class="fa fa-power-off text-danger"></i> <?= __('logout') ?>
                            </a>
                        </div>
                    </div>
                </div>
            </nav>

            <div class="container-fluid">
                <?= $this->section('page_content') ?>
            </div>
        </div>
    </div>

    <script defer src="https://use.fontawesome.com/releases/v5.13.0/js/all.js"></script>
    <script defer src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script defer src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <?= $this->section('scripts') ?>
    <script defer src="<?= absolute_url('/public/js/index.js') ?>"></script>

    <?php if (get_user_session()->theme === 'dark') : ?>
    <script defer src="<?= absolute_url('/public/js/theme.js') ?>"></script>
    <?php endif ?>
</body>

</html>