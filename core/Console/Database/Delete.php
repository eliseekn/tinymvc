<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Console\Database;

use Core\Database\QueryBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Delete database.
 */
class Delete extends Command
{
    protected static $defaultName = 'db:delete';

    protected function configure(): void
    {
        $this->setDescription('Delete database');
        $this->addArgument('database', InputArgument::IS_ARRAY, 'The name of database (separated by space if many)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $connection = QueryBuilder::connection();
        $databases = $input->getArgument('database');

        if (empty($databases)) {
            $databases = [get_filename(QueryBuilder::connection()->getDBName())];
        }

        foreach ($databases as $database) {
            if (! $connection->schemaExists($database)) {
                $output->writeln('<bg=bright-yellow;fg=black> WARN </> Database <options=bold>'.$database.'</> does not exists.');
            } else {
                $connection->deleteSchema($database);
                $output->writeln('<bg=blue;options=bold> INFO </> Database <options=bold>'.$database.'</> has been deleted.');
            }
        }

        return Command::SUCCESS;
    }
}
