<?php $this->layout('docs/layout', [
    'page_title' => 'Middlewares | Documentation'
]) ?>

<?php $this->start('page_content') ?>

<div class="card mb-5">
    <div class="card-header">Middlewares</div>

    <div class="card-body">
        <p class="font-weight-bold">Create middlewares</p>
        <p>
            You can define middlewares in <span class="bg-light text-danger">app/Middlewares</span> directory. The basic content 
            of the middleware <span class="bg-light text-danger">MyCustomMiddleware.php</span> can be :
        </p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">&lt;?php

namespace App\Middlewares;

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

        <p class="font-weight-bold">Execute middlewares</p>
        <p>Define middlewares in route parameters :</p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">Route::get('home', [
    'handler' => 'HomeController::index',
    'name' => 'home',
    'middlewares' => [
        'CsrfProtection'
    ]
]);</code></pre>
        </div>

        <p>Execute middlewares inside Controller class :</p>

        <div class="card">
            <pre class="m-0"><code class="p-3">&lt;?php

namespace App\Controllers;

use Framework\Routing\Controller;

class MyController extends Controller
{
    public function __construct()
    {
        $this->middlewares('MyCustomMiddleware');
    }

    /**
     * @return void
     */
    public function index(): void
    {
        $this->response('Hello world!');
    }
}</code></pre>
        </div>
    </div>

    <div class="card-footer d-flex justify-content-between">
        <span>Next: <a href="<?= absolute_url('docs/guides/controllers') ?>">Controllers</a></span>
        <span>Previous: <a href="<?= absolute_url('docs/guides/routing') ?>">Routing</a></span>
    </div>
</div>

<?php $this->stop() ?>