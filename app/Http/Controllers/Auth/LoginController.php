<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Core\Support\Auth;
use Core\Support\Alert;
use Core\Support\Session;
use App\Http\Validators\Auth\LoginValidator;

class LoginController extends Controller
{ 
    public function index(): void
    {
        if (!Auth::check($this->request)) {
            $this->render('auth.login');
        }

        $uri = !Session::has('intended') ? config('app.home') : Session::pull('intended');
        $this->redirect($uri);
    }

	public function authenticate(LoginValidator $validator): void
	{
        $validator->validate($this->request->inputs(), $this->response);

        if (Auth::attempt($this->response, $this->request->only(['email', 'password']), $this->request->hasInput('remember'))) {
            Alert::toast(__('welcome', ['name' => Auth::get('name')]))->success();
            $uri = !Session::has('intended') ? config('app.home') : Session::pull('intended');
            $this->redirect($uri);
        }

        Alert::default(__('login_failed'))->error();
        $this
            ->response
            ->url('/login')
            ->withInputs($this->request->only(['email', 'password']))
            ->withErrors([__('login_failed')])
            ->send();
    }
}
