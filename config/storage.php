<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

/**
 * Storage configuration
 */

return [
    'uploads' => absolute_path('storage.uploads'),
    'public' => absolute_path('public'),
    'routes' => absolute_path('routes'),
    'views' => absolute_path('views'),
    'migrations' => absolute_path('app.Database.Migrations'),
    'seeds' => absolute_path('app.Database.Seeds'),
    'factories' => absolute_path('app.Database.Factories'),
    'stubs' => absolute_path('core.Stubs'),
    'lang' => absolute_path('resources.lang'),
    'controllers' => absolute_path('app.Http.Controllers'),
    'models' => absolute_path('app.Database.Models'),
    'middlewares' => absolute_path('app.Http.Middlewares'),
    'validators' => absolute_path('app.Http.Validators'),
    'logs' => absolute_path('storage.logs'),
    'cache' => absolute_path('storage.cache'),
    'mails' => absolute_path('app.Mails'),
    'helpers' => absolute_path('app.Helpers'),
    'exceptions' => absolute_path('app.Exceptions'),
    'tests' => absolute_path('tests'),
    'console' => absolute_path('app.Console'),
    'sqlite' => absolute_path('storage.sqlite'),
    'actions' => absolute_path('app.Http.Actions'),
    'events' => absolute_path('app.Events')
];
