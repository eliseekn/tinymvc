<?php

namespace App\Database\Repositories;

use Carbon\Carbon;
use App\Helpers\Auth;
use Framework\Http\Request;
use Framework\Database\Repository;

class Invoices extends Repository
{
    /**
     * name of table
     *
     * @var string
     */
    public $table = 'invoices';

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
     * retrieves all invoices by role
     *
     * @param  int|null $user_id
     * @param  int $items_per_pages
     * @return \Framework\Support\Pager
     */
    public function findAllByUserPaginate(?int $user_id = null, int $items_per_pages = 10): \Framework\Support\Pager
    {
        return $this->select(['invoices.*', 'users.company AS company'])
            ->join('users', 'invoices.user_id', '=', 'users.id')
            ->subQuery(function ($query) use ($user_id) {
                if (!is_null($user_id)) {
                    $query->where('user_id', $user_id);
                }
            })
            ->latest('invoices.created_at')
            ->paginate($items_per_pages);
    }
    
    /**
     * retrieves invoices count
     *
     * @param  string $status
     * @param  int|null $user_id
     * @return int
     */
    public function invoicesCount(string $status, ?int $user_id = null): int
    {
        return $this->count()
            ->subQuery(function ($query) use ($user_id, $status) {
                if (!is_null($user_id)) {
                    $query->join('users', 'invoices.user_id', '=', 'users.id')
                        ->where('user_id', $user_id)
                        ->where('invoices.status', $status);
                }

                else {
                    $query->where('invoices.status', $status);
                }
            })
            ->single()
            ->value;
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
            'user_id' => $request->company,
            'invoice_id' => Carbon::now()->format('Y/m/d/') . strtoupper(random_string(5)),
            'currency' => $request->currency,
            'tax' => $request->inputs('tax', 0),
            'products' => $request->products
        ]);
    }
    
    /**
     * refresh
     *
     * @param  \Framework\Http\Request $request
     * @param  int $id
     * @return bool
     */
    public function refresh(Request $request, int $id): bool
    {
        return $this->updateIfExists($id, [
            'user_id' => $request->company,
            'currency' => $request->currency,
            'tax' => $request->inputs('tax', 0),
            'products' => $request->products
        ]);
    }
    
    /**
     * findSingleByCompany
     *
     * @param  int $id
     * @return mixed
     */
    public function findSingleByCompany(int $id)
    {
        return $this->select(['invoices.*', 'users.company AS company'])
            ->join('users', 'invoices.user_id', '=', 'users.id')
            ->where('invoices.id', $id)
            ->single();
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
            ->where('id', '!=', Auth::get('id'))
            ->subQuery(function($query) use ($date_start, $date_end) {
                if (!empty($date_start) && !empty($date_end)) {
                    $query->whereBetween('created_at', $date_start, $date_end);
                }
            })
            ->oldest()
            ->all();
    }
}
