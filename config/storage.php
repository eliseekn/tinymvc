<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

/*
 * Storage configuration
 */

return [
    'tests' => absolute_path('tests'),
    'public' => absolute_path('public'),
    'routes' => absolute_path('routes'),

    'views' => absolute_path('resources.views'),
    'translations' => absolute_path('resources.translations'),
    'assets' => absolute_path('resources.assets'),

    'migrations' => absolute_path('app.Database.Migrations'),
    'seeders' => absolute_path('app.Database.Seeders'),
    'factories' => absolute_path('app.Database.Factories'),
    'entities' => absolute_path('app.Database.Entities'),
    'models' => absolute_path('app.Database.Models'),

    'console' => absolute_path('app.Console'),
    'useCases' => absolute_path('app.UseCases'),
    'events' => absolute_path('app.Events'),
    'helpers' => absolute_path('app.Helpers'),
    'exceptions' => absolute_path('app.Exceptions'),
    'enums' => absolute_path('app.Enums'),
    'tasks' => absolute_path('app.Tasks'),
    'observers' => absolute_path('app.Observers'),
    'policies' => absolute_path('app.Policies'),

    'controllers' => absolute_path('app.Http.Controllers'),
    'middlewares' => absolute_path('app.Http.Middlewares'),
    'validators' => absolute_path('app.Http.Validation.Validators'),
    'rules' => absolute_path('app.Http.Validation.Rules'),
    'resources' => absolute_path('app.Http.Resources'),

    'mails' => absolute_path('app.Notifications.Mails'),
    'sms' => absolute_path('app.Notifications.Sms'),

    'uploads' => absolute_path('storage.uploads'),
    'tmp' => absolute_path('storage.tmp'),
    'logs' => absolute_path('storage.logs'),
    'cache' => absolute_path('storage.cache'),
    'sqlite' => absolute_path('storage.sqlite'),

    'stubs' => absolute_path('core.Stubs'),
];
