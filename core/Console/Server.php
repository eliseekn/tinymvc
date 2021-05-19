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
        $this->addArgument('port', InputArgument::OPTIONAL, 'Specify server port');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $port = $input->getArgument('port') ?? 8080;
        system('php -S 127.0.0.1:' . $port);

        return Command::SUCCESS;
    }
}
