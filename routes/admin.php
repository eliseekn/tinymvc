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

//get requests routes
Route::group([
    //dashboard route
    'dashboard' => ['handler' => 'Admin\DashboardController@index'],

    //users routes
    'resources/users' => ['handler' => 'Admin\UsersController@index'],
    'resources/users/new' => ['handler' => 'Admin\UsersController@new'],
    'resources/users/edit/{num}' => ['handler' => 'Admin\UsersController@edit'],
    'resources/users/read/{num}' => ['handler' => 'Admin\UsersController@read'],

    //medias routes
    'resources/medias' => ['handler' => 'Admin\MediasController@index'],
    'resources/medias/new' => ['handler' => 'Admin\MediasController@new'],
    'resources/medias/edit/{num}' => ['handler' => 'Admin\MediasController@edit'],
    'resources/medias/read/{num}' => ['handler' => 'Admin\MediasController@read'],
    'resources/medias/search' => ['handler' => 'Admin\MediasController@search'],

    //account management routes
    'account/messages' => ['handler' => 'Admin\MessagesController@index'],
    'account/notifications' => ['handler' => 'Admin\NotificationsController@index'],
    'account/settings/{num}' => ['handler' => 'Admin\SettingsController@index'],
    'account/activities' => ['handler' => 'Admin\ActivitiesController@index'],
])->by([
    'method' => 'get',
    'prefix' => 'admin',
    'middlewares' => [
        'RememberUser',
        'DashboardPolicy'
    ]
]);

//delete requests routes
Route::group([
    'resources/users/delete/?{num}?' => ['handler' => 'Admin\UsersController@delete'],
    'resources/medias/delete/?{num}?' => ['handler' => 'Admin\MediasController@delete'],
    'account/messages/delete/?{num}?' => ['handler' => 'Admin\MessagesController@delete'],
    'account/notifications/delete/?{num}?' => ['handler' => 'Admin\NotificationsController@delete'],
    'account/activities/delete' => ['handler' => 'Admin\ActivitiesController@delete']
])->by([
    'method' => 'delete',
    'prefix' => 'admin',
    'middlewares' => [
        'RememberUser',
        'CsrfProtection',
        'DashboardPolicy'
    ]
]);

//patch requests routes
Route::group([
    'account/messages/update/?{num}?' => ['handler' => 'Admin\MessagesController@update'],
    'account/notifications/update/?{num}?' => ['handler' => 'Admin\NotificationsController@update'],
    'resources/users/update/{num}' => ['handler' => 'Admin\UsersController@update'],
    'resources/medias/update/{num}' => ['handler' => 'Admin\MediasController@update'],
    'account/settings/update/{num}' => ['handler' => 'Admin\SettingsController@update'],
])->by([
    'method' => 'patch',
    'prefix' => 'admin',
    'middlewares' => [
        'RememberUser',
        'CsrfProtection',
        'DashboardPolicy'
    ]
]);

//post requests routes
Route::group([
    //users routes
    'resources/users/create' => ['handler' => 'Admin\UsersController@create'],
    'resources/users/import' => ['handler' => 'Admin\UsersController@import'],
    'resources/users/export' => ['handler' => 'Admin\UsersController@export'],

    //medias routes
    'resources/medias/create' => ['handler' => 'Admin\MediasController@create'],
    'resources/medias/import' => ['handler' => 'Admin\MediasController@import'],
    'resources/medias/export' => ['handler' => 'Admin\MediasController@export'],

    //notifications routes
    'account/notifications/create' => ['handler' => 'Admin\NotificationsController@create'],

    //messages routes
    'account/messages/create' => ['handler' => 'Admin\MessagesController@create'],
    'account/messages/reply' => ['handler' => 'Admin\MessagesController@reply'],
    'account/messages/export' => ['handler' => 'Admin\MessagesController@export'],

    //activities routes
    'account/activities/export' => ['handler' => 'Admin\ActivitiesController@export'],
])->by([
    'method' => 'post',
    'prefix' => 'admin',
    'middlewares' => [
        'RememberUser',
        'CsrfProtection',
        'SanitizeInputs',
        'DashboardPolicy'
    ]
]);

//custom routes
