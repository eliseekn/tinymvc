<!DOCTYPE html>
<html lang="<?= config('app.lang') ?>">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Page not found">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <title>Error 500: Internal Server Error</title>
</head>

<body>
    <div class="container mt-5 p-5 text-center">
        <h1 class="font-weight-bold display-4">Error 500: <em>Internal Server Error</em></h1>
        
        <p class="lead mt-4" style="line-height: 1.8">
            Try to refresh the page or feel free to contact us if the problem persists. <br>
            <a href="<?= absolute_url() ?>">Go back home</a>
        </p>
    </div>
</body>

</html>