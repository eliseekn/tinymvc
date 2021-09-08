<?php

namespace Core\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

/**
 * Run PHPUnit tests cases
 */
class Tests extends Command
{
    protected static $defaultName = 'tests:run';

    protected function configure()
    {
        $this->setDescription('Run tests cases');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $server_process = new Process(['php', '-S', config('testing.host') . ':' . config('testing.port')]);
        $server_process->setTimeout(null);
        $server_process->start();

        $process = new Process(['php', 'vendor/bin/paratest', '-p' . config('testing.process')]);
        $process->setTimeout(null);
        $process->start();

        $process->wait(function ($type, $buffer) use ($output) {
            $output->write($buffer);
        });

        $server_process->stop();

        return Command::SUCCESS;
    }
}
