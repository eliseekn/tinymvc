<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Auth;

use App\Exceptions\InvalidCredentialsException;
use App\Http\Validation\Validators\Auth\LoginValidator;
use App\Http\Validation\Validators\Auth\RegisterValidator;
use App\UseCases\Api\v1\Auth\LoginUseCase;
use App\UseCases\Api\v1\Auth\RegisterUseCase;
use App\UseCases\EmailVerification\NotifyUseCase;
use Core\Enums\HttpCode;
use Core\Enums\ResponseStatus;
use Core\Http\Auth;
use Core\Http\Routing\Controller;

class AuthController extends Controller
{
    public function login(LoginUseCase $useCase, LoginValidator $validator): void
    {
        try {
            $data = $useCase->handle($validator->validated());

            $this->jsonResponse([
                'status' => ResponseStatus::SUCCESS,
                'token' => $data['token'],
                'user' => $data['user'],
            ], HttpCode::OK);
        } catch (InvalidCredentialsException $e) {
            $this->jsonResponse([
                'status' => ResponseStatus::ERROR,
                'message' => 'Email or password is incorrect',
            ], HttpCode::UNAUTHORIZED);
        }
    }

    public function logout(): void
    {
        Auth::deleteToken();

        $this->jsonResponse([
            'status' => ResponseStatus::SUCCESS,
            'message' => 'Logout successfully',
        ], HttpCode::OK);
    }

    public function register(RegisterUseCase $useCase, NotifyUseCase $notifyUseCase, RegisterValidator $valitator): void
    {
        $useCase->handle($valitator->validated(), $notifyUseCase);
    }
}
