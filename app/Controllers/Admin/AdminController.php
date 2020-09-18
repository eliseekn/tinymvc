<?php

namespace App\Controllers\Admin;

use Framework\Routing\View;
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
			'online_users' => UsersModel::findAllWhere('online', 1)
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
			'users' => UsersModel::paginate(3, ['name', 'ASC'])
		]);
	}
}
