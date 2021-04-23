<?php

namespace App\Database\Repositories;

use App\Helpers\Auth;
use Framework\Database\Repository;
use Framework\Http\Request;

class Tickets extends Repository
{
    /**
     * name of table
     *
     * @var string
     */
    public $table = 'tickets';

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
     * retrieves all tickets by role
     *
     * @param  int|null $user_id
     * @param  int $items_per_pages
     * @return \Framework\Support\Pager
     */
    public function findAllByUserPaginate(?int $user_id = null, int $items_per_pages = 10): \Framework\Support\Pager
    {
        return $this->select()
            ->join('users', 'tickets.user_id', '=', 'users.id')
            ->subQuery(function ($query) use ($user_id) {
                if (!is_null($user_id)) {
                    $query->where('user_id', $user_id);
                }
            })
            ->oldest('tickets.created_at')
            ->paginate($items_per_pages);
    }
    
    /**
     * store
     *
     * @param  \Framework\Http\Request $request
     * @return int
     */
    public function store(Request $request): int
    {
        return $this->insert([
            'user_id' => Auth::get('id'),
            'ticket_id' => strtoupper(random_string(10, true)),
            'object' => $request->object,
            'priority' =>$request->priority
        ]);
    }
    
    /**
     * refresh
     *
     * @param  int $id
     * @param  int $status
     * @return bool
     */
    public function refresh(int $id, int $status): bool
    {
        return $this->updateIfExists($id, ['status' => $status]);
    }
}
