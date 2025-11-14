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
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TaskList extends Command
{
    protected static $defaultName = 'tasks:list';

    protected function configure(): void
    {
        $this->setDescription('Get all task');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tasks = Task::getAll();

        $table = new Table($output);
        $table->setHeaders(['Key', 'Status', 'Execution time', 'Last run', 'Retries']);

        foreach ($tasks as $task) {
            $table->addRow([
                $task->_key,
                $this->colorizeStatus($task->status),
                $this->formatTime($task->execution_time),
                is_null($task->last_run) ? '-' : date('Y-m-d H:i:s', $task->last_run),
                $task->retries,
            ]);
        }

        $table->render();

        return Command::SUCCESS;
    }

    private function colorizeStatus(string $status): string
    {
        return match ($status) {
            TaskStatus::PENDING => '<comment>'.$status.'</comment>',
            TaskStatus::RUNNING => '<info>'.$status.'</info>',
            TaskStatus::COMPLETED => '<info>'.$status.'</info>',
            TaskStatus::FAILED => '<error>'.$status.'</error>',
            TaskStatus::CANCELLED => '<comment>'.$status.'</comment>',
            default => $status,
        };
    }

    private function formatTime(string $executionTime): string
    {
        if (! str_starts_with($executionTime, 'at:')) {
            return $executionTime;
        }

        $time = substr($executionTime, 3);

        return 'at:'.carbon((int) $time)->format('Y-m-d H:i:s');

    }
}
