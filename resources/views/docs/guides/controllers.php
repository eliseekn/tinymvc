<?php $this->layout('docs/layout', [
    'page_title' => 'Controllers | Documentation'
]) ?>

<?php $this->start('page_content') ?>

<?php $this->insert('partials/breadcrumb', [
    'items' => [
        'Documentation' => absolute_url('/docs'),
        'Controllers' => ''
    ]
]) ?>

<div class="card mb-5" id="basic-routing">
    <div class="card-header bg-dark">
        <span class="text-white lead">Controllers</span>
    </div>

    <div class="card-body">
        <p>
            You can define controllers in <span class="bg-light text-danger">app/Controllers</span> directory. The basic content 
            of the controller <span class="bg-light text-danger">MyController.php</span> can be :
        </p>

        <div class="card">
            <div class="card-body bg-light">
                <pre class="m-0 text-danger"><code>&lt;?php

namespace App\Controllers;

class MyController
{
    /**
     * @return void
     */
    public function index(): void
    {
        Response::send([], 'Hello world!');
    }
}</code></pre>
            </div>
        </div>
    </div>

    <div class="card-footer d-flex justify-content-between">
        <span>Next: <a href="<?= absolute_url('/docs/views') ?>">Views</a></span>
        <span>Previous: <a href="<?= absolute_url('/docs/middlewares') ?>">Middlewares</a></span>
    </div>
</div>

<?php $this->stop() ?>