<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Console\Make;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Create new console command 
 */
class Cmd extends Command
{
    protected static $defaultName = 'make:cmd';

    protected function configure()
    {
        $this->setDescription('Create new console command');
        $this->addArgument('cmd', InputArgument::REQUIRED, 'The command class name');
        $this->addOption('name', null, InputOption::VALUE_REQUIRED, 'The command name');
        $this->addOption('description', null, InputOption::VALUE_REQUIRED, 'The command description (inside "")');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        list($name, $class) = Make::generateClass($input->getArgument('cmd'), '', true);

        if (!Make::createCommand($input->getArgument('cmd'), $input->getOption('name'), $input->getOption('description'))) {
            $output->writeln('<fg=yellow>Failed to create command "' . $class . '"</fg>');
        }

        $output->writeln('<info>Command "' . $class . '" has been created</info>');

        return Command::SUCCESS;
    }
}
