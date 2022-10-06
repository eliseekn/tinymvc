<?php

/**
 * @copyright (2019 - 2022) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\UseCases\User;

use App\Database\Models\User;

final class UpdateUseCase
{
    public function handle(array $data, int $id): bool
	{
        $user = User::find($id);
        $user->fill($data);

        return $user->save();
	}

    public function handleByEmail(array $data, string $email): bool
    {
        $user = User::where('email', $email)->first();
        $user->fill($data);

        return $user->save();
    }
}
