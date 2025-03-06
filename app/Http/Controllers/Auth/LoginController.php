<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\UseCases\LoginUseCase;
use App\Http\Validation\Validators\Auth\LoginValidator;
use Core\Enums\HttpMethod;
use Core\Http\Auth;
use Core\Http\Routing\Attributes\Route;
use Core\Http\Routing\Controller;

class LoginController extends Controller
{
    #[Route(HttpMethod::GET, '/login', ['remember', 'redirect'])]
    public function index(): void
    {
        $this->render('auth.login');
    }

    #[Route(HttpMethod::POST, '/login')]
    public function login(LoginUseCase $useCase, LoginValidator $validator): void
    {
        $useCase->handle($validator->inputs());
    }
}
