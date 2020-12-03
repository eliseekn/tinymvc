<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use Framework\Routing\Route;

/**
 * Admin panel routes
 */

Route::group([
    'admin' => [],
    'admin/dashboard' => []
])->by([
    'method' => 'GET',
    'handler' => 'Admin\DashboardController@index'
]);

Route::group([
    'resources/users' => ['handler' => 'Admin\UsersController@index'],
    'resources/users/new' => ['handler' => 'Admin\UsersController@new'],
    'resources/users/edit/{id:num}' => ['handler' => 'Admin\UsersController@edit'],
    'resources/users/view/{id:num}' => ['handler' => 'Admin\UsersController@view'],
    'resources/users/delete/{id:num}' => ['handler' => 'Admin\UsersController@delete'],

    'resources/roles' => ['handler' => 'Admin\RolesController@index'],
    'resources/roles/new' => ['handler' => 'Admin\RolesController@new'],
    'resources/roles/edit/{id:num}' => ['handler' => 'Admin\RolesController@edit'],
    'resources/roles/view/{id:num}' => ['handler' => 'Admin\RolesController@view'],
    'resources/roles/delete/{id:num}' => ['handler' => 'Admin\RolesController@delete'],

    'account/messages' => ['handler' => 'Admin\MessagesController@index'],
    'account/messages/update/{id:num}' => ['handler' => 'Admin\MessagesController@update'],

    'account/notifications' => ['handler' => 'Admin\NotificationsController@index'],
    'account/notifications/update/{id:num}' => ['handler' => 'Admin\NotificationsController@update'],    
    'account/notifications/delete/{id:num}' => ['handler' => 'Admin\NotificationsController@delete'],

    'account/messages/delete/{id:num}' => ['handler' => 'Admin\MessagesController@delete'],

    'account/settings/{id:num}' => ['handler' => 'Admin\SettingsController@index'],

    'account/activities' => ['handler' => 'Admin\ActivitiesController@index'],
    'account/activities/delete/{id:num}' => ['handler' => 'Admin\ActivitiesController@delete']
])->by([
    'method' => 'GET',
    'prefix' => 'admin',
    'middlewares' => [
        'RememberUser',
        'DashboardPolicy'
    ]
]);

Route::group([
    'resources/users/create' => ['handler' => 'Admin\UsersController@create'],
    'resources/users/update/{id:num}' => ['handler' => 'Admin\UsersController@update'],
    'resources/users/import' => ['handler' => 'Admin\UsersController@import'],
    'resources/users/export' => ['handler' => 'Admin\UsersController@export'],
    'resources/users/delete' => ['handler' => 'Admin\UsersController@delete'],

    'resources/roles/create' => ['handler' => 'Admin\RolesController@create'],
    'resources/roles/update/{id:num}' => ['handler' => 'Admin\RolesController@update'],
    'resources/roles/import' => ['handler' => 'Admin\RolesController@import'],
    'resources/roles/export' => ['handler' => 'Admin\RolesController@export'],
    'resources/roles/delete' => ['handler' => 'Admin\RolesController@delete'],

    'account/notifications/create' => ['handler' => 'Admin\NotificationsController@create'],
    'account/notifications/update' => ['handler' => 'Admin\NotificationsController@update'],
    'account/notifications/delete' => ['handler' => 'Admin\NotificationsController@delete'],

    'account/messages/create' => ['handler' => 'Admin\MessagesController@create'],
    'account/messages/reply' => ['handler' => 'Admin\MessagesController@reply'],
    'account/messages/update' => ['handler' => 'Admin\MessagesController@update'],
    'account/messages/delete' => ['handler' => 'Admin\MessagesController@delete'],
    'account/messages/export' => ['handler' => 'Admin\MessagesController@export'],

    'account/settings/update/{id:num}' => ['handler' => 'Admin\SettingsController@update'],

    'account/activities/export' => ['handler' => 'Admin\ActivitiesController@export'],
    'account/activities/delete' => ['handler' => 'Admin\ActivitiesController@delete']
])->by([
    'method' => 'POST',
    'prefix' => 'admin',
    'middlewares' => [
        'RememberUser',
        'CsrfProtection',
        'SanitizeFields',
        'DashboardPolicy'
    ]
]);
