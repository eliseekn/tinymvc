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
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Cancel extends Command
{
    protected static $defaultName = 'tasks:cancel';

    protected function configure(): void
    {
        $this->setDescription('Cancel task by key value');
        $this->addArgument('key', InputArgument::IS_ARRAY | InputArgument::OPTIONAL, 'The key of task (separated by space if many)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $keys = $input->getArgument('key');

        foreach ($keys as $key) {
            if (Task::cancel($key)) {
                $output->writeln('<bg=bright-yellow;fg=black> WARN </> Failed to cancel task <options=bold>'.$key.'</>.');
            } else {
                $output->writeln('<bg=blue;options=bold> INFO </> Task <options=bold>'.$key.'</> has been cancled.');
            }
        }

        return Command::SUCCESS;
    }
}
