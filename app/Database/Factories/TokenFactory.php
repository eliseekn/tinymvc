<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Database\Factories;

use App\Enums\TokenDescription;
use Carbon\Carbon;
use Core\Database\Factory;
use App\Database\Models\Token;

class TokenFactory extends Factory
{
    protected static $model = Token::class;

    public function __construct(int $count = 1)
    {
        parent::__construct($count);
    }

    public function data(): array
    {
        return [
            'email' => $this->faker->unique()->email,
            'value' => generate_token(),
            'expire' => Carbon::now()->addHour()->toDateTimeString(),
            'description' => TokenDescription::PASSWORD_RESET_TOKEN->value
        ];
    }
}