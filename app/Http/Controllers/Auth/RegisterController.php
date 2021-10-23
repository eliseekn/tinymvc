<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Controllers\Auth;

use Core\Http\Request;
use Core\Support\Auth;
use Core\Support\Alert;
use Core\Support\Session;
use App\Mails\WelcomeMail;
use Core\Support\Mailer\Mailer;
use Core\Http\Response\Response;
use App\Http\Actions\UserActions;
use App\Http\Validators\Auth\RegisterValidator;

class RegisterController
{
    public function index(Request $request, Response $response)
    {
        if (!Auth::check($request)) $response->view('auth.signup'); 

        $uri = !Session::has('intended') ? Auth::HOME : Session::pull('intended');
        $response->redirect()->to($uri)->go();
    }

    public function register(Request $request, Mailer $mailer, Response $response)
    {
        RegisterValidator::make($request->inputs())->redirectBackOnFail($response);
        $user = UserActions::create($request->except('csrf_token'));

        if (!config('security.auth.email_verification')) {
            WelcomeMail::send($mailer, $user->email, $user->name);

            Alert::default(__('account_created'))->success();
            $response->redirect()->to('login')->go();
        }

        $response->redirect()->to('email/notify')->go();
    }
}
