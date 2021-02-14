<?php $this->layout('layouts/docs', [
    'page_title' => 'HTTP Requests | Documentation'
]) ?>

<?php $this->start('page_content') ?>

<div class="card mb-5" id="http-requests">
    <div class="card-header">HTTP Requests</div>

    <div class="card-body">
        <p class="font-weight-bold">Accessing HTTP requests</p>
        <p class="mb-4">You can access incoming HTTP requests by using the <span class="bg-light text-danger">Requests</span> class.</p>

        <p class="font-weight-bold">Retrieving headers</p>
        <p>
            Headers informations are retrieved from <span class="bg-light text-danger">$_SERVER</span> array. 
            To retrieves all headers, uses the following method :
        </p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">$headers = Request::getHeaders(); //or 
$headers = (new Request)->headers();</code></pre>
        </div>

        <p>Single header :</p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">$header = Request::getHeader(string $key); //or 
$headers = (new Request)->headers(string $key);</code></pre>
        </div>

        <p class="font-weight-bold">Retrieving method</p>
        <p>Method is retrieved form <span class="bg-light text-danger">$_SERVER['REQUEST_METHOD']</span> :</p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">$method = Request::getMethod(); //or 
$method = (new Request)->method();</code></pre>
        </div>

        <p class="font-weight-bold">Retrieving URI</p>
        <p>Full URI :</p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">$uri = Request::getFullUri(); //or 
$uri = (new Request)->uri(true);</code></pre>
        </div>

        <p>URI without queries :</p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">$uri = Request::getUri(); //or 
$uri = (new Request)->uri();</code></pre>
        </div>

        <p class="font-weight-bold">Retrieving $_GET queries</p>
        <p>All queries :</p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">$queries = Request::getQueries(); //or 
$queries = (new Request)->queries();</code></pre>
        </div>

        <p>Single query :</p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">$query = Request::getQuery(string $key); //or 
$query = (new Request)->$key; //or 
$query = (new Request)->queries(string $key);</code></pre>
        </div>

        <p>Set query value :</p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">Request::setQuery(string $key, $value); //or 
(new Request)->query(string $key, $value);</code></pre>
        </div>

        <p class="font-weight-bold">Retrieving $_POST inputs</p>
        <p>All inputs :</p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">$inputs = Request::getInputs(); //or 
$inputs = (new Request)->inputs()</code></pre>
        </div>

        <p>Single input :</p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">$input = Request::getInput(string $input); //or 
$input = (new Request)->$key; //or
$input = (new Request)->inputs(string $input);</code></pre>
        </div>

        <p>Set input value :</p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">Request::setInput(string $input, $value); //or 
(new Request)->input(string $input, $value);</code></pre>
        </div>

        <p class="font-weight-bold">Retrieving items data from Request class</p>
        <p>Retrieving both $_GET queries and $_POST inputs :</p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">$items = (new Request)->all();</code></pre>
        </div>

        <p>Check if item exists :</p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">$exists = Request::hasQuery(string $key)
$exists = Request::hasInput(string $input); //or
$exists = (new Request)->has(string $item); //query or input</code></pre>
        </div>

        <p>Retrieving only some items data :</p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">$exists = (new Request)->only(string ...$items);</code></pre>
        </div>

        <p>Retrieving all items excepts some :</p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">$exists = (new Request)->except(string ...$items);</code></pre>
        </div>

        <p class="font-weight-bold">Retrieving uploaded files</p>
        <p>
            Uploaded files informations are retrieved from <span class="bg-light text-danger">$_FILES</span> array. 
            To retrieves single uploaded file, uses the following method :
        </p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">$file = Request::getFile(string $input, array $allowed_extensions = []);</code></pre>
        </div>

        <p>Retrieves multiple uploaded files :</p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">$files = Request::getFiles(string $input, array $allowed_extensions = []);</code></pre>
        </div>

        <p>Or you can user <span class="bg-light text-danger">files</span> method of Request class :</p>

        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">$files = (new Request)->files(string $input, array $allowed_extensions = [], bool $multiple = false);</code></pre>
        </div>

        <p class="mb-4">To manage uploaded files, refer to <a href="<?= absolute_url('docs/guides/uploader') ?>" class="bg-light text-danger">Uploader</a> class.</p>

        <p class="font-weight-bold">Accessing HTTP requests inside a Controller</p>
        <p class="mb-4">You can access incoming HTTP requests in your controller class by using the <span class="bg-light text-danger">request</span> method.</p>

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
        $items = $this->request->inputs(); //retrieves inputs from POST request
        
        $this->render('index', compact('items'));
    }
}</code></pre>
        </div>
    </div>

    <div class="card-footer d-flex justify-content-between">
        <span>Next: <a href="<?= absolute_url('docs/guides/responses') ?>">HTTP Responses</a></span>
        <span>Previous: <a href="<?= absolute_url('docs/guides/views') ?>">Views</a></span>
    </div>
</div>

<?php $this->stop() ?>