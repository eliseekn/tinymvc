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
 * Create new controller 
 */
class Controller extends Command
{
    protected static $defaultName = 'make:controller';

    protected function configure()
    {
        $this->setDescription('Create new controller');
        $this->setHelp('This command allows you to create new controller');
        $this->addArgument('controller', InputArgument::REQUIRED|InputArgument::IS_ARRAY, 'The name of controller (separated by space if many)');
        $this->addOption('namespace', null, InputOption::VALUE_OPTIONAL, 'Specify controller namespace');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $controllers = $input->getArgument('controller');

        foreach ($controllers as $controller) {
            list($name, $class) = Make::generateClass($controller, 'controller');

            if (!Make::createController($controller, $input->getOption('namespace'))) {
                $output->writeln('<fg=yellow>Failed to create controller "' . $class . '"</fg>');
            }

            $output->writeln('<info>Controller "' . $class . '" has been created</info>');
        }

        return Command::SUCCESS;
    }
}
