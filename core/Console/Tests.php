<?php

namespace Core\Console;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Process\Process;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Run PHPUnit tests cases
 */
class Tests extends Command
{
    protected static $defaultName = 'test';

    protected function configure(): void
    {
        $this->setDescription('Run tests cases');
        $this->addArgument('filename', InputArgument::OPTIONAL, 'Specify test filename');
        $this->addArgument('filter', InputArgument::OPTIONAL, 'Specify test case');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (config('app.env') !== 'test') {
            $output->writeln('<fg=yellow>You must set APP_ENV to test in application configuration</>');
            return Command::FAILURE;
        }

        $this->getApplication()->find('db:create')->run(new ArrayInput([]), $output);
        $this->getApplication()->find('migrations:reset')->run(new ArrayInput([]), $output);
        $this->getApplication()->find('db:seed')->run(new ArrayInput([]), $output);

        $server = new Process(['php', '-S', config('tests.host') . ':' . config('tests.port')]);
        $server->setTimeout(null);
        $server->start();

        $args = ['php', 'vendor/bin/phpunit'];

        if (!is_null($input->getArgument('filename'))) {
            $args = array_merge($args, ['tests/' . $input->getArgument('filename')]);
        }

        if (!is_null($input->getArgument('filter'))) {
            $args = array_merge($args, ['--filter=' . $input->getArgument('filter')]);
        }

        $phpunit = new Process($args);
        $phpunit->setTimeout(null);
        $phpunit->start();
        $phpunit->wait(function ($type, $buffer) { echo $buffer; });

        $server->stop();

        return Command::SUCCESS;
    }
}
