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
 * Create new actions 
 */
class Actions extends Command
{
    protected static $defaultName = 'make:actions';

    protected function configure()
    {
        $this->setDescription('Create new actions');
        $this->addArgument('model', InputArgument::REQUIRED|InputArgument::IS_ARRAY, 'The name of model (separated by space if many)');
        $this->addOption('namespace', null, InputOption::VALUE_OPTIONAL, 'Specify namespace (base: App\Http\Actions)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $models = $input->getArgument('model');

        foreach ($models as $model) {
            list(, $class) = Make::generateClass($model, 'actions', true, true);

            if (!Make::createActions($model, $input->getOption('namespace'))) {
                $output->writeln('<fg=yellow>Failed to create actions "' . $class . '"</fg>');
            }

            $output->writeln('<info>Actions "' . $class . '" has been created</info>');
        }

        return Command::SUCCESS;
    }
}
