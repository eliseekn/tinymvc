<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Testing\Concerns;

use Faker\Factory;
use Faker\Generator as Faker;

/**
 * Load faker library
 */
trait LoadFaker
{
    public Faker $faker;

    public function loadFaker(): void
    {
        $this->faker = Factory::create(config('app.lang'));
    }
}
