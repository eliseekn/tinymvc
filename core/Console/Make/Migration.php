<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Console\Make;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Create new migration.
 */
class Migration extends Command
{
    protected static $defaultName = 'make:migration';

    protected function configure(): void
    {
        $this->setDescription('Create new migration');
        $this->addArgument('migration', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'The name of migration table (separated by space if many)');
        $this->addOption('seeder', null, InputOption::VALUE_NONE, 'Create seeder');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $migrations = $input->getArgument('migration');

        foreach ($migrations as $migration) {
            [, $class] = Maker::generateClass($migration, 'migration');

            if (! Maker::createMigration($migration)) {
                $output->writeln('<bg=red;options=bold> ERROR </> Failed to create migration <options=bold>'.$class.'</>.');
            } else {
                $output->writeln('<bg=blue;options=bold> INFO </> Migration <options=bold>'.$class.'</> has been created.');
            }
        }

        if ($input->getOption('seeder')) {
            foreach ($migrations as $migration) {
                [, $class] = Maker::generateClass($migration, 'seeder', true, true);

                if (! Maker::createSeeder($migration)) {
                    $output->writeln('<bg=red;options=bold> ERROR </> Failed to create seeder <options=bold>'.Maker::fixPlural($class, true).'</>.');
                } else {
                    $output->writeln('<bg=blue;options=bold> INFO </> Seeder <options=bold>'.Maker::fixPlural($class, true).'</> has been created.');
                }
            }
        }

        return Command::SUCCESS;
    }
}
