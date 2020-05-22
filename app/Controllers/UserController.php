<?php

/**
 * TinyMVC
 * 
 * PHP framework based on MVC architecture
 * 
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace App\Controllers;

use App\Models\UsersModel;
use Framework\Http\Router;
use Framework\Http\Request;
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

	public function login()
	{
		$request = new Request();
        $email = $request->postQuery('email');
		$password = $request->postQuery('password');
		
        $user = new UsersModel();

        if ($user->isRegistered($email, $password)) {
			create_session('logged_user', $user->get($email));
            Router::redirectToRoute('admin');
		}

		Router::redirectToRoute('login.page');
	}
}
