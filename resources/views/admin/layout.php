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
    <div class="d-flex" id="wrapper">
        <div class="bg-light border-light border-right min-vh-100" id="sidebar-wrapper">
            <div class="sidebar-title bg-dark text-light">
                Administration
            </div>

            <div class="list-group list-group-flush">
                <a href="<?= absolute_url('/admin/dashboard') ?>" class="list-group-item list-group-item-action bg-light">
                    <i class="fa fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="<?= absolute_url('/admin/roles') ?>" class="list-group-item list-group-item-action bg-light">
                    <i class="fa fa-dot-circle <?php if (exists_uri('roles')) : echo 'text-primary'; endif ?>"></i> Roles
                </a>
                <a href="<?= absolute_url('/admin/users') ?>" class="list-group-item list-group-item-action bg-light">
                    <i class="fa fa-dot-circle <?php if (exists_uri('users')) : echo 'text-primary'; endif ?>"></i> Users
                </a>
            </div>
        </div>

        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
                <button class="btn border-light text-light" id="sidebar-toggler" title="Toggle sidebar">
                    <i class="fa fa-bars"></i>
                </button>

                <div class="dropdown ml-auto">
                    <button class="btn btn-danger dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?= get_user_session()->name ?>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="<?= absolute_url('/logout') ?>">
                            <i class="fa fa-power-off text-danger"></i> Log out
                        </a>
                    </div>
                </div>
            </nav>

            <div class="container-fluid p-4">
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
</body>

</html>