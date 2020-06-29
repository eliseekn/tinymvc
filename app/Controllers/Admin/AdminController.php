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

	/**
	 * authenticate user
	 * 
	 * @return void
	 */
	public function authenticate(): void
	{
		LoginForm::validate([
			'redirect' => 'back'
		]);

		if (Authenticate::attempt()) {
			Redirect::toUrl('/admin')->only();
		} else {
			Redirect::back()->withError('Invalid email address and/or password.');
		}
	}
	
	/**
	 * logout
	 *
	 * @return void
	 */
	public function logout(): void
	{
		Authenticate::logout();
		Redirect::toUrl('/')->only();
	}
}
