<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Controllers\Auth;

use Core\Http\Request;
use Core\Support\Auth;
use Core\Support\Alert;
use Core\Support\Session;
use Core\Http\Response;
use App\Http\Validators\Auth\LoginValidator;

class LoginController
{ 
    public function index(Request $request, Response $response): void
    {
        if (!Auth::check($request)) {
            $response->view('auth.login')->send();
        }

        $uri = !Session::has('intended') ? config('app.home') : Session::pull('intended');
        $response->url($uri)->send(302);
    }

	public function authenticate(Request $request, Response $response, LoginValidator $loginValidator): void
	{
        $loginValidator->validate($request->inputs(), $response);

        if (Auth::attempt($response, $request->only('email', 'password'), $request->has('remember'))) {
            $uri = !Session::has('intended') ? config('app.home') : Session::pull('intended');

            Alert::toast(__('welcome', ['name' => Auth::get('name')]))->success();
            $response->url($uri)->send(302);
        }

        Alert::default(__('login_failed'))->error();
        $response->url('/login')->withInputs($request->only('email', 'password'))->withErrors([__('login_failed')])->send(302);
    }
}
