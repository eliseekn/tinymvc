<?php

/**
 * @copyright (2019 - 2022) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Actions\User;

use App\Database\Models\User;

class StoreAction
{
    public function handle(array $data)
	{
        $data['password'] = hash_pwd($data['password']);

        return User::create($data);
	}
}
