<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
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

//retrieves notifications list
Route::get('api/notifications', [
    'handler' => function() {
        $notifications = NotificationsModel::messages()->take(5);

        foreach ($notifications as $notification) {
            $notification->created_at = time_elapsed(DateHelper::format($notification->created_at)->timestamp(), 1);
        }

        Response::send(['notifications' => $notifications], true);
    }
]);

//retrieves metrics trends
Route::get('api/metrics/users/{str}/?{num}?', [
    'handler' => function (string $trends, int $interval = 0) {
        $metrics = UsersModel::metrics('id', Metrics::COUNT, $trends, $interval);
        Response::send(['metrics' => json_encode($metrics)], true);
    }
]);

//retrieves messages list
Route::get('api/messages', [
    'handler' => function () {
        $messages = MessagesModel::recipients()->take(5);

        foreach ($messages as $message) {
            $message->created_at = time_elapsed(DateHelper::format($message->created_at)->timestamp(), 1);
        }

        Response::send(['messages' => $messages], true);
    }
]);

//retrieves users list
Route::get('api/users', [
    'handler' => function () {
        Response::send(['users' => UsersModel::find('!=', Auth::get()->id)->all()], true);
    }
]);

//retrieves translations
Route::get('api/translations', [
    'handler' => function() {
        require_once 'resources/lang/' . Auth::get()->lang . '.php';

        Response::send(['translations' => $config], true);
    }
]);
