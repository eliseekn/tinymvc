<?php

namespace App\Database\Repositories;

use Framework\Database\Repository;

class Wallet extends Repository
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
     * @return int
     */
    public function findSumByStatus(?string $status = null): int
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
}
