<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Administration log in page">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    
    <title>Log in | Administration</title>
</head>

<body>
    <div class="d-flex align-items-center justify-content-center min-vh-100">
        <div class="container" style="width: 400px">
            <h1 class="py-3 text-center">Log in</h1>

            <?php if (session_has('flash_messages')) { ?>

                <div class="alert alert-danger" role="alert">

                    <?php
                    $flash_messages = get_flash_messages('flash_messages');

                    foreach ($flash_messages as $flash_message) {
                        if (is_array($flash_message)) {
                            foreach ($flash_message as $error_message) {
                                echo $error_message . '<br>';
                            }
                        } else {
                            echo $flash_message . '<br>';
                        }
                    }
                    ?>

                </div>

            <?php } ?>

            <div class="card shadow p-4">
                <form method="post" action="<?= absolute_url('/admin/authenticate') ?>">

                    <?= generate_csrf_token() ?>

                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" id="email" name="email" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>

                        <div class="d-flex align-items-center">
                            <input type="password" id="password" name="password" class="form-control">

                            <span class="btn" id="password-toggler" title="Toggle display">
                                <i class="fa fa-eye-slash"></i>
                            </span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <div class="form-group custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="remember" name="remember" checked>
                            <label class="custom-control-label" for="remember">Remember me</label>
                        </div>

                        <a href="<?= absolute_url('/password/forgot') ?>">Forgot password?</a>
                    </div>

                    <input type="submit" class="btn btn-primary w-100" value="Submit">
                </form>
            </div>
        </div>
    </div>

    <script defer src="https://use.fontawesome.com/releases/v5.13.0/js/all.js"></script>
    <script defer src="<?= absolute_url('/public/js/components/password.js') ?>"></script>
</body>

</html>