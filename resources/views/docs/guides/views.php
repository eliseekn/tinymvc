<?php $this->layout('docs/layout', [
    'page_title' => 'Views | Documentation'
]) ?>

<?php $this->start('page_content') ?>

<div class="card mb-5" id="basic-routing">
    <div class="card-header">Views</div>

    <div class="card-body">
        <p class="font-weight-bold">Plates template</p>
        <p class="mb-4">TinyMVC use <a href="http://platesphp.com/">Plates</a> native PHP template system.</p>
        
        <p class="font-weight-bold">Templates directory</p>
        <p class="mb-4">Views templates are located in <span class="bg-light text-danger">resources/views</span> directory.</p>
        
        <p class="font-weight-bold">CSRF field</p>
        <p>
            You can implement a CSRF token in your forms by using the helper function 
            <span class="bg-light text-danger">csrf_token_input()</span> :
        </p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">&lt;form method="POST" action="/uri"&gt;
    &lt;?= csrf_token_input() ?&gt;
                
&lt;/form&gt;</code></pre>
        </div>

        <p>This function generate an hidden input with generated token as value :</p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">&lt;input type="hidden" name="csrf_token" id="csrf_token" value="csrf_token_value"&gt;</code></pre>
        </div>

        <p class="font-weight-bold">Rendering</p>
        <p>You can render a view template by using the <span class="bg-light text-danger">render</span> method inside a <span class="bg-light text-danger">Controller</span> :</p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">$this->render(string $view, array $data = [], int $status_code = 200);</code></pre>
        </div>

        <p>Or by using the <span class="bg-light text-danger">View</span> class : </p>
    
        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">View::render(string $view, array $data = [], int $status_code = 200);</code></pre>
        </div>

        <p>Example :</p>

        <div class="card">
            <pre class="m-0"><code class="p-3">&lt;?php

namespace App\Controllers;

use Framework\Routing\Controller;

class MyController
{
    /**
     * @return void
     */
    public function index(): void
    {
        $this->render('index'); //or View::render('index')
    }
}</code></pre>
        </div>
    </div>

    <div class="card-footer d-flex justify-content-between">
        <span>Next: <a href="<?= absolute_url('docs/guides/requests') ?>">HTTP Requests</a></span>
        <span>Previous: <a href="<?= absolute_url('docs/guides/controllers') ?>">Controllers</a></span>
    </div>
</div>

<?php $this->stop() ?>