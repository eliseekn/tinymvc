<?php

namespace App\Database\Repositories;

use App\Helpers\Auth;
use Framework\Http\Request;
use Framework\Database\Repository;

class Messages extends Repository
{
    /**
     * name of table
     *
     * @var string
     */
    public $table = 'messages';

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct($this->table);
    }

    /**
     * retrieves all messages
     *
     * @param  int $items_per_pages
     * @return \Framework\Support\Pager
     */
    public function findAllPaginate(int $items_per_pages = 20): \Framework\Support\Pager
    {
        return $this->select(['messages.*', 'u1.email AS sender_mail', 'u2.email AS recipient_email'])
            ->join('users AS u1', 'messages.sender', '=', 'u1.id')
            ->join('users AS u2', 'messages.recipient', '=', 'u2.id')
            ->whereRaw('recipient = ' . Auth::get('id') . ' OR sender = ' . Auth::get('id'))
            ->raw('AND (sender_deleted = (CASE WHEN u1.id = ' . Auth::get('id') . ' THEN 0 ELSE 1 END) OR recipient_deleted = (CASE WHEN u2.id = ' . Auth::get('id') . ' THEN 0 ELSE 1 END))')
            ->oldest('messages.created_at')
            ->paginate($items_per_pages);
    }
    
    /**
     * retrieves received messages only
     *
     * @param  int $limit
     * @return array
     */
    public function findReceivedMessages(int $limit = 5): array
    {
        return $this->select(['messages.*', 'u.email AS sender_mail', 'u.name AS sender_name'])
            ->join('users As u', 'messages.sender', '=', 'u.id')
            ->where('recipient', Auth::get('id'))
            ->and('recipient_deleted', 0)
            ->and('recipient_status', 'unread')
            ->oldest('messages.created_at')
            ->take($limit);
    }
    
    /**
     * retrieves unread messages count
     *
     * @return int
     */
    public function unreadCount(): int
    {
        return $this->count()
            ->where('recipient', Auth::get('id'))
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
    public function store(Request $request): int
    {
        return $this->insert([
            'sender' => Auth::get('id'),    
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
    public function updateReadStatus(Request $request, ?int $id = null): void
    {
        if (!is_null($id)) {
            if ($this->findSingle($id)->sender === Auth::get('id')) {
                $data = 'sender_status';
            } else if ($this->findSingle($id)->recipient === Auth::get('id')) {
                $data = 'recipient_status';
            }

            $this->updateIfExists($id, [$data => 'read']);
        } else {
			foreach (explode(',', $request->items) as $id) {
				if ($this->findSingle($id)->sender === Auth::get('id')) {
                    $data = 'sender_status';
                } else if ($this->findSingle($id)->recipient === Auth::get('id')) {
                    $data = 'recipient_status';
                }
    
                $this->updateIfExists($id, [$data => 'read']);
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
    public function updateDeletedStatus(Request $request, ?int $id = null): void
    {
        if (!is_null($id)) {
            if ($this->findSingle($id)->sender === Auth::get('id')) {
                $data = 'sender_status';
            } else if ($this->findSingle($id)->recipient === Auth::get('id')) {
                $data = 'recipient_status';
            }

            $this->updateIfExists($id, [$data => 1]);
        } else {
			foreach (explode(',', $request->items) as $id) {
				if ($this->findSingle($id)->sender === Auth::get('id')) {
                    $data = 'sender_status';
                } else if ($this->findSingle($id)->recipient === Auth::get('id')) {
                    $data = 'recipient_status';
                }
    
                $this->updateIfExists($id, [$data => 1]);
			}
        }
    }
    
    /**
     * retrieves data from date range
     *
     * @param  mixed $start
     * @param  mixed $end
     * @return array
     */
    public function findAllDateRange($date_start, $date_end): array
    {
        return $this->select()
            ->subQuery(function($query) use ($date_start, $date_end) {
                if (!empty($date_start) && !empty($date_end)) {
                    $query->whereBetween('created_at', $date_start, $date_end);
                }
            })
            ->oldest()
            ->all();
    }
}
