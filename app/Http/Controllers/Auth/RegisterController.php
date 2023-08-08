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
use App\Mails\WelcomeMail;
use Core\Support\Mail\Mail;
use App\Http\Actions\User\StoreAction;
use App\Http\Validators\Auth\RegisterValidator;

class RegisterController extends Controller
{
    public function index(): void
    {
        if (!Auth::check($this->request)) {
            $this->render('auth.signup');
        }

        $uri = !Session::has('intended') ? config('app.home') : Session::pull('intended');
        $this->redirect($uri);
    }

    public function register(RegisterValidator $validator, StoreAction $action): void
    {
        $validator = $validator->validate($this->request->inputs(), $this->response);
        $user = $action->handle($validator->validated());

        if (config('security.auth.email_verification')) {
            $this->redirect('/email/notify' , ['email' => $user->attribute('email')]);
        }

        Mail::send(new WelcomeMail($user->attribute('email'), $user->attribute('name')));
        Alert::default(__('account_created'))->success();
        $this->redirect('/login');
    }
}
