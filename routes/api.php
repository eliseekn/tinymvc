<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use App\Helpers\Auth;
use App\Helpers\DateHelper;
use Framework\HTTP\Response;
use Framework\Routing\Route;
use Framework\Support\Metrics;
use App\Database\Models\UsersModel;
use App\Database\Models\MessagesModel;
use App\Database\Models\NotificationsModel;

/**
 * API routes
 */

//send basic headers
Response::sendHeaders([
    'Access-Control-Allow-Origin' => '*',
    'Access-Control-Allow-Headers' => 'X-Requested-With',
]);

//get notifications list
Route::get('api/notifications', [
    'handler' => function() {
        $notifications = NotificationsModel::get()->firstOf(5);

        foreach ($notifications as $notification) {
            $notification->created_at = time_elapsed(DateHelper::format($notification->created_at), 1);
        }

        Response::sendJson([
            'notifications' => $notifications,
            'title' => __('notifications'),
            'view_all' => __('view_all')
        ]);
    }
]);

//get metrics trends
Route::get('api/metrics/users/{trends:str}', [
    'handler' => function (string $trends) {
        $metrics = UsersModel::metrics('id', Metrics::COUNT, $trends);
        Response::sendJson(['metrics' => json_encode($metrics)]);
    }
]);

//get messages list
Route::get('api/messages', [
    'handler' => function () {
        $messages = MessagesModel::recipients()->firstOf(5);

        foreach ($messages as $message) {
            $message->created_at = time_elapsed(DateHelper::format(($message->created_at), 1));
        }

        Response::sendJson([
            'messages' => $messages,
            'title' => __('messages'),
            'view_all' => __('view_all')
        ]);
    }
]);

//get users list
Route::get('api/users', [
    'handler' => function () {
        Response::sendJson(['users' => UsersModel::find('id', '!=', Auth::user()->id)->all()]);
    }
]);
