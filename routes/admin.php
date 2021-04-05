<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use Framework\Routing\Route;
use App\Middlewares\RememberUser;
use App\Middlewares\CsrfProtection;
use App\Middlewares\SanitizeInputs;
use App\Middlewares\DashboardPolicy;
use App\Controllers\Admin\UsersController;
use App\Controllers\Admin\MediasController;
use App\Controllers\Admin\MessagesController;
use App\Controllers\Admin\SettingsController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\ActivitiesController;
use App\Controllers\Admin\NotificationsController;

/**
 * Admin panel routes
 */

//get requests routes
Route::group([
    //dashboard route
    'dashboard' => [
        'handler' => [DashboardController::class, 'index'],
        'name' => 'dashboard.index'
    ],

    //users routes
    'resources/users' => [
        'handler' => [UsersController::class, 'index'],
        'name' => 'users.index'
    ],

    'resources/users/new' => [
        'handler' => [UsersController::class, 'new'],
        'name' => 'users.new'
    ],

    'resources/users/{id}/edit' => [
        'handler' => [UsersController::class, 'edit'],
        'parameters' => ['id' => 'num'],
        'name' => 'users.edit'
    ],

    'resources/users/{id}/read' => [
        'handler' => [UsersController::class, 'read'],
        'parameters' => ['id' => 'num'],
        'name' => 'users.read'
    ],

    //medias routes
    'resources/medias' => [
        'handler' => [MediasController::class, 'index'],
        'name' => 'medias.index'
    ],

    'resources/medias/new' => [
        'handler' => [MediasController::class, 'new'],
        'name' => 'medias.new'
    ],

    'resources/medias/{id}/edit' => [
        'handler' => [MediasController::class, 'edit'],
        'parameters' => ['id' => 'num'],
        'name' => 'medias.edit'
    ],

    'resources/medias/{id}/read' => [
        'handler' => [MediasController::class, 'read'],
        'name' => 'medias.read'
    ],

    'resources/medias/search' => [
        'handler' => [MediasController::class, 'search'],
        'name' => 'medias.search'
    ],

    'resources/medias/{id}/download' => [
        'handler' => [MediasController::class, 'download'],
        'parameters' => ['id' => 'num'],
        'name' => 'medias.download'
    ],

    //account management routes
    'account/messages' => [
        'handler' => [MessagesController::class, 'index'],
        'name' => 'messages.index'
    ],

    'account/notifications' => [
        'handler' => [NotificationsController::class, 'index'],
        'name' => 'notifications.index'
    ],

    'account/{id}/settings' => [
        'handler' => [SettingsController::class, 'index'],
        'parameters' => ['id' => 'num'],
        'name' => 'settings.index'
    ],

    'account/activities' => [
        'handler' => [ActivitiesController::class, 'index'],
        'name' => 'activities.index'
    ],
])->by([
    'method' => 'get',
    'prefix' => 'admin',
    'middlewares' => [
        RememberUser::class,
        DashboardPolicy::class
    ]
]);

//delete requests routes
Route::group([
    'resources/users/?{id}?/delete' => [
        'handler' => [UsersController::class, 'delete'],
        'parameters' => ['id' => 'num'],
        'name' => 'users.delete'
    ],

    'resources/medias/?{id}?/delete' => [
        'handler' => [MediasController::class,'delete'],
        'parameters' => ['id' => 'num'],
        'name' => 'medias.delete'
    ],

    'account/messages/?{id}?/delete' => [
        'handler' => [MessagesController::class, 'delete'],
        'parameters' => ['id' => 'num'],
        'name' => 'messages.delete'
    ],

    'account/notifications/?{id}?/delete' => [
        'handler' => [NotificationsController::class, 'delete'],
        'parameters' => ['id' => 'num'],
        'name' => 'notifications.delete'
    ],

    'account/activities/delete' => [
        'handler' => [ActivitiesController::class, 'delete'],
        'name' => 'activities.delete'
        ]
])->by([
    'method' => 'delete',
    'prefix' => 'admin',
    'middlewares' => [
        RememberUser::class,
        CsrfProtection::class,
        DashboardPolicy::class
    ]
]);

//patch requests routes
Route::group([
    'account/messages/?{id}?/update' => [
        'handler' => [MessagesController::class, 'update'],
        'parameters' => ['id' => 'num'],
        'name' => 'messages.update'
    ],

    'account/notifications/?{id}?/update' => [
        'handler' => [NotificationsController::class, 'update'],
        'parameters' => ['id' => 'num'],
        'name' => 'notifications.update'
    ],

    'resources/users/{id}/update' => [
        'handler' => [UsersController::class, 'update'],
        'parameters' => ['id' => 'num'],
        'name' => 'users.update'
    ],

    'resources/medias/{id}/update' => [
        'handler' => [MediasController::class,'update'],
        'parameters' => ['id' => 'num'],
        'name' => 'medias.update'
    ],

    'account/settings/{id}/update' => [
        'handler' => [SettingsController::class, 'update'],
        'parameters' => ['id' => 'num'],
        'name' => 'settings.update'
    ],
])->by([
    'method' => 'patch',
    'prefix' => 'admin',
    'middlewares' => [
        RememberUser::class,
        CsrfProtection::class,
        DashboardPolicy::class
    ]
]);

//post requests routes
Route::group([
    //users routes
    'resources/users/create' => [
        'handler' => [UsersController::class, 'create'],
        'name' => 'users.create'
    ],

    'resources/users/export' => [
        'handler' => [UsersController::class, 'export'],
        'name' => 'users.export'
    ],

    //medias routes
    'resources/medias/create' => [
        'handler' => [MediasController::class,'create'],
        'name' => 'medias.create'
    ],

    //notifications routes
    'account/notifications/create' => [
        'handler' => [NotificationsController::class, 'create'],
        'name' => 'notifications.create'
    ],

    //messages routes
    'account/messages/create' => [
        'handler' => [MessagesController::class, 'create'],
        'name' => 'messages.create'
    ],

    'account/messages/{id}/reply' => [
        'handler' => [MessagesController::class, 'reply'],
        'parameters' => ['id' => 'num'],
        'name' => 'messages.reply'
    ],

    'account/activites/export' => [
        'handler' => [ActivitiesController::class, 'export'],
        'name' => 'messages.export'
    ]
])->by([
    'method' => 'post',
    'prefix' => 'admin',
    'middlewares' => [
        RememberUser::class,
        CsrfProtection::class,
        SanitizeInputs::class,
        DashboardPolicy::class
    ]
]);

//custom routes
