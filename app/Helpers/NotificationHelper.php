<?php

namespace App\Helpers;

use App\Database\Models\UsersModel;
use App\Database\Models\NotificationsModel;

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
            foreach (UsersModel::selectAll() as $user) {
                NotificationsModel::insert([
                    'message' => $message,
                    'user_id' => $user->id
                ]);
            }
        } 
        
        else {
            NotificationsModel::insert([
                'message' => $message,
                'user_id' => $recipient
            ]);
        }
    }
}