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
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Create new seeder.
 */
class Seeder extends Command
{
    protected static $defaultName = 'make:seeder';

    protected function configure(): void
    {
        $this->setDescription('Create new seeder');
        $this->addArgument('seeder', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'The name of the model (separated by space if many)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $seeders = $input->getArgument('seeder');

        foreach ($seeders as $seeder) {
            list(, $class) = Maker::generateClass($seeder, 'seeder', true, true);

            if (! Maker::createSeeder($seeder)) {
                $output->writeln('<bg=red;options=bold> ERROR </> Failed to create seeder <options=bold>'.Maker::fixPlural($class, true).'</>.');
            } else {
                $output->writeln('<bg=blue;options=bold> INFO </> Seeder <options=bold>'.Maker::fixPlural($class, true).'</> has been created.');
            }
        }

        return Command::SUCCESS;
    }
}
