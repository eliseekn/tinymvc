<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Middlewares\RememberUser;
use App\Http\Validation\Validators\Auth\RegisterValidator;
use App\UseCases\Auth\RegisterUseCase;
use App\UseCases\EmailVerification\NotifyUseCase;
use Core\Enums\HttpMethod;
use Core\Http\Auth;
use Core\Http\Routing\Attributes\Route;
use Core\Http\Routing\Controller;

class RegisterController extends Controller
{
    #[Route(HttpMethod::GET, '/signup', [RememberUser::class])]
    public function index(): void
    {
        if (! Auth::check()) {
            $this->render('auth.signup');
        }

        $this->redirectToUrl(config('app.home'));
    }

    #[Route(HttpMethod::POST)]
    public function register(RegisterUseCase $useCase, NotifyUseCase $notifyUseCase, RegisterValidator $validator): void
    {
        $useCase->handle($validator->validated(), $notifyUseCase);
    }
}
