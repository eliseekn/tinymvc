<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Controllers\Auth;

use App\Http\UseCases\EmailVerification\NotifyUseCase;
use App\Http\UseCases\User\RegisterUseCase;
use App\Http\Validators\Auth\RegisterValidator;
use Core\Enums\HttpMethod;
use Core\Http\Auth;
use Core\Routing\Attributes\Route;
use Core\Routing\Controller;

class RegisterController extends Controller
{
    #[Route(HttpMethod::GET, '/signup', ['remember'])]
    public function index(): void
    {
        if (! Auth::check($this->request)) {
            $this->render('auth.signup');
        }

        $this->redirectToUrl(config('app.home'));
    }

    #[Route(HttpMethod::POST, middlewares: ['csrf'])]
    public function register(RegisterUseCase $useCase, NotifyUseCase $notifyUseCase, RegisterValidator $validator): void
    {
        $useCase->handle($validator->validated(), $notifyUseCase);
    }
}
