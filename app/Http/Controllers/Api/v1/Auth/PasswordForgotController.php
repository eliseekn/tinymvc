<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Validation\Validators\Auth\EmailValidator;
use App\Http\Validation\Validators\Auth\UpdatePasswordValidator;
use App\UseCases\Api\v1\ResetPasswordUseCase;
use App\UseCases\PasswordReset\NotifyUseCase;
use App\UseCases\PasswordReset\UpdatePasswordUseCase;
use Core\Enums\HttpMethod;
use Core\Http\Response\BaseResponse;
use Core\Http\Routing\Attributes\Route;
use Core\Http\Routing\Controller;

/**
 * Manage password forgot.
 */
class PasswordForgotController extends Controller
{
    #[Route(HttpMethod::POST, '/api/v1/password/notify')]
    public function notify(EmailValidator $validator, NotifyUseCase $useCase): BaseResponse
    {
        $useCase->handle($validator->validated('email'));

        return $this->successJsonResponse(__('alert.password_reset_link_sent'));
    }

    #[Route(HttpMethod::GET, '/api/v1/password/reset')]
    public function reset(ResetPasswordUseCase $useCase): BaseResponse
    {
        $useCase->handle();

        return $this->successJsonResponse(__('alert.valid_password_reset_link'));
    }

    #[Route(HttpMethod::POST, '/api/v1/password/update')]
    public function update(UpdatePasswordUseCase $useCase, UpdatePasswordValidator $validator): BaseResponse
    {
        if (! $useCase->handle($validator->validated())) {
            return $this->errorJsonResponse(__('alert.password_not_reset'));
        }

        return $this->successJsonResponse(__('alert.password_reset'));
    }
}
