<?php

namespace App\Helpers;

use App\Database\Repositories\Users;
use App\Database\Repositories\Notifications;

class NotificationHelper
{    
    /**
     * create notification message
     *
     * @param  string $message
     * @param  int|null $recipient
     * @return void
     */
    public static function create(string $message, ?int $recipient = null): void
    {
        if (is_null($recipient)) {
            $users = (new Users())->selectAll(['id']);

            foreach ($users as $user) {
                (new Notifications())->insert([
                    'message' => $message,
                    'user_id' => $user->id
                ]);
            }
        } 
        
        else {
            (new Notifications())->insert([
                'message' => $message,
                'user_id' => $recipient
            ]);
        }
    }
}