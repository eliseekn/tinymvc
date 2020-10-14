<?php

namespace App\Controllers\Admin;

use Framework\Routing\View;
use App\Helpers\MetricsHelper;
use App\Database\Models\RolesModel;
use App\Database\Models\UsersModel;

class AdminController
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
			'users_metrics' => MetricsHelper::getCount('users', 'id', 'months')
		]);
	}

	/**
	 * display users page
	 *
	 * @return void
	 */
	public function users(): void
	{
		View::render('admin/users/index', [
			'users' => UsersModel::select()->orderBy('name', 'ASC')->paginate(50)
		]);
	}

	/**
	 * display roles page
	 *
	 * @return void
	 */
	public function roles(): void
	{
		View::render('admin/roles/index', [
			'roles' => RolesModel::select()->orderBy('title', 'ASC')->paginate(50)
		]);
	}
}