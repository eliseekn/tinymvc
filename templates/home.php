<?php $this->layout('layouts/main', [
    'page_title' => $page_title,
    'page_description' => $page_description
]) ?>

<?php $this->start('page_content') ?>

    <h1>TinyMVC</h1>
    <p>Just a PHP framework based on MVC architecture.</p>
    <p><a href="https://github.com/eliseekn/TinyMVC">https://github.com/eliseekn/TinyMVC</a></p>
    <p>Copyright © 2019-2020 - <strong>N'Guessan Kouadio Elisée</strong> (eliseekn@gmail.com)</p>

<?php $this->stop() ?>