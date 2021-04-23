<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Auth;
use Framework\Support\Metrics;
use Framework\Routing\Controller;
use App\Database\Repositories\Roles;
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
        $users_metrics = Auth::role(Roles::ROLE[1])
            ? $users->metrics('id', Metrics::COUNT, Metrics::MONTHS, 0, ['AND id != ? AND parent_id = ?', [Auth::get('id'), Auth::get('id')]])
            : $users->metrics('id', Metrics::COUNT, Metrics::MONTHS, 0, ['AND id != ?', [Auth::get('id')]]);

		$this->render('admin.index', [
            'total_users' => count($users->findAllByRole()), 
            'active_users' => $users->activeCount(), 
            'users_metrics' => $users_metrics, 
            'total_medias' => count($medias->findAllByUser()),
            'notifications' => $notifications->findMessages(), 
            'messages' => $messages->findReceivedMessages()
        ]);
    }
}
