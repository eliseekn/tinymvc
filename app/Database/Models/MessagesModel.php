<?php

namespace App\Database\Models;

use Framework\ORM\Model;
use App\Helpers\AuthHelper;

class MessagesModel extends Model
{
    /**
     * name of table
     *
     * @var string
     */
    public static $table = 'messages';

    /**
     * retrieves all messages
     *
     * @return \Framework\ORM\Model
     */
    public static function get(): \Framework\ORM\Model
    {
        return self::select(['messages.*', 'u1.email AS sender_email', 'u2.email AS recipient_email'])
            ->join('users AS u1', 'messages.sender', 'u1.id')
            ->join('users AS u2', 'messages.recipient', 'u2.id')
            ->where('messages.recipient', AuthHelper::user()->id)
            ->orWhere('messages.sender', AuthHelper::user()->id)
            ->orderDesc('messages.created_at');
    }
        
    /**
     * retrieves recipients messages only
     *
     * @return \Framework\ORM\Model
     */
    public static function recipients(): \Framework\ORM\Model
    {
        return self::select(['messages.*', 'users.email AS sender_email', 'users.name AS sender_name'])
            ->join('users', 'messages.sender', 'users.id')
            ->where('messages.recipient', AuthHelper::user()->id)
            ->andWhere('messages.recipient_status', 'unread')
            ->orderDesc('messages.created_at');
    }
}
