<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

use Framework\Routing\Route;

/**
 * Set admin panel routes
 */

Route::group([
    'admin' => [],
    'admin/dashboard' => []
])->by([
    'method' => 'GET',
    'handler' => 'Admin\DashboardController@index',
    'middlewares' => [
        'RememberUser',
        'AdminPolicy'
    ]
]);

Route::group([
    'users/delete/{id:num}' => ['handler' => 'Admin\UsersController@delete'],
    'roles/delete/{id:num}' => ['handler' => 'Admin\RolesController@delete'],

    'notifications/delete/{id:num}' => ['handler' => 'Admin\NotificationsController@delete'],
])->by([
    'method' => 'DELETE',
    'prefix' => 'admin',
    'middlewares' => [
        'RememberUser',
        'AdminPolicy'
    ]
]);

Route::get('/admin/notifications/update/{id:num}', [
    'handler' => 'Admin\NotificationsController@update',
    'middlewares' => [
        'RememberUser',
        'AdminPolicy'
    ]
]);

Route::group([
    'users' => ['handler' => 'Admin\UsersController@index'],
    'users/new' => ['handler' => 'Admin\UsersController@new'],
    'users/edit/{id:num}' => ['handler' => 'Admin\UsersController@edit'],
    'users/view/{id:num}' => ['handler' => 'Admin\UsersController@view'],

    'roles' => ['handler' => 'Admin\RolesController@index'],
    'roles/new' => ['handler' => 'Admin\RolesController@new'],
    'roles/edit/{id:num}' => ['handler' => 'Admin\RolesController@edit'],
    'roles/view/{id:num}' => ['handler' => 'Admin\RolesController@view'],

    'settings/{id:num}' => ['handler' => 'Admin\SettingsController@index'],
    'notifications' => ['handler' => 'Admin\NotificationsController@index']
])->by([
    'method' => 'GET',
    'prefix' => 'admin',
    'middlewares' => [
        'RememberUser',
        'AdminPolicy'
    ]
]);

Route::group([
    'users/delete' => ['handler' => 'Admin\UsersController@delete'],
    'users/import' => ['handler' => 'Admin\UsersController@import'],
    'users/export' => ['handler' => 'Admin\UsersController@export'],

    'roles/delete' => ['handler' => 'Admin\RolesController@delete'],
    'roles/import' => ['handler' => 'Admin\RolesController@import'],
    'roles/export' => ['handler' => 'Admin\RolesController@export'],

    'notifications/delete' => ['handler' => 'Admin\NotificationsController@delete'],
    'notifications/update' => ['handler' => 'Admin\NotificationsController@update']
])->by([
    'method' => 'POST',
    'prefix' => 'admin',
    'middlewares' => [
        'RememberUser',
        'AdminPolicy'
    ]
]);

Route::group([
    'users/create' => ['handler' => 'Admin\UsersController@create'],
    'users/update/{id:num}' => ['handler' => 'Admin\UsersController@update'],

    'roles/create' => ['handler' => 'Admin\RolesController@create'],
    'roles/update/{id:num}' => ['handler' => 'Admin\RolesController@update'],

    'settings/update/{id:num}' => ['handler' => 'Admin\SettingsController@update'],
])->by([
    'method' => 'POST',
    'prefix' => 'admin',
    'middlewares' => [
        'RememberUser',
        'CsrfProtection',
        'SanitizeFields',
        'AdminPolicy'
    ]
]);
