<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\UseCases\Api\v1\Auth;

use App\Database\Models\AdminModel;
use App\Exceptions\InvalidCredentialsException;
use App\Http\Resources\AdminResource;
use Core\Http\Auth\Auth;
use Core\Support\Encryption;

final class AdminLoginUseCase
{
    public function handle(array $data): array
    {
        $admin = AdminModel::findByIdentifier($data['username']);

        if (! $admin || ! Encryption::check($data['password'], $admin->getPassword())) {
            throw new InvalidCredentialsException;
        }

        return [
            'token' => Auth::createToken($admin),
            'admin' => new AdminResource($admin->toModel())->handle(),
        ];
    }
}
