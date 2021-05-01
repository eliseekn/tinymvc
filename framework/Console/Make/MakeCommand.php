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
        $this->setDescription('Create new console command');
        $this->setHelp('This command allows you to create new console command');
        $this->addArgument('class', InputArgument::REQUIRED, 'The class name');
        $this->addArgument('name', InputArgument::REQUIRED, 'The command name');
        $this->addOption('description', null, InputOption::VALUE_REQUIRED, 'The command description (inside "")');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $class = $input->getArgument('class');
        $name = $input->getArgument('name');

        list($_name, $class) = Make::generateClass($class, '');

        if (!Make::createCommand($name, $input->getOption('description'))) {
            $output->writeln('<fg=yellow>Failed to create command "' . $class . '"</fg>');
        }

        $output->writeln('<info>Command "' . $class . '" has been created</info>');

        return Command::SUCCESS;
    }
}
