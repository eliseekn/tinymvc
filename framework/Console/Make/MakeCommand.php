<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Console\Make;

use Framework\Console\Make;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Create new repository file 
 */
class MakeCommand extends Command
{
    protected static $defaultName = 'make:command';

    protected function configure()
    {
        $this->setDescription('Create new command');
        $this->setHelp('This command allows you to create new command');
        $this->addArgument('name', InputArgument::REQUIRED, 'The name of table command (separated by space if many).');
        $this->addArgument('cmd', InputArgument::REQUIRED, 'The command value.');
        $this->addOption('description', 'd', InputOption::VALUE_REQUIRED, 'The command description (inside "")');
        $this->addOption('usage', 'u', InputOption::VALUE_OPTIONAL, 'The command help usage (inside "")');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $command = $input->getArgument('cmd');

        list($name, $class) = Make::generateClass($name, 'command');

        if (!Make::createCommand($command, $input->getOption('description'), $input->getOption('help'))) {
            $output->writeln('<fg=yellow>Failed to create command "' . $class . '"</fg>');
        }

        $output->writeln('<info>Command "' . $class . '" created succesfully</info>');

        return Command::SUCCESS;
    }
}
