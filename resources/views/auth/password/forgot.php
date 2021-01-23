<!DOCTYPE html>
<html lang="<?= config('app.lang') ?>">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?= __('forgot_password', true) ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <title><?= __('forgot_password', true) ?></title>
</head>

<body>
    <div class="container py-5" style="width: 450px">
        <h1 class="text-center"><?= __('forgot_password', true) ?></h1>
        
        <div class="card my-3 mb-3">
            <div class="card-body bg-light">
                <p class="card-text">
                    <?= __('reset_password_instructions', true) ?>
                </p>
            </div>
        </div>

        <?php if (!empty($alerts)) :
            $this->insert('partials/alert', $alerts);
        endif ?>

        <div class="card shadow p-4">
            <form method="post" action="<?= absolute_url('password/notify') ?>">
                <div class="form-group">
                    <label for="email"><?= __('email', true) ?></label>
                    <input type="email" id="email" name="email" class="form-control">
                </div>

                <button type="submit" class="btn btn-block btn-primary loading"><?= __('submit', true) ?></button>
            </form>
        </div>
    </div>

    <script defer src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    <script defer src="<?= assets('js/index.js') ?>"></script>
</body>

</html>
