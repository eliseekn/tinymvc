<?php

namespace App\Database\Repositories;

use App\Helpers\Auth;
use Framework\Http\Request;
use Framework\Database\Repository;

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
        return $this->select(['tickets.*'])
            ->subQuery(function ($query) use ($user_id) {
                if (!is_null($user_id)) {
                    $query->join('users', 'tickets.user_id', '=', 'users.id')
                        ->where('user_id', $user_id);
                }
            })
            ->latest('tickets.created_at')
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
            'ticket_id' => strtoupper(random_string(8, true)),
            'object' => $request->object,
            'priority' => $request->priority
        ]);
    }

    /**
     * delete tickets
     *
     * @param  \Framework\Http\Request $request
     * @param  int $id
     * @return bool
     */
    public function flush(Request $request, ?int $id = null): bool
    {
        return is_null($id) 
            ? $this->deleteBy('id', 'in', explode(',', $request->items))
            : $this->deleteIfExists($id);
    }
    
    /**
     * retrieves open tickets count
     *
     * @param  int|null $user_id
     * @return int
     */
    public function openCount(?int $user_id = null): int
    {
        return $this->count()
            ->subQuery(function ($query) use ($user_id) {
                if (!is_null($user_id)) {
                    $query->join('users', 'tickets.user_id', '=', 'users.id')
                        ->where('user_id', $user_id)
                        ->where('status', 1);
                }

                else {
                    $query->where('status', 1);
                }
            })
            ->single()
            ->value;
    }
}
