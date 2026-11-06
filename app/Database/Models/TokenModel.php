<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Database\Models;

use App\Database\Entities\Token;
use App\Database\Factories\TokenFactory;
use Carbon\Carbon;
use Core\Database\Attributes\UseFactory;
use Core\Database\Attributes\UseTable;
use Core\Database\Factory\HasFactory;
use Core\Database\Model;

#[UseTable('tokens')]
#[UseFactory(TokenFactory::class)]
class TokenModel extends Model
{
    use HasFactory;

    public static function query(): self
    {
        return new self;
    }

    public static function find(int $id): ?Token
    {
        return self::query()->findBy('id', $id)?->toEntity(Token::class);
    }

    public static function findByValue(string $value): ?Token
    {
        return self::query()->findBy('value', $value)?->toEntity(Token::class);
    }

    public static function findByDescription(string $identifier, string $description, string $authProvider = 'user'): ?Token
    {
        return self::query()
            ->select('*')
            ->where('identifier', $identifier)
            ->and('description', $description)
            ->andRaw('(auth_provider = ? OR auth_provider IS NULL)', [$authProvider])
            ->first()
            ?->toEntity(Token::class);
    }

    public static function generate(string $identifier, string $description, string $value, string $authProvider = 'user'): ?Token
    {
        $token = self::findByDescription($identifier, $description, $authProvider);

        if (is_null($token)) {
            $token = (new Token)
                ->setIdentifier($identifier)
                ->setDescription($description)
                ->setExpiresAt(Carbon::now()->addHour())
                ->setAuthProvider($authProvider);
        }

        $token->setValue($value);

        return self::query()->setAttributes($token->toArray())->save() ? $token : null;
    }
}
