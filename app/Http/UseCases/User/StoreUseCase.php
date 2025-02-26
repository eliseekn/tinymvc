<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\UseCases\User;

use App\Database\Models\User;
use Core\Enums\HttpCode;
use Core\Support\Alert;
use Core\Support\UseCase;

class StoreUseCase extends UseCase
{
    public function handle(array $data): void
    {
        $data['password'] = hash_pwd($data['password']);

        if (! User::factory()->create($data)) {
            Alert::toast('Failed to create user')->error();
        }

        Alert::toast('User created')->success();
        $this->response->back()->send(HttpCode::CREATED);
    }
}
