<?php

declare(strict_types=1);

namespace Core\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

/**
 * Run PHPUnit tests cases.
 */
class Test extends Command
{
    protected static $defaultName = 'test';

    protected function configure(): void
    {
        $this->setDescription('Run tests cases');
        $this->addArgument('test', InputArgument::OPTIONAL, 'Specify test name');
        $this->addArgument('filter', InputArgument::OPTIONAL, 'Specify test case');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (config('app.env') !== 'test') {
            $output->writeln('<comment>[WARNING] You must set APP_ENV to test in application configuration</comment>');

            return Command::FAILURE;
        }

        $this->getApplication()->find('db:create')->run(new ArrayInput([]), $output);
        $this->getApplication()->find('migrations:reset')->run(new ArrayInput([]), $output);
        $this->getApplication()->find('db:seed')->run(new ArrayInput([]), $output);

        $server = new Process(['php', '-S', config('tests.host') . ':' . config('tests.port')]);
        $server->setTimeout(null);
        $server->start();

        $args = ['php', 'vendor/bin/phpunit'];

        if (! is_null($input->getArgument('test'))) {
            $filename = str_contains($input->getArgument('test'), '.php')
                ? $input->getArgument('test')
                : $input->getArgument('test') . '.php';

            $args = array_merge($args, ['tests' . DIRECTORY_SEPARATOR . $filename]);
        }

        if (! is_null($input->getArgument('filter'))) {
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
