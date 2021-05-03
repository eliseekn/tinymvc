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
     * retrieves total incomes
     *
     * @param  string|null $status
     * @return mixed
     */
    public function findSumByStatus(?string $status = null)
    {
        return $this->sum('total_price')
            ->subQuery(function ($query) use ($status) {
                if (!is_null($status)) {
                    $query->where('status', 'paid');
                }
            })
            ->single()
            ->value;
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
     * findOneWithCompany
     *
     * @param  int $id
     * @return mixed
     */
    public function findOneWithCompany(int $id)
    {
        return $this->select(['invoices.*', 'users.company AS company'])
            ->join('users', 'invoices.user_id', '=', 'users.id')
            ->where('invoices.id', $id)
            ->single();
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
        $products = json_decode('[' . $request->products . ']');
        $tax = $request->inputs('tax', 0);
        $sub_total = 0;

        foreach ($products as $product) {
            $sub_total += $product->price  * $product->quantity;
        }

        return $this->insert([
            'user_id' => $request->company,
            'invoice_id' => Carbon::now()->format('Y/m/d/') . strtoupper(random_string(5)),
            'products' => $request->products,
            'tax' => $tax,
            'sub_total' => $sub_total,
            'total_price' => $tax !== 0 ? $sub_total + (($sub_total * $tax) / 100) : $sub_total,
            'expire' => $request->inputs('expire')
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
        $products = json_decode('[' . $request->products . ']');
        $tax = $request->inputs('tax', 0);
        $sub_total = 0;

        foreach ($products as $product) {
            $sub_total += $product->price  * $product->quantity;
        }

        return $this->updateIfExists($id, [
            'user_id' => $request->company,
            'tax' => $tax,
            'products' => $request->products,
            'sub_total' => $sub_total,
            'total_price' => $tax !== 0 ? $sub_total + (($sub_total * $tax) / 100) : $sub_total,
            'status' => $request->status,
            'expire' => $request->inputs('expire')
        ]);
    }

    /**
     * delete invoices
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
     * retrieves data from date range
     *
     * @param  mixed $start
     * @param  mixed $end
     * @return array
     */
    public function findAllDateRange($date_start, $date_end): array
    {
        return $this->select(['invoices.*', 'users.company AS company'])
            ->join('users', 'invoices.user_id', '=', 'users.id')
            ->subQuery(function($query) use ($date_start, $date_end) {
                if (!Auth::role(Roles::ROLE[0])) {
                    $query->where('user_id', Auth::get('id'));
                }

                if (!empty($date_start) && !empty($date_end)) {
                    $query->whereBetween('created_at', $date_start, $date_end);
                }
            })
            ->oldest()
            ->all();
    }
}
