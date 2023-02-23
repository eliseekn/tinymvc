<?php

/**
 * @copyright (2019 - 2022) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Actions\User;

use App\Database\Models\User;

class UpdateAction
{
    public function handle(array $data, string $email)
	{
        $user = User::findBy('email', $email);

        if ($user === false) {
            return false;
        }

        if (isset($data['password'])) {
            $data['password'] = hash_pwd($data['password']);
        }

        $user->fill($data);
        return $user->save();
	}
}
