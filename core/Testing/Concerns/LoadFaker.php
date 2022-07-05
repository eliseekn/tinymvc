<?php

/**
 * @copyright (2019 - 2022) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Testing\Concerns;

use Faker\Factory;
use Faker\Generator;

/**
 * Load faker library
 */
trait LoadFaker
{
    public Generator $faker;

    public function loadFaker()
    {
        $this->faker = Factory::create(config('app.lang'));
    }
}
