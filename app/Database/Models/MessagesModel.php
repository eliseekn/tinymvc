<?php

namespace App\Database\Models;

use App\Helpers\Auth;
use Framework\Database\Model;

class MessagesModel
{
    /**
     * name of table
     *
     * @var string
     */
    public static $table = 'messages';

    /**
     * create new model instance 
     *
     * @return \Framework\Database\Model
     */
    private static function model(): \Framework\Database\Model
    {
        return new Model(self::$table);
    }

    /**
     * retrieves all messages
     *
     * @param  int $items_per_page
     * @return \Framework\Support\Pager
     */
    public static function findMessages(int $items_per_page = 20): \Framework\Support\Pager
    {
        return self::model()
            ->select(['messages.*', 'u1.email AS sender_email', 'u2.email AS recipient_email'])
            ->join('users AS u1', 'messages.sender', '=', 'u1.id')
            ->join('users AS u2', 'messages.recipient', '=', 'u2.id')
            ->where('recipient_deleted', 0)
            ->and('recipient', Auth::get()->id)
            ->or('sender', Auth::get()->id)
            ->oldest('messages.created_at')
            ->paginate($items_per_page);
    }
    
    /**
     * retrieves recipients messages only
     *
     * @param  int $limit
     * @return array
     */
    public static function findRecipients(int $limit = 5): array
    {
        return self::model()
            ->select(['messages.*', 'users.email AS sender_email', 'users.name AS sender_name'])
            ->join('users', 'messages.sender', '=', 'users.id')
            ->where('recipient', Auth::get()->id)
            ->and('recipient_status', 'unread')
            ->oldest('messages.created_at')
            ->take($limit);
    }
}
