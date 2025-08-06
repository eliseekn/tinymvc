<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\UseCases\PasswordReset\NotifyUseCase;
use App\Http\UseCases\PasswordReset\ResetUseCase;
use App\Http\UseCases\PasswordReset\UpdatePasswordUseCase;
use App\Http\Validation\Validators\Auth\UpdatePasswordValidator;
use Core\Enums\HttpMethod;
use Core\Http\Routing\Attributes\Route;
use Core\Http\Routing\Controller;

/**
 * Manage password forgot.
 */
class PasswordForgotController extends Controller
{
    #[Route(HttpMethod::POST, '/password/notify')]
    public function notify(NotifyUseCase $useCase): void
    {
        $useCase->handle();
    }

    #[Route(HttpMethod::GET, '/password/reset')]
    public function reset(ResetUseCase $useCase): void
    {
        $useCase->handle();
    }

    #[Route(HttpMethod::POST, '/password/update')]
    public function update(UpdatePasswordUseCase $useCase, UpdatePasswordValidator $validator): void
    {
        $useCase->handle($validator->inputs());
    }
}
