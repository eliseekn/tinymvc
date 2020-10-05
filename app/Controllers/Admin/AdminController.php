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
			'users' => UsersModel::findAll(),
			'online_users' => UsersModel::findAllWhere('online', 1),
			'active_users' => UsersModel::findAllWhere('active', 1),
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
			'users' => UsersModel::paginate(50, ['name', 'ASC'])
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
			'roles' => RolesModel::paginate(50, ['title', 'ASC'])
		]);
	}
}
