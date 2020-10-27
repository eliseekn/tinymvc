<?php $this->layout('docs/layout', [
    'page_title' => 'Middlewares | Documentation'
]) ?>

<?php $this->start('page_content') ?>

<?php $this->insert('partials/breadcrumb', [
    'items' => [
        'Documentation' => absolute_url('/docs'),
        'Middlewares' => ''
    ]
]) ?>

<div class="card mb-5" id="basic-routing">
    <div class="card-header bg-dark">
        <span class="text-white lead">Middlewares</span>
    </div>

    <div class="card-body">
        <p>
            You can define middlewares in <span class="bg-light text-danger">app/Middlewares</span> directory. The basic content 
            of the middleware <span class="bg-light text-danger">MyCustomMiddleware.php</span> can be :
        </p>

        <div class="card">
            <div class="card-body bg-light">
                <pre class="m-0 text-danger"><code>&lt;?php

namespace App\Middlewares

class MyCustomMiddleware {
    /**
     * @return void
     */
    public static function handle(): void
    {
        //code to execute
    }
}</code></pre>
            </div>
        </div>
    </div>

    <div class="card-footer d-flex justify-content-between">
        <span>Next: <a href="<?= absolute_url('/docs/controllers') ?>">Controllers</a></span>
        <span>Previous: <a href="<?= absolute_url('/docs/routing') ?>">Routing</a></span>
    </div>
</div>

<?php $this->stop() ?>