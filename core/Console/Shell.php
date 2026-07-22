<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Console;

use Core\Support\AliasLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Start a local server development.
 */
class Shell extends Command
{
    protected function configure(): void
    {
        $this->setName('shell');
        $this->setDescription('Start a interactive PHP shell');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $loader = (new AliasLoader)->register();

        $shell = new \Psy\Shell;
        $shell->run();

        $loader->unregister();

        return Command::SUCCESS;
    }
}
