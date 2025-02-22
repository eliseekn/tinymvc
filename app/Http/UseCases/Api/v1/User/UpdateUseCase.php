<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\UseCases\Api\v1\User;

use App\Database\Models\User;
use Core\Database\Model;
use Core\Support\UseCase;

class UpdateUseCase extends UseCase
{
    public function handle(array $data, int $id): Model|false
    {
        $user = User::find($id);
        return ! $user ? false : $user->set($data)->save();
    }
}
