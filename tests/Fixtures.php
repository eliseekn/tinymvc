<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests;

use App\Database\Entities\Token;
use App\Database\Entities\User;
use App\Database\Models\RoleModel;
use App\Database\Models\TokenModel;
use App\Database\Models\UserModel;
use App\Enums\UserRole;

abstract class Fixtures
{
    public static function makeAdmin(array $attributes = [], int $count = 1): User|array
    {
        $role = RoleModel::factory()->create(['name' => UserRole::ADMIN->value], true);
        $attributes['role_id'] = $role->getId();
        $result = UserModel::factory($count)->make($attributes);

        if (is_array($result)) {
            return array_map(fn (UserModel $user) => $user->toEntity(User::class), $result);
        }

        return $result->toEntity(User::class);
    }

    public static function createAdmin(array $attributes = [], int $count = 1): User|array
    {
        $result = self::makeAdmin($attributes, $count);

        if (is_array($result)) {
            return array_map(fn (User $r) => $r->toModel()->save(false)?->toEntity(User::class), $result);
        }

        return $result->toModel()->save(false)?->toEntity(User::class);
    }

    public static function makeUser(array $attributes = [], int $count = 1): User|array
    {
        $role = RoleModel::factory()->create(['name' => UserRole::USER->value], true);
        $attributes['role_id'] = $role->getId();
        $result = UserModel::factory($count)->make($attributes);

        if (is_array($result)) {
            return array_map(fn (UserModel $user) => $user->toEntity(User::class), $result);
        }

        return $result->toEntity(User::class);
    }

    public static function createUser(array $attributes = [], int $count = 1): User|array
    {
        $result = self::makeUser($attributes, $count);

        if (is_array($result)) {
            return array_map(fn (User $r) => $r->toModel()->save(false)?->toEntity(User::class), $result);
        }

        return $result->toModel()->save(false)?->toEntity(User::class);
    }

    public static function makeToken(array $attributes = [], int $count = 1): Token|array
    {
        $result = TokenModel::factory($count)->make($attributes);

        if (is_array($result)) {
            return array_map(fn (TokenModel $user) => $user->toEntity(Token::class), $result);
        }

        return $result->toEntity(Token::class);
    }

    public static function createToken(array $attributes = [], int $count = 1): Token|array
    {
        $result = self::makeToken($attributes, $count);

        if (is_array($result)) {
            return array_map(fn (User $r) => $r->toModel()->save(false)?->toEntity(Token::class), $result);
        }

        return $result->toModel()->save(false)?->toEntity(Token::class);
    }
}
