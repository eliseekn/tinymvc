<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use App\Helpers\Auth;
use Framework\Routing\Route;
use Framework\Support\Metrics;
use App\Database\Repositories\Roles;
use App\Database\Repositories\Users;
use App\Database\Repositories\Invoices;
use App\Database\Repositories\Messages;
use App\Database\Repositories\Notifications;

/**
 * API routes
 */

Route::groupPrefix('api', function () {
    Route::groupPrefix('metrics', function () {
        Route::get('users/{period:str}/?{interval:num}?', 
            function (string $period, int $interval = 0) {
                $metrics = (new Users())->metrics('id', Metrics::COUNT, $period, $interval, ['AND role = ?', [Roles::ROLE[1]]]);
                response()->json(['metrics' => json_encode($metrics)]);
        });

        Route::get('invoices/{period:str}/?{interval:num}?', 
            function (string $period, int $interval = 0) {
                $metrics = (new Invoices())->metrics('total_price', Metrics::SUM, $period, $interval);
                response()->json(['metrics' => json_encode($metrics)]);
        });
    });

    Route::get('notifications', function() {
        $notifications = (new Notifications())->findMessages();

        foreach ($notifications as $notification) {
            $notification->created_at = time_elapsed($notification->created_at);
        }

        response()->json(['notifications' => $notifications]);
    });

    Route::get('messages', function () {
        $messages = (new Messages())->findReceivedMessages();

        foreach ($messages as $message) {
            $message->created_at = time_elapsed($message->created_at);
        }

        response()->json(['messages' => $messages]);
    });

    Route::get('users', function () {
        $users = (new Users())->select(['id', 'email'])
            ->where('id', '!=', Auth::get('id'))
            ->and('company', Auth::get('company'))
            ->all();

        response()->json(['users' => $users]);
    });

    Route::get('translations', function() {
        require_once 'resources/lang/' . Auth::get('lang') . '.php';
        response()->json(['translations' => $config]);
    });
})->register();
