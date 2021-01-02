<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
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

    'guides/routing' => ['handler' => function() {
        View::render('docs/guides/routing');
    }],

    'guides/middlewares' => ['handler' => function() {
        View::render('docs/guides/middlewares');
    }],

    'guides/controllers' => ['handler' => function() {
        View::render('docs/guides/controllers');
    }],

    'guides/views' => ['handler' => function() {
        View::render('docs/guides/views');
    }],

    'guides/requests' => ['handler' => function() {
        View::render('docs/guides/requests');
    }],

    'guides/responses' => ['handler' => function() {
        View::render('docs/guides/responses');
    }],

    'guides/client' => ['handler' => function() {
        View::render('docs/guides/client');
    }],

    'guides/redirections' => ['handler' => function() {
        View::render('docs/guides/redirections');
    }],

    'database/database' => ['handler' => function() {
        View::render('docs/database/database');
    }],

    'database/query-builder' => ['handler' => function() {
        View::render('docs/database/query-builder');
    }],

    'database/model' => ['handler' => function() {
        View::render('docs/database/model');
    }],

    'database/migrations' => ['handler' => function() {
        View::render('docs/database/migrations');
    }],

    'database/seeds' => ['handler' => function() {
        View::render('docs/database/seeds');
    }]
])->by([
    'method' => 'GET',
    'prefix' => 'docs'
]);
