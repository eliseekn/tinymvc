<?php $this->layout('docs/layout', [
    'page_title' => 'Routing | Documentation'
]) ?>

<?php $this->start('page_content') ?>

<?php $this->insert('partials/breadcrumb', [
    'items' => [
        'Documentation' => absolute_url('/docs'),
        'Routing' => ''
    ]
]) ?>

<div class="card mb-5" id="basic-routing">
    <div class="card-header ">
        <span class=" lead">Basic routing</span>
    </div>

    <div class="card-body">
        <p class="font-weight-bold">Configuration</p>
        <p>
            You can define your application routes in <span class="bg-light text-danger">routes/web.php</span> file. TinyMVC provides
            a very expressive method of defining route :
        </p>

        <div class="card mb-4">
            <div class="card-body bg-light">
                <pre class="m-0 text-danger"><code>Route::add('/home', [
    'method' => 'GET',
    'handler' => function () {
        Response::send([], 'Hello world!');
    }
]);</code></pre>
            </div>
        </div>

        <p class="font-weight-bold">Routes methods</p>
        <p>Here are defined all routes methods shortcuts you can use to handle HTTP requests :</p>

        <div class="card mb-4">
            <div class="card-body bg-light">
                <pre class="m-0 text-danger"><code>Route::get(string $uri, array $parameters)
Route::post(string $uri, array $parameters)
Route::put(string $uri, array $parameters)
Route::patch(string $uri, array $parameters)
Route::delete(string $uri, array $parameters)
Route::options(string $uri, array $parameters)</code></pre>
            </div>
        </div>

        <p>You can also handle multiple HTTP requests methods like in the example below :</p>

        <div class="card mb-4">
            <div class="card-body bg-light">
                <pre class="m-0 text-danger"><code>Route::add(string $uri, [
    'method' => 'GET|POST|PUT'
]);</code></pre>
            </div>
        </div>

        <p>Or you can handle all HTTP requests methods by using the shortcut method <span class="bg-light text-danger">any</span> :</p>

        <div class="card">
            <div class="card-body bg-light">
                <code class="text-danger">Route::any(string $uri, array $parameters)</code>
            </div>
        </div>
    </div>

    <div class="card-footer d-flex justify-content-between">
        <span>Next: <a href="#route-parameters">Route parameters</a></span>
        <span>Previous: <a href="<?= absolute_url('/docs/getting-started') ?>">Getting started</a></span>
    </div>
</div>

<div class="card mb-5" id="route-parameters">
    <div class="card-header ">
        <span class=" lead" id="route-parameters">Route parameters</span>
    </div>

    <div class="card-body">
        <p class="font-weight-bold">List of available route parameters with name and descripton</p>

        <table class="table table-striped w-50 mb-4 border-bottom">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Type</th>
                    <th scope="col">Description</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">method</th>
                    <td>string</td>
                    <td>Defines allowed HTTP requests methods</td>
                </tr>
                <tr>
                    <th scope="row">handler</th>
                    <td>string</td>
                    <td>
                        Defines action to call on request. Value can be <span class="bg-light text-danger">closure</span> or
                        <span class="bg-light text-danger">ControllerClass@method</span>
                    </td>
                </tr>
                <tr>
                    <th scope="row">name</th>
                    <td>string</td>
                    <td>Defines route unique name</td>
                </tr>
                    <th scope="row">prefix</th>
                    <td>string</td>
                    <td>
                        Defines route prefix name. This parameter is only available when using 
                        <a href="#routes-grouping" class="bg-light text-danger">Route::group()</a> method
                    </td>
                </tr>
                <tr>
                    <th scope="row">middlewares</th>
                    <td>array</td>
                    <td>Defines list of middlewares to call before execute handler. Middlewares are called in the order they are given</td>
                </tr>
            </tbody>
        </table>

        <p class="font-weight-bold">Basic route parameters definition</p>

        <div class="card mb-4">
            <div class="card-body bg-light">
                <pre class="m-0 text-danger"><code>Route::get('/home', [
    'handler' => 'HomeController::index',
    'name' => 'home',
    'middlewares' => [
        'CsrfProtection'
    ]
]);</code></pre>
            </div>
        </div>

        <p class="font-weight-bold" id="routes-grouping">Routes grouping</p>
        <p>TinyMVC provides a method for grouping routes with same parameters :</p>

        <div class="card mb-4">
            <div class="card-body bg-light">
                <code class="text-danger">Route::group(array $routes)->by(array $parameters);</code>
            </div>
        </div>

        <p>Example :</p>

        <div class="card">
            <div class="card-body bg-light">
                <pre class="m-0 text-danger"><code>Route::group([
    '/' => [],
    '/home' => []
])->by([
    'method' => 'GET',
    'handler' => 'HomeController@index',
    'name' => 'home'
]);</code></pre>
            </div>
        </div>
    </div>

    <div class="card-footer d-flex justify-content-between">
        <span>Next: <a href="#uri-parameters">Route URI</a></span>
        <span>Previous: <a href="#basic-routing">Basic routing</a></span>
    </div>
