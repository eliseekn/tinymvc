<?php

namespace App\Controllers\Admin;

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
		$this->render('admin.index', [
            'total_users' => UsersModel::count()->single()->value, 
            'inactive_users' => UsersModel::count()->where('active', 0)->single()->value ,
            'active_users' => UsersModel::count()->where('active', 1)->single()->value, 
            'users_metrics' => UsersModel::metrics('id', Metrics::COUNT, Metrics::MONTHS), 
            'notifications' => NotificationsModel::messages()->take(5), 
            'messages' => MessagesModel::recipients()->take(5)
        ]);
    }
}
