<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="TinyMVC is a PHP framework based on MVC architecture">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <title>TinyMVC | Just a PHP framework based on MVC architecture</title>
</head>

<body>

    <div class="container mt-5 p-5 text-center">
        <h1 class="font-weight-bold display-4">TinyMVC</h1>

        <p class="lead mt-4" style="line-height: 1.8">
            Just a PHP framework based on MVC architecture that helps you <br> build easly and quickly powerful web applications and RESTful API
        </p>

        <div class="mt-5">
            <a href="<?= absolute_url('/admin') ?>" class="ml-3 btn btn-primary">
            <i class="fa fa-cog"></i> Administration
            </a>
        </div>

        <hr class="my-5 pb-5 w-25">

        <span>
            <a href="https://github.com/eliseekn/TinyMVC" class="btn btn-dark">
                <i class="fab fa-github"></i> Github
            </a>
            
            <a href="<?= absolute_url('/docs') ?>" class="ml-3 btn btn-success">
                <i class="fa fa-book"></i> Documentation
            </a>
        </span>
    </div>

    <script defer src="https://use.fontawesome.com/releases/v5.13.0/js/all.js"></script>

</body>

</html>
