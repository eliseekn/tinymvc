<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Support;

use Symfony\Component\Process\Process;

abstract class Console
{
    public static function run(string $command): void
    {
        $db = new Process(['php', 'console', $command]);
        $db->setTimeout(null);
        $db->disableOutput();
        $db->run();
    }
}
