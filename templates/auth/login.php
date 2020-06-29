<?php $this->layout('auth/layout', [
    'page_title' => 'Log in',
    'page_description' => 'Log in page'
]) ?>

<?php $this->start('page_content') ?>

<h1 class="py-3 text-center">Log in</h1>

<?php
if (session_has('flash_messages')) :
    $flash_messages = get_flash_messages('flash_messages');

    if (isset($flash_messages['success'])) :
?>
    <div class="alert alert-success alert-dismissible show" role="alert">

        <?php foreach ($flash_messages as $flash_message) : echo $flash_message . '<br>'; endforeach; ?>

        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

<?php else : ?>

    <div class="alert alert-danger alert-dismissible show" role="alert">

        <?php
        foreach ($flash_messages as $flash_message) :
            if (is_array($flash_message)) :
                foreach ($flash_message as $error_message) :
                    echo $error_message . '<br>';
                endforeach;
            else :
                echo $flash_message . '<br>';
            endif;
        endforeach
        ?>

        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

<?php
    endif;
endif
?>

<div class="card shadow p-4">
    <form method="post" action="<?= absolute_url('/authenticate') ?>">

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

    <p class="mt-4 text-center">Don't have an account? <a href="<?= absolute_url('/signup') ?>">Sign up here</a> </p>
</div>

<?php $this->stop() ?>