<?php

namespace App\Controllers\Admin;

use App\Helpers\AuthHelper;
use Framework\Support\Metrics;
use Framework\Routing\Controller;
use App\Database\Models\UsersModel;
use App\Database\Models\MessagesModel;
use App\Database\Models\NotificationsModel;

class DashboardController extends Controller
{
	/**
	 * display dashboard page
	 *
	 * @return void
	 */
	public function index(): void
	{
        $total_users = UsersModel::count()->single()->value;
        $active_users = UsersModel::count()->where('active', 1)->single()->value;
        $inactive_users = $total_users - $active_users;
        $users_metrics = UsersModel::metrics('id', Metrics::COUNT, Metrics::MONTHS);
        $notifications = NotificationsModel::find('status', 'unread')->orderDesc('created_at')->firstOf(5);

        $messages = MessagesModel::select(['messages.*', 'users.email AS sender_email', 'users.name AS sender_name'])
            ->join('users', 'messages.sender', 'users.id')
            ->where('messages.recipient', AuthHelper::getSession()->id)
            ->andWhere('messages.status', 'unread')
            ->orderDesc('messages.created_at')
            ->firstOf(5);

		$this->render('admin/index', compact('total_users', 'inactive_users', 'active_users', 'users_metrics', 'notifications', 'messages'));
    }
}
