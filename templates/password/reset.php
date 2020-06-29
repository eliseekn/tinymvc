<?php $this->layout('password/layout', [
    'page_title' => 'Forgot password',
    'page_description' => 'Forgot password page'
]) ?>

<?php $this->start('page_content') ?>

<h1 class="py-3 text-center">Forgot password</h1>
            
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
    <form method="post" action="<?= absolute_url('/password/notify') ?>">
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" id="email" name="email" class="form-control">
        </div>

        <input type="submit" class="btn btn-primary w-100" value="Submit">
    </form>
</div>

<?php $this->stop() ?>