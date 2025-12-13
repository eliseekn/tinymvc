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

class Load extends Command
{
    protected function configure(): void
    {
        $this->setName('tasks:load');
        $this->setDescription('Load schedules tasks');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        Task::load();
        $output->writeln('<bg=blue;options=bold> INFO </> Schedules tasks loaded.');

        return Command::SUCCESS;
    }
}
