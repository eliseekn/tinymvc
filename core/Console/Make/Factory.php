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
 * Create new model factory 
 */
class Factory extends Command
{
    protected static $defaultName = 'make:factory';

    protected function configure()
    {
        $this->setDescription('Create new model factory');
        $this->addArgument('factory', InputArgument::REQUIRED|InputArgument::IS_ARRAY, 'The name of model factory table (separated by space if many)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $factorys = $input->getArgument('factory');

        foreach ($factorys as $factory) {
            list($name, $class) = Make::generateClass($factory, 'factory', true, true);

            if (!Make::createFactory($factory)) {
                $output->writeln('<fg=yellow>Failed to create factory "' . Make::fixPluralTypo($class, true) . '"</fg>');
            }

            $output->writeln('<info>Factory "' . Make::fixPluralTypo($class, true) . '" has been created</info>');
        }

        return Command::SUCCESS;
    }
}
