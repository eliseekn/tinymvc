<?php $this->layout('docs/layout', [
    'page_title' => 'HTTP Responses | Documentation'
]) ?>

<?php $this->start('page_content') ?>

<div class="card mb-5" id="basic-routing">
    <div class="card-header ">
        <span class=" lead">HTTP Responses</span>
    </div>

    <div class="card-body">
        <p class="font-weight-bold">Send basic HTTP response</p>
        
        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">Response::send($body, array $headers = [], int $status_code = 200);</code></pre>
        </div>

        <p class="font-weight-bold">Send JSON response</p>
        
        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">Response::sendJson(array $body, array $headers = [], int $status_code = 200);</code></pre>
        </div>

        <p class="font-weight-bold">Send HTTP headers only</p>
        
        <div class="card mb-4">
            <pre class="m-0"><code class="p-3">Response::sendHeaders(array $headers, int $status_code = 200);</code></pre>
        </div>
    </div>

    <div class="card-footer d-flex justify-content-between">
        <span>Next: <a href="<?= absolute_url('/docs/redirections') ?>">URL Redirection</a></span>
        <span>Previous: <a href="<?= absolute_url('/docs/requests') ?>">HTTP Requests</a></span>
    </div>
</div>

<?php $this->stop() ?>