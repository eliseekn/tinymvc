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
 * Create new model file 
 */
class Model extends Command
{
    protected static $defaultName = 'make:model';

    protected function configure()
    {
        $this->setDescription('Create new model');
        $this->addArgument('model', InputArgument::REQUIRED|InputArgument::IS_ARRAY, 'The name of model table (separated by space if many)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $repositories = $input->getArgument('model');

        foreach ($repositories as $model) {
            list($name, $class) = Make::generateClass($model, '');

            if (!Make::createModel($name)) {
                $output->writeln('<fg=yellow>Failed to create model "' . Make::fixPluralTypo($class, true) . '"</fg>');
            }

            $output->writeln('<info>Model "' . Make::fixPluralTypo($class, true) . '" has been created</info>');
        }

        return Command::SUCCESS;
    }
}
