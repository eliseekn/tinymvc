<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Database\Factories;

use Carbon\Carbon;
use Core\Database\Factory;
use App\Database\Models\Token;

class TokenFactory extends Factory
{
    public static $model = Token::class;

    public function __construct(int $count = 1)
    {
        parent::__construct($count);
    }

    public function data()
    {
        return [
            'email' => $this->faker->unique()->email,
            'token' => generate_token(),
            'expire' => Carbon::now()->addHour()->toDateTimeString()
        ];
    }
}