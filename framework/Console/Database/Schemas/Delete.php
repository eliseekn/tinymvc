<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Console\Database\Schemas;

use Framework\Database\Database;
use Framework\Database\QueryBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Delete database schema 
 */
class Delete extends Command
{
    protected static $defaultName = 'db:schemas:delete';

    protected function configure()
    {
        $this->setDescription('Delete database schema');
        $this->addArgument('database', InputArgument::OPTIONAL|InputArgument::IS_ARRAY, 'The name of databases (separated by space if many)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $databases = $input->getArgument('database');

        foreach ($databases as $database) {
            if (!QueryBuilder::schemaExists($database)) {
                $output->writeln('<fg=yellow>Database schema "' . $database . '" does not exists</>');
            } else {
                Database::connection()->query("DROP DATABASE IF EXISTS $database");
                $output->writeln('<info>Database schema "' . $database . '" has been deleted</info>');
            }
        }

        return Command::SUCCESS;
    }

    protected function deleteDatabase(OutputInterface $output, string $database)
    {
        
    }
}
