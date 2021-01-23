<!DOCTYPE html>
<html lang="<?= config('app.lang') ?>">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?= __('login', true) ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <title><?= __('login', true) ?></title>
</head>

<body>
    <div class="container py-5" style="width: 450px">
        <h1 class="pb-3 text-center"><?= __('login', true) ?></h1>

        <?php if (!empty($alerts)) : $this->insert('partials/alert', $alerts); endif ?>

        <?php if(auth_attempts_exceeded()) : 
            $this->insert('partials/alert', [
                'type' => 'danger',
                'message' => __('auth_attempts_exceeded', true),
                'display' => 'default',
                'dismiss' => false
            ]);
        endif ?>

        <div class="card shadow p-4">
            <form method="post" action="<?= absolute_url('authenticate') ?>">
                <?= csrf_token_input() ?>

                <div class="form-group">
                    <label for="email"><?= __('email', true) ?></label>
                    <input type="email" id="email" name="email" class="form-control <?php isset($errors->email) ? print('is-invalid') : print('') ?>" value="<?= $inputs->email ?? '' ?>" aria-describedby="email-error">

                    <?php if(isset($errors->email)) : ?>
                    <div id="email-error" class="invalid-feedback">
                        <?= $errors->email ?>
                    </div>
                    <?php endif ?>
                </div>

                <div class="form-group">
                    <label for="password"><?= __('password', true) ?></label>

                    <div class="d-flex align-items-center">
                        <input type="password" id="password" name="password" class="form-control <?php isset($errors->password) ? print('is-invalid') : print('') ?>" value="<?= $inputs->password ?? '' ?>" aria-describedby="password-error">

                        <span class="btn" id="password-toggler" title="Toggle display">
                            <i class="fa fa-eye-slash"></i>
                        </span>
                    </div>

                    <?php if(isset($errors->password)) : ?>
                    <div id="password-error" class="invalid-feedback d-block">
                        <?= $errors->password ?>
                    </div>
                    <?php endif ?>
                </div>

                <div class="d-flex flex-column flex-lg-row justify-content-lg-between justify-content-center mb-3 mb-lg-0 mx-auto">
                    <div class="form-group custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="remember" name="remember">
                        <label class="custom-control-label" for="remember"><?= __('remember', true) ?></label>
                    </div>

                    <a href="<?= absolute_url('password/forgot') ?>"><?= __('forgot_password', true) ?></a>
                </div>

                <button type="submit" class="btn btn-block btn-primary loading" <?php auth_attempts_exceeded() ? print('disabled') : print('') ?>>
                    <?= __('submit', true) ?>
                </button>
            </form>

            <p class="mt-4 text-center">
                <?= __('no_account', true) ?> <a href="<?= absolute_url('signup') ?>"><?= __('signup_here', true) ?></a>
            </p>
        </div>
    </div>

    <script defer src="https://use.fontawesome.com/releases/v5.13.0/js/all.js"></script>
    <script defer src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    <script defer src="<?= assets('js/index.js') ?>"></script>
</body>

</html>