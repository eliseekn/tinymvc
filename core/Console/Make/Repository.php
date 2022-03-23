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
 * Create new model repository 
 */
class Repository extends Command
{
    protected static $defaultName = 'make:repository';

    protected function configure()
    {
        $this->setDescription('Create new model repository');
        $this->addArgument('repository', InputArgument::REQUIRED|InputArgument::IS_ARRAY, 'The name of model repository table (separated by space if many)');
        $this->addOption('namespace', null, InputOption::VALUE_OPTIONAL, 'Specify namespace (base: App\Database\Repositories)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $repositories = $input->getArgument('repository');

        foreach ($repositories as $repository) {
            list(, $class) = Make::generateClass($repository, 'repository', true, true);

            if (!Make::createRepository($repository, $input->getOption('namespace'))) {
                $output->writeln('<fg=yellow>Failed to create repository "' . Make::fixPluralTypo($class, true) . '"</fg>');
            }

            $output->writeln('<info>repository "' . Make::fixPluralTypo($class, true) . '" has been created</info>');
        }

        return Command::SUCCESS;
    }
}
