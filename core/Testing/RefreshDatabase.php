<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Testing;

use Symfony\Component\Process\Process;

/**
 * Automatically refresh database
 */
trait RefreshDatabase
{
    public function refreshDatabase(): void
    {
        $process = new Process(['php', 'console', 'migrations:reset']);
        $process->setTimeout(null);
        $process->disableOutput();
        $process->run();
    }
}
