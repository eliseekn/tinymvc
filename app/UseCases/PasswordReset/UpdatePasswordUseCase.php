<?php

declare(strict_types=1);

namespace App\UseCases\PasswordReset;

use App\Database\Models\UserModel;

final class UpdatePasswordUseCase
{
    public function handle(array $data): bool
    {
        $user = UserModel::findByEmail($data['email']);

        if (is_null($user)) {
            return false;
        }

        $user->setPassword(bcrypt($data['password']));

        return (bool) $user->toModel()->save();
    }
}
