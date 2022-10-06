<?php

/**
 * @copyright (2019 - 2022) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\UseCases\User;

use App\Database\Models\User;

final class DeleteUseCase
{
    public function handle(int $id): bool
	{
        return User::find($id)->delete();
	}
}
