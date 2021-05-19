<?php

namespace Core\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Run PHPUnit tests cases
 */
class Tests extends Command
{
    protected static $defaultName = 'tests:run';

    protected function configure()
    {
        $this->setDescription('Run PHPUnit tests cases');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        system('php vendor/bin/phpunit');

        return Command::SUCCESS;
    }
}
