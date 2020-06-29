<?php

namespace App\Controllers\Admin;

use Framework\Routing\View;
use Framework\Http\Redirect;
use App\Validators\LoginForm;
use App\Database\Models\UsersModel;
use Framework\Support\Authenticate;

class AdminController
{
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
}
