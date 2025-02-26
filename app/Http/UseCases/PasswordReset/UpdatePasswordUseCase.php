<?php

declare(strict_types=1);

namespace App\Http\UseCases\PasswordReset;

use App\Http\UseCases\User\UpdateUseCase;
use Core\Support\Alert;
use Core\Support\UseCase;

class UpdatePasswordUseCase extends UseCase
{
    public function handle(UpdateUseCase $updateUseCase, array $data): void
    {
        $user = $updateUseCase->handle(['password' => $data['password']], $data['email']);

        if (! $user) {
            Alert::default(__('alert.password_not_reset'))->error();
            $this->response->back()->send();
        }

        Alert::default(__('alert.password_reset'))->success();
        $this->response->url('/login')->send();
    }
}
