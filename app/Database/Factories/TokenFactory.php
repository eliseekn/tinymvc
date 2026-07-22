<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Database\Factories;

use App\Database\Models\TokenModel;
use App\Enums\TokenDescription;
use Core\Database\Factory\Factory;

class TokenFactory extends Factory
{
    public string $model = TokenModel::class;

    public function data(): array
    {
        return [
            'identifier' => faker()->unique()->email(),
            'value' => generate_token(),
            'expires_at' => carbon()->addHour()->toDateTimeString(),
            'description' => TokenDescription::PASSWORD_RESET->value,
        ];
    }
}
