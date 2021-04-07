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
use App\Database\Models\Messages;
use App\Database\Models\Notifications;

/**
 * API routes
 */

 Route::groupPrefix('api', function () {
    Route::groupMiddlewares(['cors'], function () {
        Route::get('notifications', function() {
            $notifications = Notifications::findMessages();
    
            foreach ($notifications as $notification) {
                $notification->created_at = time_elapsed($notification->created_at);
            }
    
            (new Response())->send(['notifications' => $notifications], true);
        });
    
        Route::get('metrics/users/{trends}/?{interval}?', function (string $trends, int $interval = 0) {
            $metrics = (new Model('users'))->metrics('id', Metrics::COUNT, $trends, $interval);
            (new Response())->send(['metrics' => json_encode($metrics)], true);
        })->where(['trends' => 'str','interval' => 'num']);
    
        Route::get('messages', function () {
            $messages = Messages::findReceivedMessages();
    
            foreach ($messages as $message) {
                $message->created_at = time_elapsed($message->created_at);
            }
    
            (new Response())->send(['messages' => $messages], true);
        });
    
        Route::get('users', function () {
            (new Response())->send(['users' => (new Model('users'))->find('!=', Auth::get()->id)->all()], true);
        });
    
        Route::get('translations', function() {
            require_once 'resources/lang/' . Auth::get()->lang . '.php';
            (new Response())->send(['translations' => $config], true);
        });
    });
 })->register();
