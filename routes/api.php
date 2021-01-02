<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use App\Helpers\Auth;
use App\Helpers\DateHelper;
use Framework\Http\Response;
use Framework\Routing\Route;
use Framework\Support\Metrics;
use App\Database\Models\UsersModel;
use App\Database\Models\MessagesModel;
use App\Database\Models\NotificationsModel;

/**
 * API routes
 */

//send basic headers
Response::headers([
    'Access-Control-Allow-Origin' => '*',
    'Access-Control-Allow-Headers' => 'X-Requested-With',
]);

//get notifications list
Route::get('api/notifications', [
    'handler' => function() {
        $notifications = NotificationsModel::get()->take(5);

        foreach ($notifications as $notification) {
            $notification->created_at = time_elapsed(DateHelper::format($notification->created_at)->timestamp(), 1);
        }

        Response::json(['notifications' => $notifications]);
    }
]);

//get metrics trends
Route::get('api/metrics/users/{str}/?{num}?', [
    'handler' => function (string $trends, int $interval = 0) {
        $metrics = UsersModel::metrics('id', Metrics::COUNT, $trends, $interval);
        Response::json(['metrics' => json_encode($metrics)]);
    }
]);

//get messages list
Route::get('api/messages', [
    'handler' => function () {
        $messages = MessagesModel::recipients()->take(5);

        foreach ($messages as $message) {
            $message->created_at = time_elapsed(DateHelper::format($message->created_at)->timestamp(), 1);
        }

        Response::json(['messages' => $messages]);
    }
]);

//get users list
Route::get('api/users', [
    'handler' => function () {
        Response::json(['users' => UsersModel::find('!=', Auth::get()->id)->all()]);
    }
]);

//get translations
Route::get('api/translations', [
    'handler' => function() {
        $lang = Auth::get()->lang;
        require 'resources/lang/' . $lang . '.php';

        Response::json(['translations' => $config]);
    }
]);
