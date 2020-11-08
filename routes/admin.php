<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

use Framework\Routing\Route;
use Framework\Routing\View;

/**
 * Admin panel routes
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
    'resources/users/delete/{id:num}' => ['handler' => 'Admin\UsersController@delete'],
    'resources/roles/delete/{id:num}' => ['handler' => 'Admin\RolesController@delete'],

    'account/notifications/delete/{id:num}' => ['handler' => 'Admin\NotificationsController@delete'],
])->by([
    'method' => 'DELETE',
    'prefix' => 'admin',
    'middlewares' => [
        'RememberUser',
        'AdminPolicy'
    ]
]);

Route::get('/admin/account/notifications/update/{id:num}', [
    'handler' => 'Admin\NotificationsController@update',
    'middlewares' => [
        'RememberUser',
        'AdminPolicy'
    ]
]);

Route::group([
    'resources/users' => ['handler' => 'Admin\UsersController@index'],
    'resources/users/new' => ['handler' => 'Admin\UsersController@new'],
    'resources/users/edit/{id:num}' => ['handler' => 'Admin\UsersController@edit'],
    'resources/users/view/{id:num}' => ['handler' => 'Admin\UsersController@view'],

    'resources/roles' => ['handler' => 'Admin\RolesController@index'],
    'resources/roles/new' => ['handler' => 'Admin\RolesController@new'],
    'resources/roles/edit/{id:num}' => ['handler' => 'Admin\RolesController@edit'],
    'resources/roles/view/{id:num}' => ['handler' => 'Admin\RolesController@view'],

    'account/settings/{id:num}' => ['handler' => 'Admin\SettingsController@index'],
    'account/notifications' => ['handler' => 'Admin\NotificationsController@index'],
    'account/activities' => ['handler' => 'Admin\ActivitiesController@index']
])->by([
    'method' => 'GET',
    'prefix' => 'admin',
    'middlewares' => [
        'RememberUser',
        'AdminPolicy'
    ]
]);

Route::group([
    'resources/users/delete' => ['handler' => 'Admin\UsersController@delete'],
    'resources/users/import' => ['handler' => 'Admin\UsersController@import'],
    'resources/users/export' => ['handler' => 'Admin\UsersController@export'],

    'resources/roles/delete' => ['handler' => 'Admin\RolesController@delete'],
    'resources/roles/import' => ['handler' => 'Admin\RolesController@import'],
    'resources/roles/export' => ['handler' => 'Admin\RolesController@export'],

    'account/notifications/delete' => ['handler' => 'Admin\NotificationsController@delete'],
    'account/notifications/update' => ['handler' => 'Admin\NotificationsController@update'],

    'account/activities/delete' => ['handler' => 'Admin\ActivitiesController@delete'],
    'account/activities/update' => ['handler' => 'Admin\ActivitiesController@update']
])->by([
    'method' => 'POST',
    'prefix' => 'admin',
    'middlewares' => [
        'RememberUser',
        'AdminPolicy'
    ]
]);

Route::group([
    'resources/users/create' => ['handler' => 'Admin\UsersController@create'],
    'resources/users/update/{id:num}' => ['handler' => 'Admin\UsersController@update'],

    'resources/roles/create' => ['handler' => 'Admin\RolesController@create'],
    'resources/roles/update/{id:num}' => ['handler' => 'Admin\RolesController@update'],

    'account/settings/update/{id:num}' => ['handler' => 'Admin\SettingsController@update'],
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
