<?php

declare(strict_types=1);

namespace App\UseCases\PasswordReset;

use App\Database\Models\User;

final class UpdatePasswordUseCase
{
    public function handle(array $data): bool
    {
        $user = User::findByEmail($data['email']);

        if (! $user->set(['password' => bcrypt($data['password'])])->save()) {
            return false;
        }

        return true;
    }
}
