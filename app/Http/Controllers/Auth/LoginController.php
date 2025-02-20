<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Controllers\Auth;

use App\Http\UseCases\LoginUseCase;
use App\Http\Validators\Auth\LoginValidator;
use Core\Enums\HttpMethod;
use Core\Http\Auth;
use Core\Routing\Attributes\Route;
use Core\Routing\Controller;

class LoginController extends Controller
{
    #[Route(HttpMethod::GET, '/login', ['remember'])]
    public function index(): void
    {
        if (! Auth::check($this->request)) {
            $this->render('auth.login');
        }

        $this->redirectToUrl('/dashboard');
    }

    #[Route(HttpMethod::POST, middlewares: ['csrf'])]
    public function authenticate(LoginUseCase $useCase, LoginValidator $validator): void
    {
        $useCase->handle($validator->validated());
    }
}
