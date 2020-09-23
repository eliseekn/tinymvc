<?php 
$this->layout('docs/layout', [
    'page_title' => 'Getting started | Documentation'
]) 
?>

<?php $this->start('page_content') ?>

<?php
$this->insert('partials/breadcrumb-docs', [
    'items' => [
        'Getting started' => ''
    ]
]);
?>

<div class="card mb-5" id="installation">
    <div class="card-header bg-dark">
        <span class="text-white lead">Installation</span>
    </div>

    <div class="card-body">
        <p class="font-weight-bold">Server requirements</p>
        <p>To use <strong>TinyMVC</strong> framework, you server need to meets the following requirements :</p>

        <div class="card mb-4">
            <div class="card-body bg-light">
                <pre class="m-0 text-danger"><code>PHP >= 7.2
MySQL server
Node
Composer
Yarn</code></pre>
            </div>
        </div>

        <p class="font-weight-bold">Installing TinyMVC</p>
        <p>Download project <a href="https://github.com/eliseekn/TinyMVC">here</a> or clone the github repository by running the following command on your terminal :</p>

        <div class="card">
            <div class="card-body bg-light">
                <code class="text-danger">git clone https://github.com/eliseekn/TinyMVC</code>
            </div>
        </div>
    </div>

    <div class="card-footer d-flex justify-content-between">
        <span>Next: <a href="#configuration">Configuration</a></span>
    </div>
</div>

<div class="card" id="configuration">
    <div class="card-header bg-dark">
        <span class="text-white lead">Configuration</span>
    </div>

    <div class="card-body">
        <p class="font-weight-bold">Web server configuration</p>
        <p>After installing TinyMVC framework you need to configure your web server.</p>

        <p>
            <span class="font-weight-bold font-italic"> For Apache</span> : TinyMVC comes with a <span class="bg-light text-danger">.htaccess</span> configuraion file
        </p>

        <div class="card mb-4">
            <div class="card-body bg-light">
                <pre class="m-0 text-danger"><code>RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-l
RewriteRule ^(.*)$ index.php</code></pre>
            </div>
        </div>

        <p>
            <span class="font-weight-bold font-italic"> For Nginx</span> : add the following lines to your server declaration :
        </p>

        <div class="card mb-4">
            <div class="card-body bg-light">
                <pre class="m-0 text-danger"><code>server {
    location / {
        try_files $uri $uri/ /index.php;
    }
}</code></pre>
            </div>
        </div>

        <p class="font-weight-bold">Application configuration</p>
        <p>After installation, you can setup your main application configuraion by editing file <span class="bg-light text-danger">config/app.php</span> </p>
    </div>

    <div class="card-footer d-flex justify-content-between">
        <span>Next: <a href="<?= absolute_url('/docs/routing') ?>">Routing</a></span>
        <span>Previous: <a href="#installation">Installation</a></span>
    </div>
</div>

<?php $this->stop() ?>
