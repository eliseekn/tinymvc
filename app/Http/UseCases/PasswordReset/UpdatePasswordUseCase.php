<?php

declare(strict_types=1);

namespace App\Http\UseCases\PasswordReset;

use App\Database\Models\User;
use Core\Support\Alert;

final class UpdatePasswordUseCase
{
    public function handle(array $data): void
    {
        $user = User::findByEmail($data['email']);

        if (! $user->set(['password' => bcrypt($data['password'])])->save()) {
            Alert::default(__('alert.password_not_reset'))->error();
            response()->back()->send();
        }

        Alert::default(__('alert.password_reset'))->success();
        response()->url('/login')->send();
    }
}
