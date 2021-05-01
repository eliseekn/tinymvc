<?php

namespace App\Http\Controllers\Admin;

use App\Database\Repositories\Wallet;
use App\Helpers\Report;
use Framework\Http\Request;
use Framework\Routing\Controller;
use Framework\Support\Metrics;

class WalletController extends Controller
{
    /**
     * @var \Framework\Database\Wallet $wallet
     */
    private $wallet;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct(Wallet $wallet)
    {
        $this->wallet = $wallet;
    }

    /**
     * index
     *
     * @return void
     */
    public function index(): void
	{
        $invoices = $this->wallet->findSumByStatus();
        $incomes = $this->wallet->findSumByStatus('paid');
        $incomes_metrics = $this->wallet->metrics('total_price', Metrics::SUM, Metrics::MONTHS);

		$this->render('admin.account.wallet', compact('invoices', 'incomes', 'incomes_metrics'));
	}
}
