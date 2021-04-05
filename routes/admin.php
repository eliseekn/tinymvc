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
    'dashboard' => ['handler' => [DashboardController::class, 'index']],

    //users routes
    'resources/users' => ['handler' => [UsersController::class, 'index']],
    'resources/users/new' => ['handler' => [UsersController::class, 'new']],
    'resources/users/edit/{num}' => ['handler' => [UsersController::class, 'edit']],
    'resources/users/read/{num}' => ['handler' => [UsersController::class, 'read']],

    //medias routes
    'resources/medias' => ['handler' => [MediasController::class, 'index']],
    'resources/medias/new' => ['handler' => [MediasController::class, 'new']],
    'resources/medias/edit/{num}' => ['handler' => [MediasController::class, 'edit']],
    'resources/medias/read/{num}' => ['handler' => [MediasController::class, 'read']],
    'resources/medias/search' => ['handler' => [MediasController::class, 'search']],
    'resources/medias/download/{num}' => ['handler' => [MediasController::class, 'download']],

    //account management routes
    'account/messages' => ['handler' => [MessagesController::class, 'index']],
    'account/notifications' => ['handler' => [NotificationsController::class, 'index']],
    'account/settings/{num}' => ['handler' => [SettingsController::class, 'index']],
    'account/activities' => ['handler' => [ActivitiesController::class, 'index']],
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
    'resources/users/delete/?{num}?' => ['handler' => [UsersController::class, 'delete']],
    'resources/medias/delete/?{num}?' => ['handler' => [MediasController::class,'delete']],
    'account/messages/delete/?{num}?' => ['handler' => [MessagesController::class, 'delete']],
    'account/notifications/delete/?{num}?' => ['handler' => [NotificationsController::class, 'delete']],
    'account/activities/delete' => ['handler' => [ActivitiesController::class, 'delete']]
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
    'account/messages/update/?{num}?' => ['handler' => [MessagesController::class, 'update']],
    'account/notifications/update/?{num}?' => ['handler' => [NotificationsController::class, 'update']],
    'resources/users/update/{num}' => ['handler' => [UsersController::class, 'update']],
    'resources/medias/update/{num}' => ['handler' => [MediasController::class,'update']],
    'account/settings/update/{num}' => ['handler' => [SettingsController::class, 'update']],
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
    'resources/users/create' => ['handler' => [UsersController::class, 'create']],
    'resources/users/import' => ['handler' => [UsersController::class, 'import']],
    'resources/users/export' => ['handler' => [UsersController::class, 'export']],

    //medias routes
    'resources/medias/create' => ['handler' => [MediasController::class,'create']],
    'resources/medias/import' => ['handler' => [MediasController::class,'import']],
    'resources/medias/export' => ['handler' => [MediasController::class,'export']],

    //notifications routes
    'account/notifications/create' => ['handler' => [NotificationsController::class, 'create']],

    //messages routes
    'account/messages/create' => ['handler' => [MessagesController::class, 'create']],
    'account/messages/reply/{num}' => ['handler' => [MessagesController::class, 'reply']],
    'account/messages/export' => ['handler' => [MessagesController::class, 'export']],

    //activities routes
    'account/activities/export' => ['handler' => [ActivitiesController::class, 'export']],
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
