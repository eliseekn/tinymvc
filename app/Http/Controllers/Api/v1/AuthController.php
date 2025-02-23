<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Controllers\Api\v1;

use App\Database\Models\User;
use App\Http\Validators\Auth\LoginValidator;
use Core\Enums\HttpCode;
use Core\Enums\ResponseStatus;
use Core\Http\Auth;
use Core\Routing\Controller;
use Core\Support\Encryption;

class AuthController extends Controller
{
    public function login(LoginValidator $validator): void
    {
        $data = $validator->validated();
        $user = User::findByEmail($data['email']);

        if (! $user || ! Encryption::check($data['password'], $user->get('password'))) {
            $this->jsonResponse([
                'status' => ResponseStatus::ERROR,
                'message' => 'Email or password is incorrect',
            ], HttpCode::UNAUTHORIZED);
        }

        $this->jsonResponse([
            'status' => ResponseStatus::SUCCESS,
            'token' => Auth::createToken($user->get('email')),
            'user' => $user->get(),
        ]);
    }

    public function logout(): void
    {
        if (! Auth::deleteToken($this->request)) {
            $this->jsonResponse([
                'status' => ResponseStatus::ERROR,
                'message' => 'Failed to logout',
            ], HttpCode::INTERNAL_SERVER_ERROR);
        }

        $this->jsonResponse([
            'status' => ResponseStatus::SUCCESS,
            'message' => 'Logout successfully',
        ]);
    }
}
