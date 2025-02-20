<?php

declare(strict_types=1);

namespace Core\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Start a local server development.
 */
class Shell extends Command
{
    protected static $defaultName = 'shell';

    protected function configure(): void
    {
        $this->setDescription('Start a interactive PHP shell');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $shell = new \Psy\Shell();
        $shell->run();

        return Command::SUCCESS;
    }
}
