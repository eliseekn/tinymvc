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
    'uploads' => absolute_path('storage.uploads'),
    'tmp' => absolute_path('storage.tmp'),
    'public' => absolute_path('public'),
    'routes' => absolute_path('routes'),
    'views' => absolute_path('resources.views'),
    'migrations' => absolute_path('app.Database.Migrations'),
    'seeders' => absolute_path('app.Database.Seeders'),
    'factories' => absolute_path('app.Database.Factories'),
    'stubs' => absolute_path('core.Stubs'),
    'translations' => absolute_path('resources.translations'),
    'controllers' => absolute_path('app.Http.Controllers'),
    'models' => absolute_path('app.Database.Models'),
    'middlewares' => absolute_path('app.Http.Middlewares'),
    'validators' => absolute_path('app.Http.Validation.Validators'),
    'rules' => absolute_path('app.Http.Validation.Rules'),
    'logs' => absolute_path('storage.logs'),
    'cache' => absolute_path('storage.cache'),
    'mails' => absolute_path('app.Notifications.Mails'),
    'sms' => absolute_path('app.Notifications.Sms'),
    'helpers' => absolute_path('app.Helpers'),
    'exceptions' => absolute_path('app.Exceptions'),
    'tests' => absolute_path('tests'),
    'console' => absolute_path('app.Console'),
    'sqlite' => absolute_path('storage.sqlite'),
    'useCases' => absolute_path('app.UseCases'),
    'events' => absolute_path('app.Events'),
    'assets' => absolute_path('resources.assets'),
    'enums' => absolute_path('app.Enums'),
    'tasks' => absolute_path('app.Tasks'),
    'resources' => absolute_path('app.Http.Resources'),
    'observers' => absolute_path('app.Observers'),
    'policies' => absolute_path('app.Policies'),
];
