<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Console;

use Core\Console\Make\Maker;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Console application that shares input/output with the file maker (overwrite prompts).
 */
class Application extends BaseApplication
{
    protected function doRunCommand(Command $command, InputInterface $input, OutputInterface $output): int
    {
        Maker::setIo($input, $output);

        return parent::doRunCommand($command, $input, $output);
    }
}
