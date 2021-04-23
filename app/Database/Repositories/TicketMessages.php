<?php

namespace App\Database\Repositories;

use App\Helpers\Auth;
use Framework\Http\Request;
use Framework\Database\Repository;

class TicketMessages extends Repository
{
    /**
     * name of table
     *
     * @var string
     */
    public $table = 'ticket_messages';

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
     * retrieves single data by value
     *
     * @param  int $ticket_id
     * @return \Framework\Support\Pager
     */
    public function findAllByTicketPaginate(int $ticket_id, int $items_per_pages = 10): \Framework\Support\Pager
    {
        return $this->select(['ticket_messages.*', 'users.email AS author'])
            ->join('users', 'ticket_messages.user_id', '=', 'users.id')
            ->where('ticket_id', $ticket_id)
            ->oldest()
            ->paginate($items_per_pages);
    }
    
    /**
     * store
     *
     * @param  \Framework\Http\Request $request $request
     * @return int
     */
    public function store(Request $request): int
    {
        return $this->insert([
            'ticket_id' => $request->ticket_id,
            'user_id' => Auth::get('id'),
            'message' => $request->message
        ]);
    }
}
