<?php $this->layout('docs/layout', [
    'page_title' => 'HTTP Requests | Documentation'
]) ?>

<?php $this->start('page_content') ?>

<?php $this->insert('partials/breadcrumb', [
    'items' => [
        'Documentation' => absolute_url('/docs'),
        'HTTP Requests' => ''
    ]
]) ?>

<div class="card mb-5" id="basic-routing">
    <div class="card-header ">
        <span class=" lead">HTTP Requests</span>
    </div>

    <div class="card-body">
        <p class="font-weight-bold">Accessing HTTP requests</p>
        <p class="mb-4">You can access incoming HTTP requests by using the <span class="bg-light text-danger">Requests</span> class.</p>

        <p class="font-weight-bold">Retrieving headers</p>
        <p>
            Headers informations are retrieved from <span class="bg-light text-danger">$_SERVER</span> array. 
            To retrieves all headers, uses the following method :
        </p>

        <div class="card mb-4">
            <div class="card-body bg-light">
                <code class="text-danger">$headers = Request::getHeaders();</code>
            </div>
        </div>

        <p>Single header :</p>

        <div class="card mb-4">
            <div class="card-body bg-light">
                <code class="text-danger">$header = Request::getHeader(string $field);</code>
            </div>
        </div>

        <p class="font-weight-bold">Retrieving method</p>
        <p>Method is retrieved form <span class="bg-light text-danger">$_SERVER['REQUEST_METHOD']</span> :</p>

        <div class="card mb-4">
            <div class="card-body bg-light">
                <code class="text-danger">$method = Request::getMethod();</code>
            </div>
        </div>

        <p class="font-weight-bold">Retrieving URI</p>
        <p>Full URI :</p>

        <div class="card mb-4">
            <div class="card-body bg-light">
                <code class="text-danger">$uri = Request::getFullURI();</code>
            </div>
        </div>

        <p>URI without queries :</p>

        <div class="card mb-4">
            <div class="card-body bg-light">
                <code class="text-danger">$uri = Request::getURI();</code>
            </div>
        </div>

        <p class="font-weight-bold">Retrieving $_GET queries</p>
        <p>All queries :</p>

        <div class="card mb-4">
            <div class="card-body bg-light">
                <code class="text-danger">$queries = Request::getQueries();</code>
            </div>
        </div>

        <p>Single query :</p>

        <div class="card mb-4">
            <div class="card-body bg-light">
                <code class="text-danger">$query = Request::getQuery();</code>
            </div>
        </div>

        <p>Set query value :</p>

        <div class="card mb-4">
            <div class="card-body bg-light">
                <code class="text-danger">Request::setQuery(string $query, $value);</code>
            </div>
        </div>

        <p class="font-weight-bold">Retrieving $_POST fields</p>
        <p>All fields :</p>

        <div class="card mb-4">
            <div class="card-body bg-light">
                <code class="text-danger">$fields = Request::getFields();</code>
            </div>
        </div>

        <p>Single field :</p>

        <div class="card mb-4">
            <div class="card-body bg-light">
                <code class="text-danger">$field = Request::getField();</code>
            </div>
        </div>

        <p>Set field value :</p>

        <div class="card mb-4">
            <div class="card-body bg-light">
                <code class="text-danger">Request::setField(string $field, $value);</code>
            </div>
        </div>

        <p class="font-weight-bold">Retrieving uploaded files</p>
        <p>
            Uploaded files informations are retrieved from <span class="bg-light text-danger">$_FILES</span> array. 
            To retrieves single uploaded file, uses the following method :
        </p>

        <div class="card mb-4">
            <div class="card-body bg-light">
                <code class="text-danger">$file = Request::getFile(string $field, array $allowed_extensions = []);</code>
            </div>
        </div>

        <p>Retrieves multiple uploaded files :</p>

        <div class="card mb-4">
            <div class="card-body bg-light">
                <code class="text-danger">$files = Request::getFiles(string $field, array $allowed_extensions = []);</code>
            </div>
        </div>

        <p>To manage uploaded files, refer to <a href="<?= absolute_url('/docs/uploader') ?>" class="bg-light text-danger">Uploader</a> class.</p>
    </div>

    <div class="card-footer d-flex justify-content-between">
        <span>Next: <a href="<?= absolute_url('/docs/responses') ?>">HTTP Responses</a></span>
        <span>Previous: <a href="<?= absolute_url('/docs/views') ?>">Views</a></span>
    </div>
</div>

<?php $this->stop() ?>