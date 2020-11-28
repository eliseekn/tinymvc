<?php $this->layout('docs/layout', [
    'page_title' => 'Middlewares | Documentation'
]) ?>

<?php $this->start('page_content') ?>

<div class="card mb-5">
    <div class="card-header">Middlewares</div>

    <div class="card-body">
        <p>
            You can define middlewares in <span class="bg-light text-danger">app/Middlewares</span> directory. The basic content 
            of the middleware <span class="bg-light text-danger">MyCustomMiddleware.php</span> can be :
        </p>

        <div class="card">
            <pre class="m-0"><code class="p-3">&lt;?php

namespace App\Middlewares

class MyCustomMiddleware 
{
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

    <div class="card-footer d-flex justify-content-between">
        <span>Next: <a href="<?= absolute_url('/docs/guides/controllers') ?>">Controllers</a></span>
        <span>Previous: <a href="<?= absolute_url('/docs/guides/routing') ?>">Routing</a></span>
    </div>
</div>

<?php $this->stop() ?>