<?php

declare(strict_types=1);

namespace App\UseCases\PasswordReset;

use App\Database\Models\User;

final class UpdatePasswordUseCase
{
    public function handle(array $data): bool
    {
        return User::findByEmail($data['email'])
            ->setAttributes(['password' => bcrypt($data['password'])])
            ->save();
    }
}
