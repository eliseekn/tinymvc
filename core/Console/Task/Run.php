<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Console\Task;

use Core\Enums\TaskStatus;
use Core\Task\Task;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Run extends Command
{
    protected static $defaultName = 'tasks:run';

    protected function configure(): void
    {
        $this->setDescription('Process pending tasks from the queue');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tasks = Task::process();

        foreach ($tasks as $task) {
            if ($task['status'] === TaskStatus::COMPLETED) {
                $output->writeln('<bg=blue;options=bold> INFO </> Task '.$task['_key'].' completed successfully.');
            } else {
                $output->writeln('<bg=red;options=bold> ERROR </> Task '.$task['_key'].' failed.');
            }
        }

        return Command::SUCCESS;
    }
}
