<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use App\Helpers\Auth;
use Framework\Http\Response;
use Framework\Routing\Route;
use Framework\Database\Model;
use Framework\Support\Metrics;
use App\Database\Models\MessagesModel;
use App\Database\Models\NotificationsModel;

/**
 * API routes
 */

//send basic headers
Response::headers([
    'Access-Control-Allow-Origin' => '*',
    'Access-Control-Allow-Headers' => '*',
]);

Route::group([
    //retrieves notifications list
    'notifications' => [
        'method' => 'get',
        'handler' => function() {
            $notifications = NotificationsModel::findMessages();

            foreach ($notifications as $notification) {
                $notification->created_at = date_helper($notification->created_at)->time_elapsed();
            }

            Response::send(['notifications' => $notifications], true);
        }
    ],

    //retrieves metrics trends
    'metrics/users/{str}/?{num}?' => [
        'method' => 'get',
        'handler' => function (string $trends, int $interval = 0) {
            $metrics = (new Model('users'))->metrics('id', Metrics::COUNT, $trends, $interval);
            Response::send(['metrics' => json_encode($metrics)], true);
        }
    ],

    //retrieves messages list
    'messages' => [
        'method' => 'get',
        'handler' => function () {
            $messages = MessagesModel::findRecipients();

            foreach ($messages as $message) {
                $message->created_at = date_helper($message->created_at)->time_elapsed();
            }

            Response::send(['messages' => $messages], true);
        }
    ],

    //retrieves users list
    'users' => [
        'method' => 'get',
        'handler' => function () {
            Response::send(['users' => (new Model('users'))->find('!=', Auth::get()->id)->all()], true);
        }
    ],

    //retrieves translations
    'translations' => [
        'method' => 'get',
        'handler' => function() {
            require_once 'resources/lang/' . Auth::get()->lang . '.php';
            Response::send(['translations' => $config], true);
        }
    ]
])->by([
    'prefix' => 'api'
]);