<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?= $page_description ?>">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <title><?= $page_title ?></title>
</head>

<body>
    <div class="d-flex align-items-center justify-content-center min-vh-100">
        <div class="container" style="width: 400px">
            <h1 class="display-4 py-3 text-center">Login</h1>

            <?php if (session_has('flash_messages')) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php
                        $flash_messages = get_flash_messages('flash_messages');

                        foreach ($flash_messages as $flash_message) {
                            if (is_array($flash_message)) {
                                foreach ($flash_message as $error_message) {
                                    echo $error_message . '<br>';
                                };
                            } else {
                                echo $flash_message . '<br>';
                            }
                        }
                    ?>
                </div>
            <?php } ?>

            <div class="card shadow p-5">
                <form method="post" action="<?= absolute_url('/user/login') ?>">
                    <?= generate_csrf_token() ?>
                    
                    <div class="form-group">
                        <input type="text" name="email" placeholder="Email address" class="form-control">
                    </div>

                    <div class="form-group">
                        <input type="password" name="password" placeholder="Password" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-lg btn-dark">Submit</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
