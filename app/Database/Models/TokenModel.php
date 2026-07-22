<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Database\Models;

use App\Database\Entities\Token;
use Carbon\Carbon;
use Core\Database\Factory\HasFactory;
use Core\Database\Model;

class TokenModel extends Model
{
    use HasFactory;

    protected static function defaultTable(): string
    {
        return 'tokens';
    }

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

    public static function findByDescription(string $identifier, string $description): ?Token
    {
        return self::query()
            ->select('*')
            ->where('identifier', $identifier)
            ->and('description', $description)
            ->first()
            ?->toEntity(Token::class);
    }

    public static function generate(string $identifier, string $description, string $value): ?Token
    {
        $token = self::findByDescription($identifier, $description);

        if (is_null($token)) {
            $token = (new Token)
                ->setIdentifier($identifier)
                ->setDescription($description)
                ->setExpiresAt(Carbon::now()->addHour());
        }

        $token->setValue($value);

        $saved = self::query()->setAttributes($token->toArray())->save();

        return $saved ? $token : null;
    }
}
