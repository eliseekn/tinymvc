<!DOCTYPE html>
<html lang="<?= config('app.lang') ?>">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Page not found">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <title>Error 404: Page not found</title>
</head>

<body>
    <div class="container mt-5 p-5 text-center">
        <h1 class="font-weight-bold display-4">Error 404: <em>Page not found</em></h1>
        
        <p class="lead mt-4" style="line-height: 1.8">
            The page you've requested doesn't exists. <br>
            <a href="<?= absolute_url() ?>">Go back home</a>
        </p>
    </div>
</body>

</html>
