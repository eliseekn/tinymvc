<?php

namespace App\Database\Repositories;

use App\Helpers\Auth;
use Framework\Database\Repository;

class Notifications extends Repository
{    
    /**
     * name of table
     *
     * @var string
     */
    public $table = 'notifications';
    
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
     * get notifications messages
     *
     * @param  int $limit
     * @return array
     */
    public function findMessages(int $limit = 5)
    {
        return $this->findBy('status', 0)
            ->and('user_id', Auth::get('id'))
            ->oldest()
            ->take($limit);
    }

    /**
     * retrieves all notifications messages
     *
     * @param  int $items_per_pages
     * @return \Framework\Support\Pager
     */
    public function findAllPaginate(int $items_per_pages = 10): \Framework\Support\Pager
    {
        return $this->findBy('user_id', Auth::get('id'))
            ->oldest()
            ->paginate($items_per_pages);
    }
    
    /**
     * retrieves unread notifications messages count
     *
     * @return int
     */
    public function unreadCount(): int
    {
        return $this->count()
            ->where('status', 0)
            ->and('user_id', Auth::get('id'))
            ->single()
            ->value;
    }
}
