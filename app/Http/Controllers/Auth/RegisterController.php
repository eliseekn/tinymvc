<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Controllers\Auth;

use App\Events\UserRegistered\UserRegisteredEvent;
use App\Http\UseCases\User\StoreUseCase;
use App\Http\Validators\Auth\RegisterValidator;
use Core\Routing\Attributes\Route;
use Core\Routing\Controller;
use Core\Support\Alert;
use Core\Support\Auth;

class RegisterController extends Controller
{
    #[Route('GET', '/signup', ['remember'])]
    public function index(): void
    {
        if (!Auth::check($this->request)) {
            $this->render('auth.signup');
        }

        $this->redirectUrl(config('app.home'));
    }

    #[Route(methods: 'POST', middlewares: ['csrf'])]
    public function register(StoreUseCase $useCase): void
    {
        $validated = $this->validate(new RegisterValidator());
        $user = $useCase->handle($validated);

        if (config('security.auth.email_verification')) {
            $this->redirectUrl('/email/notify' , ['email' => $user->get('email')]);
        }

        UserRegisteredEvent::dispatch([$user]);

        Alert::default(__('account_created'))->success();
        $this->redirectUrl('/login');
    }
}
