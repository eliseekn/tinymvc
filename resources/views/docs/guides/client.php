<?php
$this->layout('docs/layout', [
    'page_title' => 'HTTP Client | Documentation'
])
?>

<?php $this->start('page_content') ?>

<?php
$this->insert('partials/breadcrumb-docs', [
    'items' => [
        'HTTP Client' => ''
    ]
]);
?>

<div class="card mb-5" id="basic-routing">
    <div class="card-header bg-dark">
        <span class="text-white lead">HTTP Client</span>
    </div>

    <div class="card-body">
        <p class="font-weight-bold">Send basic HTTP request</p>
        <p>TinyMVC HTTP client uses <span class="bg-light text-danger">curl</span> library to handle requests :</p>
        
        <div class="card mb-4">
            <div class="card-body bg-light">
                <code class="text-danger">Client::send(string $method, array $urls, array $headers = [], ?array $data = null, bool $json_data = false);</code>
            </div>
        </div>

        <p class="font-weight-bold">HTTP methods</p>
        <p>Here are defined all HTTP methods shortcuts you can use to send HTTP requests :</p>

        <div class="card mb-4">
            <div class="card-body bg-light">
                <pre class="m-0 text-danger"><code>Client::get(array $urls, array $headers = [], ?array $data = null, bool $json_data = false)
Client::post(array $urls, array $headers = [], ?array $data = null, bool $json_data = false)
Client::put(array $urls, array $headers = [], ?array $data = null, bool $json_data = false)
Client::patch(array $urls, array $headers = [], ?array $data = null, bool $json_data = false)
Client::delete(array $urls, array $headers = [], ?array $data = null, bool $json_data = false)
Client::options(array $urls, array $headers = [], ?array $data = null, bool $json_data = false)</code></pre>
            </div>
        </div>
    </div>

    <div class="card-footer d-flex justify-content-between">
        <span>Next: <a href="<?= absolute_url('/docs/redirections') ?>">URL Redirection</a></span>
        <span>Previous: <a href="<?= absolute_url('/docs/responses') ?>">HTTP Responses</a></span>
    </div>
</div>

<?php $this->stop() ?>