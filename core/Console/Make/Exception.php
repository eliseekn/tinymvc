<?php

/**
 * @copyright (2019 - 2022) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Console\Make;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Create new custom exception 
 */
class Exception extends Command
{
    protected static $defaultName = 'make:exception';

    protected function configure()
    {
        $this->setDescription('Create new custom exception');
        $this->addArgument('exception', InputArgument::REQUIRED, 'The name of exception');
        $this->addOption('message', 'm', InputOption::VALUE_REQUIRED, 'The exception message');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $exception = $input->getArgument('exception');
        $message = $input->getOption('message');

        list(, $class) = Make::generateClass($exception, '');

        if (!Make::createException($exception, $message)) {
            $output->writeln('<fg=yellow>Failed to create exception "' . $class . '"</fg>');
        }

        $output->writeln('<info>Exception "' . $class . '" has been created</info>');

        return Command::SUCCESS;
    }
}
