<!DOCTYPE html>
<html lang="en-US">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="<?= $page_description ?>">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	<link rel="stylesheet" href="<?= absolute_url('/public/assets/css/main.css') ?>">
	<title><?= $page_title ?></title>
</head>

<body>
	<header class="d-flex flex-column align-items-center text-white">
        <h1 class="display-3 mb-4"><?= $header_title ?></h1>
        <h3>Mountain, Snow, Mountaineering, Everest</h3>
	</header>
	
	<?= $this->section('page_content') ?>

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
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>

</html>