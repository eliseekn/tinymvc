<?php

namespace App\Controllers;

use App\Models\UsersModel;
use Framework\Http\Request;
use Framework\Http\Redirect;
use Framework\Core\Controller;

/**
 * UserController
 * 
 * User controller
 */
class UserController extends Controller
{
	/**
	 * display login page
	 *
	 * @return void
	 */
	public function index(): void
	{
		$this->renderView('login', [
			'page_title' => 'Login page',
			'page_description' => 'Login page'
		]);
	}

	public function login(): void
	{
		$request = new Request();
        $email = $request->postQuery('email');
		$password = $request->postQuery('password');
		
		$user = new UsersModel();

        if ($user->isRegistered($email, $password)) {
			create_session('logged_user', $user->get($email));
			Redirect::toRoute('admin')->only();
		}

		Redirect::toRoute('login.page')->withMessage('login_failed', 'Incorect username or/and password');
	}

	public function logout(): void
	{
		close_session('logged_user');
		Redirect::toRoute('home')->only();
	}
}
