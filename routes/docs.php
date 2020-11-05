<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

use Framework\Routing\View;
use Framework\Routing\Route;

/**
 * Documentation routes
 */

Route::get('docs', [
    'handler' => function() {
        View::render('docs/index');
    }]
);

Route::group([
    'getting-started' => ['handler' => function() {
        View::render('docs/getting-started');
    }],

    'routing' => ['handler' => function() {
        View::render('docs/guides/routing');
    }],

    'middlewares' => ['handler' => function() {
        View::render('docs/guides/middlewares');
    }],

    'controllers' => ['handler' => function() {
        View::render('docs/guides/controllers');
    }],

    'views' => ['handler' => function() {
        View::render('docs/guides/views');
    }],

    'requests' => ['handler' => function() {
        View::render('docs/guides/requests');
    }],

    'responses' => ['handler' => function() {
        View::render('docs/guides/responses');
    }],

    'client' => ['handler' => function() {
        View::render('docs/guides/client');
    }],

    'redirections' => ['handler' => function() {
        View::render('docs/guides/redirections');
    }]
])->by([
    'method' => 'GET',
    'prefix' => 'docs'
]);
