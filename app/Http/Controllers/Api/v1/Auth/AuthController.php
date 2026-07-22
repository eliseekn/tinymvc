<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Validation\Validators\Auth\LoginValidator;
use App\Http\Validation\Validators\Auth\RegisterValidator;
use App\UseCases\Api\v1\Auth\LoginUseCase;
use App\UseCases\Auth\RegisterUseCase;
use App\UseCases\Shared\NotifyUseCase;
use Core\Enums\HttpCode;
use Core\Enums\ResponseStatus;
use Core\Http\Auth;
use Core\Http\Response\BaseResponse;
use Core\Http\Routing\Controller;

class AuthController extends Controller
{
    public function login(LoginUseCase $useCase, LoginValidator $validator): BaseResponse
    {
        $data = $useCase->handle($validator->validated());

        return $this->jsonResponse([
            'status' => ResponseStatus::SUCCESS,
            'token' => $data['token'],
            'user' => $data['user'],
        ], HttpCode::OK);
    }

    public function logout(): BaseResponse
    {
        Auth::deleteToken();

        return $this->successJsonResponse('Logout successfully', HttpCode::OK);
    }

    public function register(RegisterUseCase $useCase, NotifyUseCase $notifyUseCase, RegisterValidator $valitator): BaseResponse
    {
        $useCase->handle($valitator->validated(), $notifyUseCase);

        return $this->successJsonResponse(__('alert.account_created'), HttpCode::OK);
    }
}
