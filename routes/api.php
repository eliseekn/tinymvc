<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use App\Helpers\Auth;
use Framework\Routing\Route;
use Framework\Database\Model;
use Framework\Support\Metrics;
use App\Database\Repositories\Users;
use App\Database\Repositories\Messages;
use App\Database\Repositories\Notifications;

/**
 * API routes
 */

Route::group(['prefix' => 'api', 'middlewares' => ['cors']], function () {
    Route::get('notifications', function() {
        $notifications = (new Notifications())->findMessages();

        foreach ($notifications as $notification) {
            $notification->created_at = time_elapsed($notification->created_at);
        }

        response()->json(['notifications' => $notifications]);
    });

    Route::get('metrics/users/{trends}/?{interval}?', function (string $trends, int $interval = 0) {
        $metrics = (new Users())->metrics('id', Metrics::COUNT, $trends, $interval);
        response()->json(['metrics' => json_encode($metrics)]);
    })->where(['trends' => 'str','interval' => 'num']);

    Route::get('messages', function () {
        $messages = (new Messages())->findReceivedMessages();

        foreach ($messages as $message) {
            $message->created_at = time_elapsed($message->created_at);
        }

        response()->json(['messages' => $messages]);
    });

    Route::get('users', function () {
        response()->json(['users' => (new Users())->find('!=', Auth::get('id'))->all()]);
    });

    Route::get('translations', function() {
        require_once 'resources/lang/' . Auth::get('lang') . '.php';
        response()->json(['translations' => $config]);
    });
})->register();
