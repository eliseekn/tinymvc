<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Console\Make;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Create new helper 
 */
class Helper extends Command
{
    protected static $defaultName = 'make:helper';

    protected function configure()
    {
        $this->setDescription('Create new helper');
        $this->addArgument('helper', InputArgument::REQUIRED|InputArgument::IS_ARRAY, 'The name of helper (separated by space if many)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helpers = $input->getArgument('helper');

        foreach ($helpers as $helper) {
            list(, $class) = Make::generateClass($helper, '');

            if (!Make::createHelper($helper)) {
                $output->writeln('<fg=yellow>Failed to create helper "' . $class . '"</fg>');
            }

            $output->writeln('<info>Helper "' . $class . '" has been created</info>');
        }

        return Command::SUCCESS;
    }
}
