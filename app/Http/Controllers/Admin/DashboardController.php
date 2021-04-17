<?php

namespace App\Http\Controllers\Admin;

use Framework\Support\Metrics;
use Framework\Routing\Controller;
use App\Database\Repositories\Users;
use App\Database\Repositories\Medias;
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
	 * @return void
	 */
	public function index(Users $users, Medias $medias, Notifications $notifications, Messages $messages): void
	{
		$this->render('admin.index', [
            'total_users' => count($users->findAll()), 
            'active_users' => count($users->findAllBy('active', 1)), 
            'users_metrics' => $users->metrics('id', Metrics::COUNT, Metrics::MONTHS), 
            'total_medias' => count($medias->findAllByUser()),
            'notifications' => $notifications->findMessages(), 
            'messages' => $messages->findReceivedMessages()
        ]);
    }
}
