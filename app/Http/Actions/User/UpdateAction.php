<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Actions\User;

use App\Database\Models\User;
use Core\Database\Model;

class UpdateAction
{
    public function handle(array $data, string $email): Model|false
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
