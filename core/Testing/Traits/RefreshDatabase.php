<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Testing\Traits;

/**
 * Automatically refresh database
 */
trait RefreshDatabase
{
    public function refreshDatabase()
    {
        shell_exec('php console migrations:reset -q');
    }
}
