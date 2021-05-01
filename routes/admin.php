<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use Framework\Routing\Route;
use App\Database\Repositories\Roles;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\MediasController;
use App\Http\Controllers\Admin\TicketsController;
use App\Http\Controllers\Admin\InvoicesController;
use App\Http\Controllers\Admin\MessagesController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ActivitiesController;
use App\Http\Controllers\Admin\NotificationsController;
use App\Http\Controllers\Admin\WalletController;

/**
 * Admin routes
 */

Route::groupPrefix('admin', function () {
    Route::groupMiddlewares(['remember', 'auth'], function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

        Route::get('resources/users', [UsersController::class, 'index'])->name('users.index');
        Route::get('resources/users/new', [UsersController::class, 'new'])->name('users.new')->protected(Roles::ROLE[2]);
        Route::get('resources/users/{id}/edit', [UsersController::class, 'edit'])->name('users.edit')->where(['id' => 'num'])->protected(Roles::ROLE[2]);
        Route::get('resources/users/{id}/read', [UsersController::class, 'read'])->name('users.read')->where(['id' => 'num'])->protected(Roles::ROLE[2]);

        Route::get('resources/medias', [MediasController::class, 'index'])->name('medias.index');
        Route::get('resources/medias/search', [MediasController::class, 'search'])->name('medias.search');
        Route::get('resources/medias/search?q=', [MediasController::class, 'search'])->name('medias.search.q');
        Route::get('resources/medias/new', [MediasController::class, 'new'])->name('medias.new');
        Route::get('resources/medias/{id}/edit', [MediasController::class, 'edit'])->name('medias.edit')->where(['id' => 'num']);
        Route::get('resources/medias/{id}/read', [MediasController::class, 'read'])->name('medias.read')->where(['id' => 'num']);
        Route::get('resources/medias/{id}/download', [MediasController::class, 'download'])->name('medias.download')->where(['id' => 'num']);

        Route::get('resources/tickets/new', [TicketsController::class, 'new'])->name('tickets.new')->protected(Roles::ROLE[0]);
        Route::get('resources/?{user_id}?/tickets', [TicketsController::class, 'index'])->name('tickets.index')->where(['user_id' => 'num']);
        Route::get('resources/tickets/{id}/read', [TicketsController::class, 'read'])->name('tickets.read')->where(['id' => 'num']);

        Route::get('account/messages', [MessagesController::class, 'index'])->name('messages.index');
        Route::get('account/notifications', [NotificationsController::class, 'index'])->name('notifications.index');
        Route::get('account/{id}/settings', [SettingsController::class, 'index'])->name('settings.index')->where(['id' => 'num']);
        Route::get('account/activities', [ActivitiesController::class, 'index'])->name('activities.index');
        
        Route::get('resources/invoices', [InvoicesController::class, 'index'])->name('invoices.index');
        Route::get('resources/invoices/new', [InvoicesController::class, 'new'])->name('invoices.new')->protected(Roles::ROLE[2]);
        Route::get('resources/invoices/{id}/edit', [InvoicesController::class, 'edit'])->name('invoices.edit')->where(['id' => 'num'])->protected(Roles::ROLE[2]);
        Route::get('resources/invoices/{id}/read', [InvoicesController::class, 'read'])->name('invoices.read')->where(['id' => 'num'])->protected(Roles::ROLE[2]);
    
        Route::get('account/wallet', [WalletController::class, 'index'])->name('wallet.index')->protected(Roles::ROLE[1], Roles::ROLE[2]);
    });

    Route::groupMiddlewares(['remember', 'auth', 'csrf', 'sanitize'], function () {
        Route::delete('resources/users/?{id}?/delete', [UsersController::class, 'delete'])->name('users.delete')->where(['id' => 'num'])->protected(Roles::ROLE[2]);
        Route::patch('resources/users/?{id}?/update', [UsersController::class, 'update'])->name('users.update')->where(['id' => 'num'])->protected(Roles::ROLE[2]);
        Route::post('resources/users/create', [UsersController::class, 'create'])->name('users.create')->protected(Roles::ROLE[2]);
        Route::post('resources/users/export', [UsersController::class, 'export'])->name('users.export')->protected(Roles::ROLE[2]);

        Route::delete('resources/medias/?{id}?/delete', [MediasController::class, 'delete'])->name('medias.delete')->where(['id' => 'num']);
        Route::patch('resources/medias/?{id}?/update', [MediasController::class, 'update'])->name('medias.update')->where(['id' => 'num']);
        Route::post('resources/medias/create', [MediasController::class, 'create'])->name('medias.create');

        Route::delete('resources/tickets/?{id}?/delete', [TicketsController::class, 'delete'])->name('tickets.delete')->where(['id' => 'num']);
        Route::patch('resources/tickets/?{id}?/{status}/update', [TicketsController::class, 'update'])->name('tickets.update')->where(['id' => 'num', 'status' => 'num']);
        Route::post('resources/tickets/create', [TicketsController::class, 'create'])->name('tickets.create')->protected(Roles::ROLE[0]);
        Route::post('resources/tickets/messages/create', [TicketsController::class, 'createMessage'])->name('tickets.messages.create');
        
        Route::delete('account/messages/?{id}?/delete', [MessagesController::class, 'delete'])->name('messages.delete')->where(['id' => 'num']);
        Route::patch('account/messages/?{id}?/update', [MessagesController::class, 'update'])->name('messages.update')->where(['id' => 'num']);
        Route::post('account/messages/create', [MessagesController::class, 'create'])->name('messages.create');
        Route::post('account/messages/{id}/reply', [MessagesController::class, 'reply'])->name('messages.reply')->where(['id' => 'num']);
    
        Route::delete('account/notifications/?{id}?/delete', [NotificationsController::class, 'delete'])->name('notifications.delete')->where(['id' => 'num']);
        Route::patch('account/notifications/?{id}?/update', [NotificationsController::class, 'update'])->name('notifications.update')->where(['id' => 'num']);
        Route::post('account/notifications/create', [NotificationsController::class, 'create'])->name('notifications.create');
    
        Route::patch('account/settings/{id}/update', [SettingsController::class, 'update'])->name('settings.update')->where(['id' => 'num']);
        
        Route::delete('account/activities/?{id}?/delete', [ActivitiesController::class, 'delete'])->name('activities.delete')->where(['id' => 'num']);
        Route::post('account/activities/export', [ActivitiesController::class, 'create'])->name('activities.export');

        Route::delete('resources/invoices/?{id}?/delete', [InvoicesController::class, 'delete'])->name('invoices.delete')->where(['id' => 'num'])->protected(Roles::ROLE[0]);
        Route::patch('resources/invoices/?{id}?/update', [InvoicesController::class, 'update'])->name('invoices.update')->where(['id' => 'num'])->protected(Roles::ROLE[0]);
        Route::post('resources/invoices/create', [InvoicesController::class, 'create'])->name('invoices.create')->protected(Roles::ROLE[0]);
        Route::post('resources/invoices/export', [InvoicesController::class, 'export'])->name('invoices.export')->protected(Roles::ROLE[2]);
        Route::post('resources/invoices/{id}/download', [InvoicesController::class, 'download'])->name('invoices.download')->where(['id' => 'num'])->protected(Roles::ROLE[2]);
    });
})->register();
