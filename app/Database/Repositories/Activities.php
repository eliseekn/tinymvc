<?php

namespace App\Database\Repositories;

use App\Helpers\Auth;
use Framework\Http\Request;
use Framework\Database\Repository;

class Activities extends Repository
{
    /**
     * name of table
     *
     * @var string
     */
    public $table = 'activities';

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
     * retrieves all activites
     * 
     * @param  int $items_per_pages
     * @return \Framework\Support\Pager
     */
    public function findAllPaginate($items_per_pages = 20): \Framework\Support\Pager
    {
        return $this->select(['id', 'user', 'url', 'ip_address', 'action', 'created_at'])
            ->subQuery(function ($query) {
                if (Auth::get('role') !== Roles::ROLE[0]) {
                    $query->where('user', Auth::get('email'));
                }
            })
            ->oldest()
            ->paginate($items_per_pages);
    }
    
    /**
     * delete activities by id
     *
     * @param  \Framework\Http\Request $request
     * @return bool
     */
    public function deleteById(Request $request): bool
    {
        return $this->deleteBy('id', 'in', explode(',', $request->items));
    }
    
    /**
     * store
     *
     * @param  mixed $user
     * @param  string $action
     * @param  \Framework\Http\Request $request $request
     * @return int
     */
    public function store($user, string $action, Request $request): int
    {
        return $this->insert([
            'user' => is_null($user) ? Auth::get('email') : $user,
            'url' => $request->fullUri(),
            'method' => $request->method(),
            'ip_address' => $request->remoteIP(),
            'action' => $action
        ]);
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
                    $query->whereBetween($date_start, $date_end);
                }
            })
            ->oldest()
            ->all();
    }
}
