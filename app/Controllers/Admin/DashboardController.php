<?php

namespace App\Controllers\Admin;

use Framework\Routing\View;
use Framework\Support\Metrics;
use App\Database\Models\RolesModel;
use App\Database\Models\UsersModel;

class DashboardController
{
	/**
	 * display dashboard page
	 *
	 * @return void
	 */
	public function index(): void
	{
		View::render('admin/index', [
			'users' => UsersModel::select()->all(),
			'online_users' => UsersModel::find('online', 1)->all(),
			'active_users' => UsersModel::find('active', 1)->all(),
			'users_metrics' => UsersModel::metrics('id', Metrics::COUNT, Metrics::MONTHS)
		]);
	}
}