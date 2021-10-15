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
use App\Mails\WelcomeMail;
use Core\Support\Mailer\Mailer;
use Core\Http\Response\Response;
use App\Http\Validators\AuthRequest;
use App\Http\Validators\RegisterUser;

/**
 * Manage user authentication
 */
class AuthController
{ 
    public function login(Request $request, Response $response)
    {
        if (!Auth::check($request)) $response->view('auth.login'); 

        $uri = !Session::has('intended') ? Auth::HOME : Session::pull('intended');
        $response->redirect()->to($uri)->go();
    }

    public function signup(Request $request, Response $response)
    {
        if (!Auth::check($request)) $response->view('auth.signup'); 

        $uri = !Session::has('intended') ? Auth::HOME : Session::pull('intended');
        $response->redirect()->to($uri)->go();
    }

	public function authenticate(Request $request, Response $response)
	{
        AuthRequest::make($request->inputs())->redirectBackOnFail($response);

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
    
    public function register(Request $request, Mailer $mailer, Response $response)
    {
        RegisterUser::make($request->inputs())->redirectBackOnFail($response);
        $user = Auth::create($request->inputs());

        if (!config('security.auth.email_verification')) {
            WelcomeMail::send($mailer, $user->email, $user->name);

            Alert::default(__('account_created'))->success();
            $response->redirect()->to('login')->go();
        }

        (new EmailVerificationController())->notify($request, $response, $mailer);
    }
	
	public function logout(Response $response)
	{
        Auth::forget();

        Alert::toast(__('logged_out'))->success();
        $response->redirect()->to(Auth::HOME)->go();
	}
}
