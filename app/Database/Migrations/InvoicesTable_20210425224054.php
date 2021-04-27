<?php

namespace App\Database\Migrations;

use Framework\Database\Schema;

class InvoicesTable_20210425224054
{         
    /**
     * name of table
     *
     * @var string
     */
    protected $table = 'invoices';

    /**
     * create table
     *
     * @return void
     */
    public function create(): void
    {
        Schema::createTable($this->table)
            ->addBigInt('id')->primaryKey()
            ->addBigInt('user_id')
            ->addString('invoice_id')
            ->addLongText('products')
            ->addDecimal('sub_total')
            ->addDecimal('total_price')
            ->addString('currency')
            ->addDecimal('tax')->setNull()
            ->addTimestamp('expire')->setNull()
            ->addEnum('status', ['pending', 'paid', 'expired'])->default('pending')
            ->create();
    }
    
    /**
     * drop table
     *
     * @return void
     */
    public function drop(): void
    {
        Schema::dropTable($this->table);
    }
}