</div>

<div class="card" id="uri-parameters">
    <div class="card-header ">
        <span class=" lead" id="uri-parameters">URI parameters</span>
    </div>

    <div class="card-body">
        <p class="font-weight-bold">Required parameters</p>
        <p>You can capture required URI parameters :</p>

        <div class="card mb-4">
            <div class="card-body bg-light">
                <pre class="m-0 text-danger"><code>Route::get('/welcome/{name:str}', [
    'handler' => function ($name) {
        Response::send([], 'Welcome ' . $name . '!');       
    }
]);</code></pre>
            </div>
        </div>

        <p class="font-weight-bold">List of URI parameters with name and descripton</p>

        <table class="table table-striped w-50 mb-4 border-bottom">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Type</th>
                    <th scope="col">Description</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">str</th>
                    <td>string</td>
                    <td>
                        Accept <span class="bg-light text-danger">lowercase</span>, <span class="bg-light text-danger">uppercase</span>, 
                        <span class="bg-light text-danger">dash</span> and <span class="bg-light text-danger">underscore</span>. The 
                        corresponding regular expression is <span class="bg-light text-danger">[a-zA-Z-_]+</span>
                    </td>
                </tr>
                <tr>
                    <th scope="row">num</th>
                    <td>digit</td>
                    <td>
                        Accept digit character in the range of <span class="bg-light text-danger">0-9</span> with 1 or more digit. The 
                        corresponding regular expression is <span class="bg-light text-danger">\d+</span>
                    </td>
                </tr>
                <tr>
                    <th scope="row">any</th>
                    <td>all</td>
                    <td>
                        Accept both <span class="bg-light text-danger">str</span> and <span class="bg-light text-danger">num</span> characters 
                        except <span class="bg-light text-danger">/</span>. The corresponding regular expression is <span class="bg-light text-danger">[^/]+</span>
                    </td>
                </tr>
            </tbody>
        </table>

        <p class="font-weight-bold">Optionals parameters</p>
        <p>You specify optionals parameters by adding a <span class="bg-light text-danger">?</span> mark at the end of parameter definition :</p>

        <div class="card mb-4">
            <div class="card-body bg-light">
                <pre class="m-0 text-danger"><code>Route::get('/welcome/{name:str}?', [
    'handler' => function (?string $name = null) {
        $visitor = is_null($name) ? 'Stranger' : $name;
        Response::send([], 'Welcome ' . $visitor . '!');       
    }
]);</code></pre>
            </div>
        </div>
    </div>

    <div class="card-footer d-flex justify-content-between">
        <span>Next: <a href="<?= absolute_url('/docs/guides/middlewares') ?>">Middlewares</a></span>
        <span>Previous: <a href="#route-parameters">Route parameters</a></span>
    </div>
</div>

<?php $this->stop() ?>