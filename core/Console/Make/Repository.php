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
 * Create new repository file 
 */
class Repository extends Command
{
    protected static $defaultName = 'make:repository';

    protected function configure()
    {
        $this->setDescription('Create new repository');
        $this->addArgument('repository', InputArgument::REQUIRED|InputArgument::IS_ARRAY, 'The name of repository table (separated by space if many)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $repositories = $input->getArgument('repository');

        foreach ($repositories as $repository) {
            list($name, $class) = Make::generateClass($repository, 'repository', true, true);

            if (!Make::createRepository($repository)) {
                $output->writeln('<fg=yellow>Failed to create repository "' . $class . '"</fg>');
            }

            $output->writeln('<info>Repository "' . $class . '" has been created</info>');

            $this->getApplication()->find('make:model')->run($input, $output);
        }

        return Command::SUCCESS;
    }
}
