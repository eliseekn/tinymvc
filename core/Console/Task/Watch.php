<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Console\Task;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Watch extends Command
{
    private bool $shouldStop = false;

    protected function configure(): void
    {
        $this->setName('tasks:watch');
        $this->setDescription('Run task watcher');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (function_exists('pcntl_signal')) {
            pcntl_signal(SIGTERM, [$this, 'onSignal']);
            pcntl_signal(SIGINT, [$this, 'onSignal']);
        }

        $output->writeln('<info>Starting tasks watcher...</info>');
        $output->writeln('<comment>Press Ctrl+C to stop</comment>');

        while (! $this->shouldStop) {
            $this->getApplication()->find('tasks:run')->run(new ArrayInput([]), $output);

            if (memory_get_usage() > 128 * 1024 * 1024) { // 128MB threshold
                gc_collect_cycles();
            }

            if (function_exists('pcntl_signal_dispatch')) {
                pcntl_signal_dispatch();
            }

            if (! $this->shouldStop) {
                sleep(60);
            }
        }

        return Command::SUCCESS;
    }

    private function onSignal(): void
    {
        $this->shouldStop = true;
    }
}
