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
 * Create new database.
 */
class Create extends Command
{
    protected static $defaultName = 'db:create';

    protected function configure(): void
    {
        $this->setDescription('Create new database');
        $this->addArgument('database', InputArgument::IS_ARRAY | InputArgument::OPTIONAL, 'The name of database (separated by space if many) or leave empty to for application database');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $connection = QueryBuilder::connection();
        $databases = $input->getArgument('database');

        if (empty($databases)) {
            $databases = [get_file_name(QueryBuilder::connection()->getDBName())];
        }

        foreach ($databases as $database) {
            if ($connection->schemaExists($database)) {
                $output->writeln('<bg=bright-yellow;fg=black> WARN </> Database <options=bold>'.$database.'</> already exists.');
            } else {

                $connection->createSchema($database);
                $output->writeln('<bg=blue;options=bold> INFO </> Database <options=bold>'.$database.'</> has been created.');
            }
        }

        return Command::SUCCESS;
    }
}
