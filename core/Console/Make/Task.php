<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Console\Make;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Task extends Command
{
    protected static $defaultName = 'make:task';

    protected function configure(): void
    {
        $this->setDescription('Create new task');
        $this->addArgument('task', InputArgument::REQUIRED, 'The task name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        [, $class] = Maker::generateClass($input->getArgument('task'), '', true);

        if (! $this->createTask($input->getArgument('task'))) {
            $output->writeln('<bg=red;options=bold> ERROR </> Failed to create task <options=bold>'.$class.'</>.');

            return Command::FAILURE;
        }

        $output->writeln('<bg=blue;options=bold> INFO </> Task <options=bold>'.$class.'</> has been created.');

        return Command::SUCCESS;
    }

    private function createTask(string $task): bool
    {
        [, $class] = Maker::generateClass($task, singular: true);

        $data = Maker::stubs()->readFile('Task.stub');
        $data = str_replace('CLASSNAME', $class, $data);

        return storage(config('storage.tasks'))->writeFile($class.'.php', $data);
    }
}
