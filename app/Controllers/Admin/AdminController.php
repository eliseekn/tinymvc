<?php

namespace App\Controllers\Admin;

use Framework\Routing\View;
use App\Database\Models\UsersModel;

class AdminController
{
	/**
	 * display admin home page
	 *
	 * @return void
	 */
	public function index(): void
	{
		View::render('admin/index', [
			'users' => UsersModel::findAll(['name', 'ASC'])
		]);
	}
}
