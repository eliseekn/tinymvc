<?php

namespace App\Controllers\Admin;

use Framework\Support\Metrics;
use Framework\Routing\Controller;
use App\Database\Models\UsersModel;
use App\Database\Models\MessagesModel;
use App\Database\Models\NotificationsModel;
use Framework\Support\Session;

class DashboardController extends Controller
{    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->middlewares('RememberUser', 'DashboardPolicy');
    }

	/**
	 * display dashboard page
	 *
	 * @return void
	 */
	public function index(): void
	{
        /* dd(
            Session::get('auth_attempts'),
            Session::get('auth_attempts_timeout')
        ); */

        $total_users = UsersModel::count()->single()->value;
        $active_users = UsersModel::count()->where('active', 1)->single()->value;
        $inactive_users = UsersModel::count()->where('active', 0)->single()->value;
        $users_metrics = UsersModel::metrics('id', Metrics::COUNT, Metrics::MONTHS);
        $notifications = NotificationsModel::get()->take(5);
        $messages = MessagesModel::recipients()->take(5);

		$this->render('admin/index', compact('total_users', 'inactive_users', 'active_users', 'users_metrics', 'notifications', 'messages'));
    }
}
