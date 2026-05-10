<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\UseCases\Api\v1\Auth\LoginUseCase;
use App\Http\UseCases\Api\v1\Auth\LogoutUseCase;
use App\Http\UseCases\Api\v1\Auth\RegisterUseCase;
use App\Http\UseCases\EmailVerification\NotifyUseCase;
use App\Http\Validation\Validators\Auth\LoginValidator;
use App\Http\Validation\Validators\Auth\RegisterValidator;
use Core\Http\Routing\Controller;

class AuthController extends Controller
{
    public function login(LoginUseCase $useCase, LoginValidator $validator): void
    {
        $useCase->handle($validator->inputs());
    }

    public function logout(LogoutUseCase $useCase): void
    {
        $useCase->handle();
    }

    public function register(RegisterUseCase $useCase, NotifyUseCase $notifyUseCase, RegisterValidator $valitator): void
    {
        $useCase->handle($valitator->inputs(), $notifyUseCase);
    }
}
