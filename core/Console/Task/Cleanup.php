<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Console\Task;

use Core\Task\Task;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Cleanup extends Command
{
    protected static $defaultName = 'tasks:cleanup';

    protected function configure(): void
    {
        $this->setDescription('Clean up old completed and failed tasks');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $deleted = Task::cleanup();

        $output->writeln('<bg=blue;options=bold> INFO </> Cleaned up '.$deleted.' tasks.');

        return Command::SUCCESS;
    }
}
