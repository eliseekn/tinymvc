<!DOCTYPE html>
<html lang="en">

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
    <div class="d-flex" id="wrapper">
        <div class="bg-light border-light border-right min-vh-100" id="sidebar-wrapper">
            <div class="sidebar-title bg-dark text-light">
                <i class="fa fa-book"></i> Documentation
            </div>

            <div class="list-group list-group-flush">
                <a href="<?= absolute_url('/docs/getting-started') ?>" class="list-group-item list-group-item-action bg-light">
                    <i class="fa fa-home"></i> Getting started
                </a>

                <a href="#" class="list-group-item list-group-item-action bg-light">
                    <i class="fa fa-layer-group"></i> Guides
                </a>
                
                <div class="list-group list-group-flush">
                    <a href="<?= absolute_url('/docs/routing') ?>" class="list-group-item list-group-item-action bg-light" style="padding-left: 2.5em">
                        <i class="fa fa-dot-circle <?php if (exists_uri('routing')) : echo 'text-primary'; endif ?>"></i> Routing
                    </a>
                    <a href="<?= absolute_url('/docs/middlewares') ?>" class="list-group-item list-group-item-action bg-light" style="padding-left: 2.5em">
                        <i class="fa fa-dot-circle <?php if (exists_uri('middlewares')) : echo 'text-primary'; endif ?>"></i> Middlewares
                    </a>
                    <a href="<?= absolute_url('/docs/controllers') ?>" class="list-group-item list-group-item-action bg-light" style="padding-left: 2.5em">
                        <i class="fa fa-dot-circle <?php if (exists_uri('controllers')) : echo 'text-primary'; endif ?>"></i> Controllers
                    </a>
                    <a href="<?= absolute_url('/docs/views') ?>" class="list-group-item list-group-item-action bg-light" style="padding-left: 2.5em">
                        <i class="fa fa-dot-circle <?php if (exists_uri('views')) : echo 'text-primary'; endif ?>"></i> Views
                    </a>
                    <a href="<?= absolute_url('/docs/requests') ?>" class="list-group-item list-group-item-action bg-light" style="padding-left: 2.5em">
                        <i class="fa fa-dot-circle <?php if (exists_uri('requests')) : echo 'text-primary'; endif ?>"></i> HTTP Requests
                    </a>
                    <a href="<?= absolute_url('/docs/responses') ?>" class="list-group-item list-group-item-action bg-light" style="padding-left: 2.5em">
                        <i class="fa fa-dot-circle <?php if (exists_uri('responses')) : echo 'text-primary'; endif ?>"></i> HTTP Responses
                    </a>
                    <a href="<?= absolute_url('/docs/client') ?>" class="list-group-item list-group-item-action bg-light" style="padding-left: 2.5em">
                        <i class="fa fa-dot-circle <?php if (exists_uri('client')) : echo 'text-primary'; endif ?>"></i> HTTP Client
                    </a>
                    <a href="<?= absolute_url('/docs/redirection') ?>" class="list-group-item list-group-item-action bg-light" style="padding-left: 2.5em">
                        <i class="fa fa-dot-circle <?php if (exists_uri('redirection')) : echo 'text-primary'; endif ?>"></i> URL Redirection
                    </a>

                    <a href="#" class="list-group-item list-group-item-action bg-light">
                        <i class="fa fa-database"></i> ORM
                    </a>
                </div>
            </div>
        </div>

        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
                <button class="btn border-light text-light" id="sidebar-toggler" title="Toggle sidebar">
                    <i class="fa fa-bars"></i>
                </button>

                <div class="ml-auto">
                    <span class="text-white">v2</span>
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
    <script defer src="<?= absolute_url('/public/js/index.js') ?>"></script>
</body>

</html>