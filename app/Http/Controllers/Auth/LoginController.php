<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Controllers\Auth;

use App\Http\Validators\Auth\LoginValidator;
use Core\Routing\Controller;
use Core\Support\Alert;
use Core\Support\Auth;

class LoginController extends Controller
{ 
    public function index(): void
    {
        if (!Auth::check($this->request)) {
            $this->render('auth.login');
        }

        $uri = !$this->session->has('intended') ? config('app.home') : $this->session->pull('intended');
        $this->redirectUrl($uri);
    }

	public function authenticate(): void
	{
        $this->validate(new LoginValidator());

        if (Auth::attempt($this->response, $this->request)) {
            Alert::toast(__('welcome', ['name' => Auth::get('name')]))->success();
            $uri = !$this->session->has('intended') ? config('app.home') : $this->session->pull('intended');
            $this->redirectUrl($uri);
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
