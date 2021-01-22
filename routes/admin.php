<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
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
    //users routes
    'resources/users' => ['handler' => 'Admin\UsersController@index'],
    'resources/users/new' => ['handler' => 'Admin\UsersController@new'],
    'resources/users/edit/{num}' => ['handler' => 'Admin\UsersController@edit'],
    'resources/users/view/{num}' => ['handler' => 'Admin\UsersController@view'],
    'resources/users/delete/{num}' => ['handler' => 'Admin\UsersController@delete'],

    //roles routes
    'resources/roles' => ['handler' => 'Admin\RolesController@index'],
    'resources/roles/new' => ['handler' => 'Admin\RolesController@new'],
    'resources/roles/edit/{num}' => ['handler' => 'Admin\RolesController@edit'],
    'resources/roles/view/{num}' => ['handler' => 'Admin\RolesController@view'],
    'resources/roles/delete/{num}' => ['handler' => 'Admin\RolesController@delete'],

    //medias routes
    'resources/medias' => ['handler' => 'Admin\MediasController@index'],
    'resources/medias/new' => ['handler' => 'Admin\MediasController@new'],
    'resources/medias/edit/{id:num}' => ['handler' => 'Admin\MediasController@edit'],
    'resources/medias/view/{id:num}' => ['handler' => 'Admin\MediasController@view'],
    'resources/medias/delete/{id:num}' => ['handler' => 'Admin\MediasController@delete'],

    //messages routes
    'account/messages' => ['handler' => 'Admin\MessagesController@index'],
    'account/messages/update/{num}' => ['handler' => 'Admin\MessagesController@update'],
    'account/messages/delete/{num}' => ['handler' => 'Admin\MessagesController@delete'],

    //notifications
    'account/notifications' => ['handler' => 'Admin\NotificationsController@index'],
    'account/notifications/update/{num}' => ['handler' => 'Admin\NotificationsController@update',],
    'account/notifications/delete/{num}' => ['handler' => 'Admin\NotificationsController@delete'],

    //settings
    'account/settings/{num}' => ['handler' => 'Admin\SettingsController@index'],

    //activities
    'account/activities' => ['handler' => 'Admin\ActivitiesController@index'],
    'account/activities/delete/{num}' => ['handler' => 'Admin\ActivitiesController@delete']
])->by([
    'method' => 'GET',
    'prefix' => 'admin',
    'middlewares' => [
        'RememberUser',
        'DashboardPolicy'
    ]
]);

Route::group([
    //users routes
    'resources/users/create' => ['handler' => 'Admin\UsersController@create'],
    'resources/users/update/{num}' => ['handler' => 'Admin\UsersController@update'],
    'resources/users/import' => ['handler' => 'Admin\UsersController@import'],
    'resources/users/export' => ['handler' => 'Admin\UsersController@export'],
    'resources/users/delete' => ['handler' => 'Admin\UsersController@delete'],

    //roles routes
    'resources/roles/create' => ['handler' => 'Admin\RolesController@create'],
    'resources/roles/update/{num}' => ['handler' => 'Admin\RolesController@update'],
    'resources/roles/import' => ['handler' => 'Admin\RolesController@import'],
    'resources/roles/export' => ['handler' => 'Admin\RolesController@export'],
    'resources/roles/delete' => ['handler' => 'Admin\RolesController@delete'],

    //medias routes
    'resources/medias/create' => ['handler' => 'Admin\MediasController@create'],
    'resources/medias/update/{id:num}' => ['handler' => 'Admin\MediasController@update'],
    'resources/medias/import' => ['handler' => 'Admin\MediasController@import'],
    'resources/medias/export' => ['handler' => 'Admin\MediasController@export'],
    'resources/medias/delete' => ['handler' => 'Admin\MediasController@delete'],

    //notifications routes
    'account/notifications/create' => ['handler' => 'Admin\NotificationsController@create'],
    'account/notifications/update' => ['handler' => 'Admin\NotificationsController@update'],
    'account/notifications/delete' => ['handler' => 'Admin\NotificationsController@delete'],

    //messages routes
    'account/messages/create' => ['handler' => 'Admin\MessagesController@create'],
    'account/messages/reply' => ['handler' => 'Admin\MessagesController@reply'],
    'account/messages/update' => ['handler' => 'Admin\MessagesController@update'],
    'account/messages/delete' => ['handler' => 'Admin\MessagesController@delete'],
    'account/messages/export' => ['handler' => 'Admin\MessagesController@export'],

    //settings routes
    'account/settings/update/{num}' => ['handler' => 'Admin\SettingsController@update'],

    //activities routes
    'account/activities/export' => ['handler' => 'Admin\ActivitiesController@export'],
    'account/activities/delete' => ['handler' => 'Admin\ActivitiesController@delete']
])->by([
    'method' => 'POST',
    'prefix' => 'admin',
    'middlewares' => [
        'RememberUser',
        'CsrfProtection',
        'SanitizeInputs',
        'DashboardPolicy'
    ]
]);

//custom routes
