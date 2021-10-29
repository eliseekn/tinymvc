<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Testing\Concerns;

use Faker\Factory;

/**
 * Load faker library
 */
trait LoadFaker
{
    /** @var \Faker\Generator $faker */
    public $faker;

    public function loadFaker()
    {
        $this->faker = Factory::create(config('app.lang'));
    }
}
