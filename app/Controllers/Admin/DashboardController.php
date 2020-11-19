<?php

namespace App\Controllers\Admin;

use Framework\Support\Metrics;
use Framework\Routing\Controller;
use App\Database\Models\UsersModel;
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
		$this->render('admin/index', [
			'users' => UsersModel::select()->all(),
			'active_users' => UsersModel::find('active', 1)->all(),
            'users_metrics' => UsersModel::metrics('id', Metrics::COUNT, Metrics::MONTHS),
            'notifications' => NotificationsModel::find('status', 'unread')->orderDesc('created_at')->firstOf(5)
		]);
    }
}
