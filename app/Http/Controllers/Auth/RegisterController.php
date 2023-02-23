<?php

/**
 * @copyright (2019 - 2022) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Controllers\Auth;

use Core\Http\Request;
use Core\Support\Auth;
use Core\Support\Alert;
use Core\Support\Session;
use App\Mails\WelcomeMail;
use Core\Support\Mail\Mail;
use Core\Http\Response;
use App\Http\Actions\User\StoreAction;
use App\Http\Validators\Auth\RegisterValidator;

class RegisterController
{
    public function index(Request $request, Response $response)
    {
        if (!Auth::check($request)) $response->view('auth.signup')->send(); 

        $uri = !Session::has('intended') ? config('app.home') : Session::pull('intended');
        $response->redirectUrl($uri)->send(302);
    }

    public function register(Request $request, Response $response, RegisterValidator $registerValidator, StoreAction $storeAction)
    {
        $validator = $registerValidator->validate($request->inputs(), $response);
        $user = $storeAction->handle($validator->validated());

        if (config('security.auth.email_verification')) {
            $response->redirectUrl('/email/notify')->send(302);
        }

        Mail::send(new WelcomeMail($user->email, $user->name));
        Alert::default(__('account_created'))->success();
        
        $response->redirectUrl('/login')->send(302);
    }
}
