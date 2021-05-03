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

/**
 * Admin routes
 */

Route::groupPrefix('admin', function () {
    Route::groupMiddlewares(['remember', 'auth'], function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

        Route::get('resources/users', [UsersController::class, 'index'])->name('users.index');
        Route::get('resources/users/new', [UsersController::class, 'new'])->name('users.new')->locked(Roles::ROLE[2]);
        Route::get('resources/users/{id:num}/edit', [UsersController::class, 'edit'])->name('users.edit')->locked(Roles::ROLE[2]);
        Route::get('resources/users/{id:num}/read', [UsersController::class, 'read'])->name('users.read')->locked(Roles::ROLE[2]);

        Route::get('resources/medias', [MediasController::class, 'index'])->name('medias.index');
        Route::get('resources/medias/search', [MediasController::class, 'search'])->name('medias.search');
        Route::get('resources/medias/search?q=', [MediasController::class, 'search'])->name('medias.search.q');
        Route::get('resources/medias/new', [MediasController::class, 'new'])->name('medias.new');
        Route::get('resources/medias/{id:num}/edit', [MediasController::class, 'edit'])->name('medias.edit');
        Route::get('resources/medias/{id:num}/read', [MediasController::class, 'read'])->name('medias.read');
        Route::get('resources/medias/{id:num}/download', [MediasController::class, 'download'])->name('medias.download');

        Route::get('resources/tickets/new', [TicketsController::class, 'new'])->name('tickets.new')->locked(Roles::ROLE[0]);
        Route::get('resources/?{user_id:num}?/tickets', [TicketsController::class, 'index'])->name('tickets.index');
        Route::get('resources/tickets/{id:num}/read', [TicketsController::class, 'read'])->name('tickets.read');
        
        Route::get('resources/invoices', [InvoicesController::class, 'index'])->name('invoices.index');
        Route::get('resources/invoices/new', [InvoicesController::class, 'new'])->name('invoices.new')->locked(Roles::ROLE[2]);
        Route::get('resources/invoices/{id:num}/edit', [InvoicesController::class, 'edit'])->name('invoices.edit')->locked(Roles::ROLE[2]);
        Route::get('resources/invoices/{id:num}/read', [InvoicesController::class, 'read'])->name('invoices.read')->locked(Roles::ROLE[2]);

        Route::get('account/messages', [MessagesController::class, 'index'])->name('messages.index');
        Route::get('account/notifications', [NotificationsController::class, 'index'])->name('notifications.index');
        Route::get('account/{id:num}/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::get('account/activities', [ActivitiesController::class, 'index'])->name('activities.index');
    });

    Route::groupMiddlewares(['remember', 'auth', 'csrf', 'sanitize'], function () {
        Route::delete('resources/users/?{id:num}?/delete', [UsersController::class, 'delete'])->name('users.delete')->locked(Roles::ROLE[2]);
        Route::patch('resources/users/?{id:num}?/update', [UsersController::class, 'update'])->name('users.update')->locked(Roles::ROLE[2]);
        Route::post('resources/users/create', [UsersController::class, 'create'])->name('users.create')->locked(Roles::ROLE[2]);
        Route::post('resources/users/export', [UsersController::class, 'export'])->name('users.export')->locked(Roles::ROLE[2]);

        Route::delete('resources/medias/?{id:num}?/delete', [MediasController::class, 'delete'])->name('medias.delete');
        Route::patch('resources/medias/?{id:num}?/update', [MediasController::class, 'update'])->name('medias.update');
        Route::post('resources/medias/create', [MediasController::class, 'create'])->name('medias.create');

        Route::delete('resources/tickets/?{id:num}?/delete', [TicketsController::class, 'delete'])->name('tickets.delete');
        Route::patch('resources/tickets/?{id:num}?/{status:str}/update', [TicketsController::class, 'update'])->name('tickets.update');
        Route::post('resources/tickets/create', [TicketsController::class, 'create'])->name('tickets.create')->locked(Roles::ROLE[0]);
        Route::post('resources/tickets/messages/create', [TicketsController::class, 'createMessage'])->name('tickets.messages.create');

        Route::delete('resources/invoices/?{id:num}?/delete', [InvoicesController::class, 'delete'])->name('invoices.delete')->locked(Roles::ROLE[0]);
        Route::patch('resources/invoices/?{id:num}?/update', [InvoicesController::class, 'update'])->name('invoices.update')->locked(Roles::ROLE[0]);
        Route::post('resources/invoices/create', [InvoicesController::class, 'create'])->name('invoices.create')->locked(Roles::ROLE[0]);
        Route::post('resources/invoices/export', [InvoicesController::class, 'export'])->name('invoices.export')->locked(Roles::ROLE[2]);
        Route::post('resources/invoices/{id:num}/download', [InvoicesController::class, 'download'])->name('invoices.download')->locked(Roles::ROLE[2]);
        
        Route::delete('account/messages/?{id:num}?/delete', [MessagesController::class, 'delete'])->name('messages.delete');
        Route::patch('account/messages/?{id:num}?/update', [MessagesController::class, 'update'])->name('messages.update');
        Route::post('account/messages/create', [MessagesController::class, 'create'])->name('messages.create');
        Route::post('account/messages/{id:num}/reply', [MessagesController::class, 'reply'])->name('messages.reply');
    
        Route::delete('account/notifications/?{id:num}?/delete', [NotificationsController::class, 'delete'])->name('notifications.delete');
        Route::patch('account/notifications/?{id:num}?/update', [NotificationsController::class, 'update'])->name('notifications.update');
        Route::post('account/notifications/create', [NotificationsController::class, 'create'])->name('notifications.create');
    
        Route::patch('account/settings/{id:num}/update', [SettingsController::class, 'update'])->name('settings.update');
        
        Route::delete('account/activities/?{id:num}?/delete', [ActivitiesController::class, 'delete'])->name('activities.delete');
        Route::post('account/activities/export', [ActivitiesController::class, 'create'])->name('activities.export');
    });
})->register();
