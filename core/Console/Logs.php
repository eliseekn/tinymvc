<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Console;

use Core\Support\Storage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Clear saved logs
 */
class Logs extends Command
{
    protected static $defaultName = 'logs:clear';

    protected function configure()
    {
        $this->setDescription('Clear saved logs');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        Storage::path(config('storage.logs'))->deleteDir();
        $output->writeln('<info>Logs have been cleared</info>');

        return Command::SUCCESS;
    }
}
