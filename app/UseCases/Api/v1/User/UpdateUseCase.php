<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\UseCases\Api\v1\User;

use App\Policies\ProfilePolicy;
use Core\Database\Model;

final class UpdateUseCase
{
    public function handle(array $data, Model $user): bool
    {
        $user->isAuthorized(new ProfilePolicy)->onUpdate();

        if (! empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        return $user->setAttributes($data)->save();
    }
}
