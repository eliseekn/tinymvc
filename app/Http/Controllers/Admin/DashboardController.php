<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Auth;
use Framework\Support\Metrics;
use Framework\Routing\Controller;
use App\Database\Repositories\Roles;
use App\Database\Repositories\Users;
use App\Database\Repositories\Medias;
use App\Database\Repositories\Wallet;
use App\Database\Repositories\Tickets;
use App\Database\Repositories\Messages;
use App\Database\Repositories\Notifications;

class DashboardController extends Controller
{
	/**
	 * index
	 *
     * @param  \App\Database\Repositories\Users $users
     * @param  \App\Database\Repositories\Medias $medias
     * @param  \App\Database\Repositories\Notifications $notifications
     * @param  \App\Database\Repositories\Messages $messages
     * @param  \App\Database\Repositories\Wallet $wallet
     * @param  \App\Database\Repositories\Tickets $tickets
	 * @return void
	 */
	public function index(
        Users $users, 
        Medias $medias, 
        Notifications $notifications, 
        Messages $messages, 
        Wallet $wallet,
        Tickets $tickets
    ): void {
		$this->render('admin.index', [
            'users' => count($users->findAllByCompany()),
            'customers' => count($users->findAllByRole(Roles::ROLE[1])),
            'incomes' => $wallet->findSumByStatus('paid'),
            'tickets' => $tickets->openCount(Auth::role(Roles::ROLE[0]) ? null : Auth::get('id')),
            'medias' => count($medias->findAllByUser()),
            'notifications' => $notifications->findMessages(), 
            'messages' => $messages->findReceivedMessages(),
            'users_metrics' => $users->metrics('id', Metrics::COUNT, Metrics::MONTHS, 0, ['AND role = ? AND id != ?', [Roles::ROLE[1], Auth::get('id')]]), 
            'incomes_metrics' => $wallet->metrics('total_price', Metrics::SUM, Metrics::MONTHS)
        ]);
    }
}
