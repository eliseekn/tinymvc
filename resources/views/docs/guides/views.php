<?php $this->layout('docs/layout', [
    'page_title' => 'Views | Documentation'
]) ?>

<?php $this->start('page_content') ?>

<?php $this->insert('partials/breadcrumb', [
    'items' => [
        'Documentation' => absolute_url('/docs'),
        'Views' => ''
    ]
]) ?>

<div class="card mb-5" id="basic-routing">
    <div class="card-header ">
        <span class=" lead">Views</span>
    </div>

    <div class="card-body">
        <p class="font-weight-bold">Plates template</p>
        <p class="mb-4">TinyMVC use <a href="http://platesphp.com/">Plates</a> native PHP template system.</p>
        
        <p class="font-weight-bold">Templates directory</p>
        <p class="mb-4">Views templates are located in <span class="bg-light text-danger">resources/views</span> directory.</p>
        
        <p class="font-weight-bold">CSRF field</p>
        <p>
            You can implement a CSRF token in your forms by using the helper function 
            <span class="bg-light text-danger">generate_csrf_token()</span> :
        </p>

        <div class="card mb-4">
            <div class="card-body bg-light">
                <pre class="m-0 text-danger"><code>&lt;form method="POST" action="/uri"&gt;
    &lt;?= generate_csrf_token() ?&gt;
                
&lt;/form&gt;</code></pre>
            </div>
        </div>

        <p>This function generate an hidden input with generated token as value :</p>

        <div class="card mb-4">
            <div class="card-body bg-light">
                <code class="text-danger">&lt;input type="hidden" name="csrf_token" id="csrf_token" value="$csrf_token"&gt;</code>
            </div>
        </div>

        <p class="font-weight-bold">Rendering</p>
        <p>You can render a view template from your application by using the <span class="bg-light text-danger">View</span> class : </p>
    
        <div class="card mb-4">
            <div class="card-body bg-light">
                <code class="text-danger">View::render(string $view, array $data = [], int $status_code = 200);</code>
            </div>
        </div>

        <p>Example :</p>

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
        View::render('index');
    }
}</code></pre>
            </div>
        </div>
    </div>

    <div class="card-footer d-flex justify-content-between">
        <span>Next: <a href="<?= absolute_url('/docs/requests') ?>">HTTP Requests</a></span>
        <span>Previous: <a href="<?= absolute_url('/docs/controllers') ?>">Controllers</a></span>
    </div>
</div>

<?php $this->stop() ?>