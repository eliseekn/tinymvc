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
 * Create new database schema 
 */
class Create extends Command
{
    protected static $defaultName = 'db:schemas:create';

    protected function configure()
    {
        $this->setDescription('Create new database schema');
        $this->addArgument('database', InputArgument::OPTIONAL|InputArgument::IS_ARRAY, 'The name of databases (separated by space if many)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $databases = $input->getArgument('database');

        foreach ($databases as $database) {
            if (QueryBuilder::schemaExists($database)) {
                $output->writeln('<fg=yellow>Database database "' . $database . '" already exists</error>');
            } else {
                Database::connection()->query("CREATE DATABASE {$database} CHARACTER SET " . config('database.charset') . " COLLATE " . config('database.collation'));
                $output->writeln('<info>Database database "' . $database . '" created</info>');
            }
        }

        return Command::SUCCESS;
    }
}
