<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\UseCases\Api\v1\ResetPasswordUseCase;
use App\Http\UseCases\PasswordReset\NotifyUseCase;
use App\Http\UseCases\PasswordReset\UpdatePasswordUseCase;
use App\Http\Validation\Validators\Auth\EmailValidator;
use App\Http\Validation\Validators\Auth\UpdatePasswordValidator;
use Core\Enums\HttpCode;
use Core\Enums\HttpMethod;
use Core\Enums\ResponseStatus;
use Core\Http\Routing\Attributes\Route;
use Core\Http\Routing\Controller;

/**
 * Manage password forgot.
 */
class PasswordForgotController extends Controller
{
    #[Route(HttpMethod::POST, '/api/v1/password/notify')]
    public function notify(EmailValidator $validator, NotifyUseCase $useCase): void
    {
        if ($useCase->handle($validator->inputs('email'))) {
            response()->json([
                'status' => ResponseStatus::SUCCESS,
                'message' => __('alert.password_reset_link_sent'),
            ])->send(HttpCode::OK);
        }

        response()->json([
            'status' => ResponseStatus::ERROR,
            'message' => __('alert.password_reset_link_not_sent'),
        ])->send(HttpCode::INTERNAL_SERVER_ERROR);
    }

    #[Route(HttpMethod::GET, '/api/v1/password/reset')]
    public function reset(ResetPasswordUseCase $useCase): void
    {
        $useCase->handle();
    }

    #[Route(HttpMethod::POST, '/api/v1/password/update')]
    public function update(UpdatePasswordUseCase $useCase, UpdatePasswordValidator $validator): void
    {
        if (! $useCase->handle($validator->inputs())) {
            response()->json([
                'status' => ResponseStatus::ERROR,
                'message' => __('alert.password_not_reset'),
            ])->send(HttpCode::INTERNAL_SERVER_ERROR);
        }

        response()->json([
            'status' => ResponseStatus::SUCCESS,
            'message' => __('alert.password_reset'),
        ])->send(HttpCode::OK);
    }
}
