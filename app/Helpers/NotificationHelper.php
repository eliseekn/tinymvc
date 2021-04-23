<?php

namespace App\Helpers;

use App\Mails\NotificationsMail;
use App\Database\Repositories\Users;
use App\Database\Repositories\Notifications;

class NotificationHelper
{    
    /**
     * create notification
     *
     * @param  string $message
     * @param  int|null $recipient
     * @return void
     */
    public static function create(string $message, $url, ?int $recipient = null): void
    {
        $users = is_null($recipient)
            ? (new Users())->selectAll(['id', 'email', 'email_notifications'])
            : (new Users())->select(['id', 'email', 'email_notifications'])->where('id', $recipient)->all(); 

        foreach ($users as $user) {
            self::send($message, $url, $user);
        }
    }
    
    /**
     * create and send notificaiton
     *
     * @param  string $message
     * @param  mixed $user
     * @return void
     */
    private static function send(string $message, $url, $user = null): void
    {
        (new Notifications())->insert([
            'message' => $message,
            'user_id' => $user->id
        ]);

        if ($user->email_notifications && $user->id != Auth::get('id')) {
            NotificationsMail::send($user->email, $message, $url);
        }

        (new Notifications())->updateBy(['user_id', Auth::get('id')], ['status' => 1]);
    }
}
