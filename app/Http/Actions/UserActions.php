<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Actions;

use App\Database\Models\User;

class UserActions
{
    public static function create(array $data)
	{
        $data['password'] = hash_pwd($data['password']);

        return User::create($data);
	}

    public static function update(array $data, string $email)
	{
        $user = User::findBy('email', $email);

        if (!$user) return false;

        if (isset($data['password'])) {
            $data['password'] = hash_pwd($data['password']);
        }

        $user->fill($data);
        $user = $user->save();

        return $user;
	}
}
