<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Console\Database;

use Exception;
use Framework\Database\Database;
use Framework\Database\QueryBuilder;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Manage database schemas 
 */
class Schemas extends Command
{
    protected static $defaultName = 'db:schema';

    protected function configure()
    {
        $this->setDescription('Manage MySQL schemas');
        $this->setHelp('This command allows you to create or delete schemas');
        $this->addArgument('schema', InputArgument::OPTIONAL|InputArgument::IS_ARRAY, 'The name of schema (separated by space if many)');
        $this->addOption('create', null, InputOption::VALUE_NONE, 'Create new schemas');
        $this->addOption('delete', null, InputOption::VALUE_NONE, 'Delete schemas');
        $this->addOption('list', null, InputOption::VALUE_NONE, 'Display the list of schemas');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('create')) {
            foreach ($input->getArgument('schema') as $schema) {
                $this->createSchema($output, $schema);
            }
        } 
        
        else if ($input->getOption('delete')) {
            foreach ($input->getArgument('schema') as $schema) {
                $this->dropSchema($output, $schema);
            }
        }

        else if ($input->getOption('list')) {
            $this->listSchemas($output);
        }

        else {
            $output->writeln('<error>Invalid command line arguments. Type "php console list" for commands list</error>');
        }

        return Command::SUCCESS;
    }

    public static function listSchemas(OutputInterface $output): void
    {
        $databases = Database::connection()->query("SHOW DATABASES")->fetchAll();
        $rows = [];

        foreach ($databases as $db) {
            $rows[] = [$db->Database];
        }

        $table = new Table($output);
        $table->setHeaders(['Schemas']);
        $table->setRows($rows);
        $table->render();
    }

    protected function createSchema(OutputInterface $output, string $schema)
    {
        if (QueryBuilder::schemaExists($schema)) {
            $output->writeln('<fg=yellow>Schema "' . $schema . '" already exists</error>');
        } else {
            Database::connection()->query("CREATE DATABASE $schema CHARACTER SET " . config('database.charset') . " COLLATE " . config('database.collation'));
            $output->writeln('<info>Schema "' . $schema . '" created successfully</info>');
        }
    }

    protected function dropSchema(OutputInterface $output, string $schema)
    {
        if (!QueryBuilder::schemaExists($schema)) {
            $output->writeln('<fg=yellow>Schema "' . $schema . '" does not exists</>');
        } else {
            Database::connection()->query("DROP DATABASE IF EXISTS $schema");
            $output->writeln('<info>Schema "' . $schema . '" deleted successfully</info>');
        }
    }
}
