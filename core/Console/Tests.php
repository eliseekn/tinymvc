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
        if (config('app.env') !== 'test') {
            $output->writeln('<fg=yellow>You must set APP_ENV to test in application configuration</>');
            return Command::FAILURE;
        }
        
        $server = new Process(['php', '-S', config('testing.host') . ':' . config('testing.port')]);
        $server->setTimeout(null);
        $server->start();

        $phpunit = new Process(['php', 'vendor/bin/phpunit']);
        $phpunit->setTimeout(null);
        $phpunit->start();
        $phpunit->wait(function ($type, $buffer) { echo $buffer; });

        $server->stop();

        return Command::SUCCESS;
    }
}
