<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\UseCases\User;

use App\Database\Models\User;
use Core\Database\Model;
use Core\Support\UseCase;

class UpdateUseCase extends UseCase
{
    public function handle(array $data, string $email): Model|false
    {
        $user = User::findByEmail($email);

        if (! $user) {
            return false;
        }

        if (isset($data['password'])) {
            $data['password'] = hash_pwd($data['password']);
        }

        return $user->set($data)->save();
    }
}
