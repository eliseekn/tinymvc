<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use Core\Http\Request;
use Core\Support\Auth;
use Core\Support\Alert;
use Core\Support\Session;
use Core\Http\Response\Response;
use App\Http\Validators\Auth\LoginValidator;

class LoginController
{ 
    public function index(Request $request, Response $response)
    {
        if (!Auth::check($request)) $response->view('auth.login'); 

        $uri = !Session::has('intended') ? Auth::HOME : Session::pull('intended');
        $response->redirect()->to($uri)->go();
    }

	public function authenticate(Request $request, Response $response)
	{
        LoginValidator::make($request->inputs())->redirectBackOnFail($response);

        if (Auth::attempt($request->only('email', 'password'), $request->has('remember'))) {
            $uri = !Session::has('intended') ? Auth::HOME : Session::pull('intended');

            Alert::toast(__('welcome', ['name' => Auth::get('name')]))->success();
            $response->redirect()->to($uri)->go();
        }

        if (Auth::attemptsExceeded()) {
            $response->redirect()->back()->with('auth_attempts_timeout', Carbon::now()->addMinutes(config('security.auth.unlock_timeout'))->toDateTimeString())->go();
        }
        
        Alert::default(__('login_failed'))->error();
        $response->redirect()->to('login')->withInputs($request->only('email', 'password'))->withErrors([__('login_failed')])->go();
    }
}
