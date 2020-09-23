<?php 
$this->layout('docs/layout', [
    'page_title' => 'TinyMVC | Documentation'
]) 
?>

<?php $this->start('page_content') ?>

<div class="container mt-5 p-5 text-center">
    <h1 class="font-weight-bold display-4">TinyMVC</h1>

    <p class="lead mt-4" style="line-height: 1.8">
        Just a PHP framework based on MVC architecture that helps you <br> build easly and quickly powerful web applications and RESTful API.
    </p>

    <hr class="my-5 w-25">

    <span>
        <a href="https://github.com/eliseekn/TinyMVC" class="btn btn-dark">
            <i class="fab fa-github"></i> Github
        </a>
        
        <a href="<?= absolute_url('/docs/getting-started') ?>" class="ml-3 btn btn-success">
            <i class="fa fa-book"></i> Getting started
        </a>
    </span>
</div>

<?php $this->stop() ?>
