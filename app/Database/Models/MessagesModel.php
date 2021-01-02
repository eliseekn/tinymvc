<?php

namespace App\Database\Models;

use Framework\Database\Model;
use App\Helpers\Auth;

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
     * @return \Framework\Database\Model
     */
    public static function get(): \Framework\Database\Model
    {
        return self::select(['messages.*', 'u1.email AS sender_email', 'u2.email AS recipient_email'])
            ->join('users AS u1', 'messages.sender', '=', 'u1.id')
            ->join('users AS u2', 'messages.recipient', '=', 'u2.id')
            ->where('messages.recipient', Auth::get()->id)
            ->or('messages.sender', Auth::get()->id)
            ->orderDesc('messages.created_at');
    }
        
    /**
     * retrieves recipients messages only
     *
     * @return \Framework\Database\Model
     */
    public static function recipients(): \Framework\Database\Model
    {
        return self::select(['messages.*', 'users.email AS sender_email', 'users.name AS sender_name'])
            ->join('users', 'messages.sender', '=', 'users.id')
            ->where('messages.recipient', Auth::get()->id)
            ->and('messages.recipient_status', 'unread')
            ->orderDesc('messages.created_at');
    }
}
