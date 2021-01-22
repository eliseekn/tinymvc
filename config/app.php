<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

/**
 * Application configuration
 */

$config = [
    'app' => [
        'name' => 'TinyMVC',
        'folder' => '/tinymvc', //leave empty if you are using 'www' root
        'url' => 'http://localhost/tinymvc', //remove folder if you are using 'www' root
        'lang' => 'en'
    ],

    //mysql
    'db' => [
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'host' => 'localhost',
        'name' => 'test',
        'username' => 'root',
        'password' => 'root',
        'table_prefix' => '',
        'timestamps' => true,
        'storage_engine' => 'InnoDB'

    ],

    //smtp
    'mailer' => [
        'host' => 'localhost',
        'port' => 25,
        'username' => '',
        'password' => '',
        'from' => 'admin@mail.com',
        'name' => 'Admin'
    ],

    'errors' => [
        'display' => true,
        'log' => false,

        'views' => [
            '403' => 'errors' . DIRECTORY_SEPARATOR . '403',
            '404' => 'errors' . DIRECTORY_SEPARATOR . '404',
            '500' => 'errors' . DIRECTORY_SEPARATOR . '500'
        ]
    ],

    'security' => [
        'enc_key' => base64_encode('write_something_here_to_generate_a_single_encryption_key'),
        'encrypt_cookies' => true,

        'auth' => [
            'max_attempts' => 5, //set to 0 to disable
            'unlock_timeout' => 1, //in minute
            'email_confirmation' => false
        ]
    ],

    'storage' => [
        'storage' => absolute_path('storage'),
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
        'logs' => absolute_path('storage.logs')
    ],

    'session' => [
        'lifetime' => 3600 * 2, //2 hours in seconds
    ]
];
