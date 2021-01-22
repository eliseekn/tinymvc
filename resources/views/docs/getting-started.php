<?php $this->layout('docs/layout', [
    'page_title' => 'Getting started | Documentation'
]) ?>

<?php $this->start('page_content') ?>

<div class="card mb-5" id="installation">
    <div class="card-header">Installation</div>

    <div class="card-body">
        <p class="font-weight-bold">Server requirements</p>
        <p>To use TinyMVC framework, you server need to meets the following requirements :</p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">PHP >= 7.2
Node
Composer
Yarn</code></pre>
        </div>

        <p class="font-weight-bold">Installing TinyMVC</p>
        <p>Download project <a href="https://github.com/eliseekn/tinymvc">here</a> or clone the github repository by running the following command on your terminal :</p>

        <div class="card">
            <pre class="m-0"><code class="p-3">git clone https://github.com/eliseekn/tinymvc</code></pre>
        </div>
    </div>

    <div class="card-footer d-flex justify-content-between">
        <span>Next: <a href="#configuration">Configuration</a></span>
    </div>
</div>

<div class="card" id="configuration">
    <div class="card-header">Configuration</div>

    <div class="card-body">
        <p class="font-weight-bold">Web server configuration</p>
        <p>After installing TinyMVC framework you need to configure your web server.</p>

        <p>
            <span class="font-weight-bold font-italic"> For Apache</span> : TinyMVC comes with a <span class="bg-light text-danger">.htaccess</span> configuraion file
        </p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-l
RewriteRule ^(.*)$ index.php</code></pre>
        </div>

        <p>
            <span class="font-weight-bold font-italic"> For Nginx</span> : add the following lines to your server declaration :
        </p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">server {
    location / {
        try_files $uri $uri/ /index.php;
    }
}</code></pre>
        </div>

        <p class="font-weight-bold">Application configuration</p>
        <p>After installation, you can setup your main application configuraion by editing <span class="bg-light text-danger">config/app.php</span> file</p>
    </div>

    <div class="card-footer d-flex justify-content-between">
        <span>Next: <a href="<?= absolute_url('docs/guides/routing') ?>">Routing</a></span>
        <span>Previous: <a href="#installation">Installation</a></span>
    </div>
</div>

<?php $this->stop() ?>
