<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Http\UseCases\Api\v1\Auth\LoginUseCase;
use App\Http\UseCases\Api\v1\Auth\LogoutUseCase;
use App\Http\Validation\Validators\Auth\LoginValidator;
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
}
