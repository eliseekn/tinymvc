<?php

namespace App\Helpers;

use Framework\Database\Model;

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
            $users = (new Model('users'))->selectAll();

            foreach ($users as $user) {
                (new Model('notifications'))->insert([
                    'message' => $message,
                    'user_id' => $user->id
                ]);
            }
        } 
        
        else {
            (new Model('notifications'))->insert([
                'message' => $message,
                'user_id' => $recipient
            ]);
        }
    }
}