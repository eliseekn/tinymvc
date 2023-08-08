<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Controllers\Auth;

use App\Http\Actions\User\StoreAction;
use App\Http\Validators\Auth\RegisterValidator;
use App\Mails\WelcomeMail;
use Core\Routing\Controller;
use Core\Support\Alert;
use Core\Support\Auth;
use Core\Support\Mail\Mail;
use Core\Support\Session;

class RegisterController extends Controller
{
    public function index(): void
    {
        if (!Auth::check($this->request)) {
            $this->render('auth.signup');
        }

        $uri = !Session::has('intended') ? config('app.home') : Session::pull('intended');
        $this->redirectUrl($uri);
    }

    public function register(StoreAction $action): void
    {
        $validated = $this->validateRequest(new RegisterValidator());
        $user = $action->handle($validated);

        if (config('security.auth.email_verification')) {
            $this->redirectUrl('/email/notify' , ['email' => $user->attribute('email')]);
        }

        Mail::send(new WelcomeMail($user->attribute('email'), $user->attribute('name')));
        Alert::default(__('account_created'))->success();

        $this->redirectUrl('/login');
    }
}
