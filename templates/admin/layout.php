<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?= $page_description ?>">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= absolute_url('/public/css/admin.css') ?>">

    <title><?= $page_title ?></title>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <div class="bg-light border-light border-right" id="sidebar-wrapper">
            <div class="sidebar-heading bg-dark">
                <a href="<?= absolute_url('/admin') ?>" class="text-light">
                    <i class="fa fa-cogs"></i> Administration
                </a>
            </div>

            <div class="list-group list-group-flush">
                <a href="<?= absolute_url('/admin/users') ?>" class="list-group-item list-group-item-action bg-light">
                    <i class="fa fa-dot-circle"></i> Users
                </a>
            </div>
        </div>

        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <button class="btn btn-light" id="sidebar-toggler">
                    <i class="fa fa-angle-left"></i>
                </button>

                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= absolute_url('/') ?>">Go home</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-danger ml-3" href="<?= absolute_url('/logout') ?>">Log out</a>
                    </li>
                </ul>
            </nav>

            <div class="container-fluid p-4">
                <div class="mt-3">

                    <?= $this->section('page_content') ?>

                </div>
            </div>
        </div>
    </div>

    <script defer src="https://use.fontawesome.com/releases/v5.13.0/js/all.js"></script>
    <script defer src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script defer src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script defer src="<?= absolute_url('/public/js/components/tables.js') ?>"></script>
    <script defer src="<?= absolute_url('/public/js/components/password.js') ?>"></script>
    <script defer src="<?= absolute_url('/public/js/components/confirm.js') ?>"></script>
    <script defer src="<?= absolute_url('/public/js/components/loading.js') ?>"></script>
    <script defer src="<?= absolute_url('/public/js/components/delete.js') ?>"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            //toggle sidebar
            document.querySelector('#sidebar-toggler').addEventListener('click', event => {
                event.preventDefault()
                document.querySelector('#wrapper').classList.toggle('toggled')

                if (document.querySelector('#wrapper').classList.contains('toggled')) {
                    document.querySelector('#sidebar-toggler').innerHTML = '<i class="fa fa-angle-right"></i>'
                } else {
                    document.querySelector('#sidebar-toggler').innerHTML = '<i class="fa fa-angle-left"></i>'
                }
            })
        })
    </script>
</body>

</html>
