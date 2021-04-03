<?php

namespace App\Database\Models;

use App\Helpers\Auth;
use Framework\Database\Model;
use Framework\Http\Request;

class Messages
{
    /**
     * name of table
     *
     * @var string
     */
    public static $table = 'messages AS m';

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
     * @param  int $items_per_pages
     * @return \Framework\Support\Pager
     */
    public static function paginate(int $items_per_pages = 20): \Framework\Support\Pager
    {
        return self::model()
            ->select(['m.*', 'u1.email AS sender_email', 'u2.email AS recipient_email'])
            ->join('users AS u1', 'm.sender', '=', 'u1.id')
            ->join('users AS u2', 'm.recipient', '=', 'u2.id')
            ->whereRaw('recipient = ' . Auth::get()->id . ' OR sender = ' . Auth::get()->id)
            ->raw('AND (sender_deleted = (CASE WHEN u1.id = ' . Auth::get()->id . ' THEN 0 ELSE 1 END) OR recipient_deleted = (CASE WHEN u2.id = ' . Auth::get()->id . ' THEN 0 ELSE 1 END))')
            ->oldest('m.created_at')
            ->paginate($items_per_pages);
    }
    
    /**
     * retrieves received messages only
     *
     * @param  int $limit
     * @return array
     */
    public static function findReceivedMessages(int $limit = 5): array
    {
        return self::model()
            ->select(['m.*', 'u.email AS sender_email', 'u.name AS sender_name'])
            ->join('users As u', 'm.sender', '=', 'u.id')
            ->where('recipient', Auth::get()->id)
            ->and('recipient_deleted', 0)
            ->and('recipient_status', 'unread')
            ->oldest('m.created_at')
            ->take($limit);
    }
    
    /**
     * retrieves unread messages count
     *
     * @return int
     */
    public static function unreadCount(): int
    {
        return self::model()
            ->count()
            ->where('recipient', Auth::get()->id)
            ->and('recipient_status', 'unread')
            ->single()
            ->value;
    }
    
    /**
     * store message
     *
     * @param  \Framework\Http\Request $request
     * @return int
     */
    public static function store(Request $request): int
    {
        return self::model()
            ->insert([
                'sender' => Auth::get()->id,    
                'recipient' => $request->recipient,
                'message' => $request->message
            ]);
    }
    
    /**
     * update read message status
     *
     * @param  \Framework\Http\Request $request
     * @param  int|null $id
     * @return void
     */
    public static function updateReadStatus(Request $request, ?int $id = null): void
    {
        if (!is_null($id)) {
            if (self::model()->findSingle($id)->sender === Auth::get()->id) {
                $data = 'sender_status';
            } else if (self::model()->findSingle($id)->recipient === Auth::get()->id) {
                $data = 'recipient_status';
            }

            self::model()->updateIfExists($id, [$data => 'read']);
        } else {
			foreach (explode(',', $request->items) as $id) {
				if (self::model()->findSingle($id)->sender === Auth::get()->id) {
                    $data = 'sender_status';
                } else if (self::model()->findSingle($id)->recipient === Auth::get()->id) {
                    $data = 'recipient_status';
                }
    
                self::model()->updateIfExists($id, [$data => 'read']);
			}
        }
    }
    
    /**
     * update deleted message status
     *
     * @param  \Framework\Http\Request $request
     * @param  int $id
     * @return void
     */
    public static function updateDeletedStatus(Request $request, ?int $id = null): void
    {
        if (!is_null($id)) {
            if (self::model()->findSingle($id)->sender === Auth::get()->id) {
                $data = 'sender_status';
            } else if (self::model()->findSingle($id)->recipient === Auth::get()->id) {
                $data = 'recipient_status';
            }

            self::model()->updateIfExists($id, [$data => 1]);
        } else {
			foreach (explode(',', $request->items) as $id) {
				if (self::model()->findSingle($id)->sender === Auth::get()->id) {
                    $data = 'sender_status';
                } else if (self::model()->findSingle($id)->recipient === Auth::get()->id) {
                    $data = 'recipient_status';
                }
    
                self::model()->updateIfExists($id, [$data => 1]);
			}
        }
    }
}
