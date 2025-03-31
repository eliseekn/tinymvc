<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\UseCases\User;

use App\Database\Models\User;
use App\Events\UserCreated\UserCreatedEvent;
use Core\Support\Alert;
use Core\Support\UseCase;

final class StoreUseCase extends UseCase
{
    public function handle(array $data): void
    {
        $password = $data['password'];
        $data['password'] = bcrypt($password);

        $user = User::factory()->create($data);

        if (! $user) {
            Alert::toast('Failed to create user')->error();
        }

        dispatch(new UserCreatedEvent($user, $password));

        Alert::toast('User created')->success();
        $this->response->back()->send();
    }
}
