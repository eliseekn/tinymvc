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
    protected function configure(): void
    {
        $this->setName('tasks:cleanup');
        $this->setDescription('Clean up old completed and failed tasks');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (Task::cleanup()) {
            $output->writeln('<bg=blue;options=bold> INFO </> Tasks has been cleaned up.');
        } else {
            $output->writeln('<bg=bright-yellow;fg=black> WARN </> Failed to cleanup tasks.');
        }

        return Command::SUCCESS;
    }
}
