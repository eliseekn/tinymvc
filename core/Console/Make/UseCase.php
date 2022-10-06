<?php

/**
 * @copyright (2019 - 2022) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
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
class UseCase extends Command
{
    protected static $defaultName = 'make:use-case';

    protected function configure()
    {
        $this->setDescription('Create new use cases');
        $this->addArgument('model', InputArgument::REQUIRED|InputArgument::IS_ARRAY, 'The name of model (separated by space if many)');
        $this->addOption('namespace', null, InputOption::VALUE_OPTIONAL, 'Specify namespace (base: App\Http\UseCases)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $models = $input->getArgument('model');

        foreach ($models as $model) {
            if (!Make::createUseCases($model, $input->getOption('namespace'))) {
                $output->writeln('<fg=yellow>Failed to create "' . $model . ' use cases"</fg>');
            }

            $output->writeln('<info>"' . $model . ' use cases" have been created</info>');
        }

        return Command::SUCCESS;
    }
}
