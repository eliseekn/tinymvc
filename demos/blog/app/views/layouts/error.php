<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?= $page_description ?>">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" 
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" 
        crossorigin="anonymous">
    <link rel="stylesheet" href="<?= absolute_url('public/assets/css/main.css') ?>">

    <title><?= $page_title ?></title>
</head>

<body>
    <header class="d-flex flex-column align-items-center text-white">
        <h1 class="display-3"><?= $page_title ?></h1>
    </header>

    <?= $page_content ?>

    <footer class="d-flex flex-column align-items-center text-white">
        <h1 class="display-3 mb-4"><?= $footer_title ?></h1>

        <ul class="social-icons">
            <a href="#" class="text-white">
                <li class="fab fa-facebook-square"></li>
            </a>
            <a href="#" class="text-white mx-3">
                <li class="fab fa-instagram"></li>
            </a>
            <a href="#" class="text-white mr-3">
                <li class="fab fa-pinterest-square"></li>
            </a>
            <a href="#" class="text-white">
                <li class="fab fa-youtube"></li>
            </a>
        </ul>
    </footer>

    <script defer src="https://use.fontawesome.com/releases/v5.13.0/js/all.js"></script>
</body>

</html>