<?php

namespace Core\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

/**
 * Start a local server development
 */
class Server extends Command
{
    protected static $defaultName = 'server:start';

    protected function configure()
    {
        $this->setDescription('Start a local server development');
        $this->addOption('host', null, InputOption::VALUE_OPTIONAL, 'Specify server host');
        $this->addOption('port', null, InputOption::VALUE_OPTIONAL, 'Specify server port');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $host = $input->getOption('host') ?? '127.0.0.1';
        $port = $input->getOption('port') ?? 8080;

        $process = new Process(['php', '-S', "{$host}:{$port}"]);
        $process->setTimeout(null);
        $process->start();

        $process->wait(function ($type, $buffer) use ($output) {
            $output->write($buffer);
        });

        return Command::SUCCESS;
    }
}
