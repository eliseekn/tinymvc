<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Database\Factories;

use App\Database\Models\Token;
use App\Enums\TokenDescription;
use Core\Database\Factory\Factory;

class TokenFactory extends Factory
{
    public string $model = Token::class;

    public function __construct(int $count = 1)
    {
        parent::__construct($this->model, $count);
    }

    public function data(): array
    {
        return [
            'email' => faker()->unique()->email,
            'value' => generate_token(),
            'expires_at' => carbon()->addHour()->toDateTimeString(),
            'description' => TokenDescription::PASSWORD_RESET_TOKEN->value
        ];
    }
}