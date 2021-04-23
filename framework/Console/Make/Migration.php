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
 * Create new migration
 */
class Migration extends Command
{
    protected static $defaultName = 'make:migration';

    protected function configure()
    {
        $this->setDescription('Create new migration');
        $this->setHelp('This command allows you to create new migration');
        $this->addArgument('migration', InputArgument::REQUIRED|InputArgument::IS_ARRAY, 'The name of migration table (separated by space if many)');
        $this->addOption('repository', 'r', InputOption::VALUE_NONE, 'Create new repository');
        $this->addOption('seed', 's', InputOption::VALUE_NONE, 'Create new seed');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $migrations = $input->getArgument('migration');

        foreach ($migrations as $migration) {
            list($name, $class) = Make::generateClass($migration, 'migration');

            if (!Make::createMigration($migration)) {
                $output->writeln('<fg=yellow>Failed to create migration "' . $class . '"</fg>');
            }

            $output->writeln('<info>Migration "' . $class . '" created succesfully</info>');
        }

        if ($input->getOption('repository')) {
            foreach ($migrations as $migration) {
                list($name, $class) = Make::generateClass($migration, 'repository');

                if (!Make::createRepository($migration)) {
                    $output->writeln('<fg=yellow>Failed to create repository "' . $class . '"</fg>');
                }

                $output->writeln('<info>Repository "' . $class . '" created succesfully</info>');
            }
        }

        if ($input->getOption('seed')) {
            foreach ($migrations as $migration) {
                list($name, $class) = Make::generateClass($migration, 'seed');
            
                if (!Make::createSeed($migration)) {
                    $output->writeln('<fg=yellow>Failed to create seed "' . $class . '"</fg>');
                }

                $output->writeln('<info>Seed "' . $class . '" created succesfully</info>');
            }
        }

        return Command::SUCCESS;
    }
}
