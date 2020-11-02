<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

use Carbon\Carbon;
use Framework\HTTP\Response;
use Framework\Routing\Route;
use App\Database\Models\NotificationsModel;

/**
 * Set api routes
 */

//get notifications list
Route::get('/api/notifications', [
    'handler' => function() {
        $notifications = NotificationsModel::find('status', 'unread')->firstOf(5);

        foreach ($notifications as $notification) {
            $notification->created_at = time_elapsed(Carbon::parse($notification->created_at, user_session()->timezone)->locale(user_session()->lang), 1);
        }

        Response::sendJson([
            'items' => $notifications,
            'title' => __('notifications'),
            'view_all' => __('view_all')
        ], [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => 'X-Requested-With',
        ]);
    }
]);
