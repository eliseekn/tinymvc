<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

/**
 * Storage configuration
 */

$config = [
    'storage' => [
        'uploads' => absolute_path('storage.uploads'),
        'public' => absolute_path('public'),
        'routes' => absolute_path('routes'),
        'views' => absolute_path('resources.views'),
        'migrations' => absolute_path('app.Database.Migrations'),
        'seeds' => absolute_path('app.Database.Seeds'),
        'stubs' => absolute_path('resources.stubs'),
        'controllers' => absolute_path('app.Controllers'),
        'models' => absolute_path('app.Database.Models'),
        'middlewares' => absolute_path('app.Middlewares'),
        'requests' => absolute_path('app.Requests'),
        'logs' => absolute_path('storage.logs'),
        'cache' => absolute_path('storage.cache'),
        'mails' => absolute_path('app.Mails')
    ]
];
