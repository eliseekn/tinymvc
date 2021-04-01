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
        'folder' => 'tinymvc', //leave empty if you are using 'www' root
        'url' => 'http://localhost/tinymvc', //remove folder if you are using 'www' root
        'lang' => 'en'
    ],

    'mysql' => [
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

    'mailer' => [
        'default' => 'smtp',
        'from' => 'tiny@mvc.framework',
        'name' => 'TinyMVC',

        'sendmail' => [],

        'smtp' => [
            'host' => 'localhost',
            'port' => 1025,
            'auth' => false,
            'secure' => false,
            'tls' => true,
            'username' => '',
            'password' => ''
        ]
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
        'enc_key' => base64_encode('write_something_here_to_set_your_single_encryption_key'),
        'encrypt_cookies' => true,

        'auth' => [
            'max_attempts' => 5, //set to 0 to disable
            'unlock_timeout' => 1, //in minute
            'email_confirmation' => true
        ]
    ],

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
        'mails' => absolute_path('app.Mails')
    ],

    'session' => [
        'lifetime' => 3600 * 5, //5 hours in seconds
    ]
];
