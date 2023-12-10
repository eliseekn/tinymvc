<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\UseCases\User;

use App\Database\Models\User;
use Core\Database\Model;

class StoreUseCase
{
    public function handle(array $data): Model|false
	{
        return (new User())->create($data);
	}
}
