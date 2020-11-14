<!DOCTYPE html>
<html lang="<?= config('app.lang') ?>">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noindex, nofollow">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= absolute_url('/public/css/docs.css') ?>">
    <title><?= $page_title ?></title>
</head>

<body>
    <div class="wrapper">
        <div class="wrapper__sidebar border-right shadow-sm bg-white">
            <div class="sidebar-title bg-light d-flex align-items-center">
                <i class="fa fa-book mr-2"></i> Documentation <span class="ml-2">v2.1</span>
                
                <button class="btn ml-auto d-none sidebar-close p-0">
                    <i class="fa fa-times text-dark"></i>
                </button>
            </div>

            <div class="list-group list-group-flush">
                <a href="<?= absolute_url('/docs/getting-started') ?>" class="list-group-item list-group-item-action border-bottom">
                    <i class="fa fa-home <?php if (url_exists('getting-started')) : echo 'text-primary'; endif ?>"></i> Getting started
                </a>

                <button class="list-group-item list-group-item-action" id="dropdown-btn" data-target="guides-dropdown-menu">
                    <i class="fa fa-layer-group <?php if (url_exists('guides')) : echo 'text-primary'; endif ?>"></i> Guides

                    <span class="float-right dropdown-caret">
                        <?php if (url_exists('guides')) : ?>
                        <i class="fa fa-caret-up"></i>
                        <?php else : ?>
                        <i class="fa fa-caret-down"></i>
                        <?php endif ?>
                    </span>
                </button>

                <div class="<?php if (!url_exists('guides')) : echo 'd-none'; endif ?> border-bottom" id="guides-dropdown-menu">
                    <a href="<?= absolute_url('/docs/guides/routing') ?>" class="list-group-item list-group-item-action border-0 dropdown-menu-item">
                        <i class="fa fa-dot-circle <?php if (url_exists('routing')) : echo 'text-primary'; endif ?>"></i> Routing
                    </a>
                    <a href="<?= absolute_url('/docs/guides/middlewares') ?>" class="list-group-item list-group-item-action border-0 dropdown-menu-item">
                        <i class="fa fa-dot-circle <?php if (url_exists('middlewares')) : echo 'text-primary'; endif ?>"></i> Middlewares
                    </a>
                    <a href="<?= absolute_url('/docs/guides/controllers') ?>" class="list-group-item list-group-item-action border-0 dropdown-menu-item">
                        <i class="fa fa-dot-circle <?php if (url_exists('controllers')) : echo 'text-primary'; endif ?>"></i> Controllers
                    </a>
                    <a href="<?= absolute_url('/docs/guides/views') ?>" class="list-group-item list-group-item-action border-0 dropdown-menu-item">
                        <i class="fa fa-dot-circle <?php if (url_exists('views')) : echo 'text-primary'; endif ?>"></i> Views
                    </a>
                    <a href="<?= absolute_url('/docs/guides/requests') ?>" class="list-group-item list-group-item-action border-0 dropdown-menu-item">
                        <i class="fa fa-dot-circle <?php if (url_exists('requests')) : echo 'text-primary'; endif ?>"></i> HTTP Requests
                    </a>
                    <a href="<?= absolute_url('/docs/guides/responses') ?>" class="list-group-item list-group-item-action border-0 dropdown-menu-item">
                        <i class="fa fa-dot-circle <?php if (url_exists('responses')) : echo 'text-primary'; endif ?>"></i> HTTP Responses
                    </a>
                    <a href="<?= absolute_url('/docs/guides/client') ?>" class="list-group-item list-group-item-action border-0 dropdown-menu-item">
                        <i class="fa fa-dot-circle <?php if (url_exists('client')) : echo 'text-primary'; endif ?>"></i> HTTP Client
                    </a>
                    <a href="<?= absolute_url('/docs/guides/redirection') ?>" class="list-group-item list-group-item-action border-0 dropdown-menu-item">
                        <i class="fa fa-dot-circle <?php if (url_exists('redirection')) : echo 'text-primary'; endif ?>"></i> URL Redirection
                    </a>
                </div>

                <button class="list-group-item list-group-item-action" id="dropdown-btn" data-target="orm-dropdown-menu">
                    <i class="fa fa-database <?php if (url_exists('orm')) : echo 'text-primary'; endif ?>"></i> ORM

                    <span class="float-right dropdown-caret">
                        <?php if (url_exists('orm')) : ?>
                        <i class="fa fa-caret-up"></i>
                        <?php else : ?>
                        <i class="fa fa-caret-down"></i>
                        <?php endif ?>
                    </span>
                </button>
            </div>
        </div>

        <div class="wrapper__content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm px-5 sticky-top">
                <button class="btn px-1 sidebar-toggler" title="Toggle sidebar">
                    <i class="fa fa-bars"></i>
                </button>
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
    <script defer src="<?= absolute_url('/public/js/index.js') ?>"></script>
    <script defer src="<?= absolute_url('/public/js/theme.js') ?>"></script>
</body>

</html>