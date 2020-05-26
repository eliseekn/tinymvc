<?php

namespace App\Controllers;

use Framework\Http\Request;
use Framework\Http\Redirect;
use Framework\Core\Controller;
use App\Database\Models\UsersModel;

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
        $email = $request->getInput('email');
		$password = $request->getInput('password');
		
		$user = new UsersModel();

        if ($user->isRegistered($email, $password)) {
			create_session('logged_user', $user->get($email));
			Redirect::toRoute('admin_posts')->only();
		}

		Redirect::toRoute('auth_page')->withMessage('login_failed', 'Incorect username or/and password.');
	}

	public function logout(): void
	{
		close_session('logged_user');
		Redirect::toRoute('home')->only();
	}
}
