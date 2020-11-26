<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

use Carbon\Carbon;
use App\Helpers\AuthHelper;
use Framework\HTTP\Response;
use Framework\Routing\Route;
use Framework\Support\Metrics;
use App\Database\Models\UsersModel;
use App\Database\Models\MessagesModel;
use App\Database\Models\NotificationsModel;
use Framework\ORM\Builder;

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
        $notifications = NotificationsModel::find('status', 'unread')->orderDesc('created_at')->firstOf(5);

        foreach ($notifications as $notification) {
            $notification->created_at = time_elapsed(Carbon::parse($notification->created_at, user_session()->timezone)->locale(user_session()->lang), 1);
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
            $message->created_at = time_elapsed(Carbon::parse($message->created_at, user_session()->timezone)->locale(user_session()->lang), 1);
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
        Response::sendJson(['users' => UsersModel::find('id', '<>', AuthHelper::user()->id)->all()]);
    }
]);
