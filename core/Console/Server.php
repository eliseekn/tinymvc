<?php

namespace Core\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Start a local server development
 */
class Server extends Command
{
    protected static $defaultName = 'server:start';

    protected function configure()
    {
        $this->setDescription('Start a local server development');
        $this->addArgument('host', InputArgument::OPTIONAL, 'Specify server host');
        $this->addArgument('port', InputArgument::OPTIONAL, 'Specify server port');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $host = $input->getArgument('host') ?? '127.0.0.1';
        $port = $input->getArgument('port') ?? 8080;

        shell_exec("php -S {$host}:{$port}");

        return Command::SUCCESS;
    }
}
