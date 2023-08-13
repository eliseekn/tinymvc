<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Actions\User;

use App\Database\Models\User;
use Core\Database\Model;

class StoreAction
{
    public function handle(array $data): Model|false
	{
        $data['password'] = hash_pwd($data['password']);
        return (new User())->create($data);
	}
}
