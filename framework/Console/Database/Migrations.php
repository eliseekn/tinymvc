<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Console\Database;

use Framework\System\Storage;
use App\Database\Seeds\Seeder;
use Framework\Database\Schema;
use Framework\Database\QueryBuilder;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Manage database migrations 
 */
class Migrations extends Command
{
    protected static $defaultName = 'db:migration';

    protected function configure()
    {
        $this->setDescription('Manage migrations tables');
        $this->setHelp('This command allows you to create or drop migrations tables');
        $this->addArgument('migration', InputArgument::OPTIONAL|InputArgument::IS_ARRAY, 'The name of table migration (separated by space if many)');
        $this->addOption('migrate', null, InputOption::VALUE_NONE, 'Migrate tables');
        $this->addOption('rollup', null, InputOption::VALUE_NONE, 'Rollup migrations tables');
        $this->addOption('delete', null, InputOption::VALUE_NONE, 'Delete migrations tables');
        $this->addOption('seed', null, InputOption::VALUE_NONE, 'Insert all seeds');
        $this->addOption('list', null, InputOption::VALUE_NONE, 'Display the list of migrated tables');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $migrations = $input->getArgument('migration');

        if ($input->getOption('migrate')) {
            if (!empty($migrations)) {
                foreach ($migrations as $table) {
                    $this->migrate($output, $table);
                }
            }

            else {
                foreach (Storage::path(config('storage.migrations'))->getFiles() as $file) {
                    $table = get_file_name($file);
                    $this->migrate($output, $table);
                }
            }

            if ($input->getOption('seed')) {
                Seeder::run();
                $output->writeln('<info>All seeds inserted successfully</info>');
            }
        }

        else if ($input->getOption('rollup')) {
            if (!empty($migrations)) {
                foreach ($migrations as $table) {
                    $this->rollup($output, $table);
                }
            }

            else {
                foreach (Storage::path(config('storage.migrations'))->getFiles() as $file) {
                    $table = get_file_name($file);
                    $this->rollup($output, $table);
                }
            }

            if ($input->getOption('seed')) {
                Seeder::run();
                $output->writeln('<info>All seeds inserted successfully</info>');
            }
        }

        else if ($input->getOption('delete')) {
            if (!empty($migrations)) {
                foreach ($migrations as $table) {
                    $this->delete($output, $table);
                }
            }

            else {
                foreach (Storage::path(config('storage.migrations'))->getFiles() as $file) {
                    $table = get_file_name($file);
                    $this->delete($output, $table);
                }
            }
        }

        else if ($input->getOption('list')) {
            $this->listMigrationsTables($output);
        }

        else {
            $output->writeln('<error>Invalid command line arguments. Type "php console list" for commands list</error>');
        }

        return Command::SUCCESS;
    }

    protected function migrate(OutputInterface $output, string $table)
    {
        if ($this->IsAlreadyMigrated($table)) {
            $output->writeln('<fg=yellow>Table "' . $table . '" already migrated</>');
            return;
        }

        $this->getMigration($table)->create();
        $output->writeln('<info>Table "' . $table . '" migrated successfully</info>');

        $this->saveMigrationTable($output, $table);
    }

    protected function delete(OutputInterface $output, string $table)
    {
        if (!$this->IsAlreadyMigrated($table)) {
            $output->writeln('<fg=yellow>Table "' . $table . '" has not been migrated</>');
            return;
        }

        $this->getMigration($table)->drop();
        $output->writeln('<info>Table "' . $table . '" deleted successfully</info>');

        $this->removeMigrationTable($table);
    }

    protected function rollup(OutputInterface $output, string $table)
    {
        $this->delete($output, $table);
        $this->migrate($output, $table);
    }
    
    protected function listMigrationsTables(OutputInterface $output): void
    {
        $tables = QueryBuilder::table('migrations')
            ->select('*')
            ->orderBy('created_at', 'DESC')
            ->fetchAll();

        $rows = [];

        foreach ($tables as $table) {
            $rows[] = [$table->migration];
        }

        $table = new Table($output);
        $table->setHeaders(['Tables']);
        $table->setRows($rows);
        $table->render();
    }
    
    protected function saveMigrationTable(OutputInterface $output, string $table): void
    {
        if (!QueryBuilder::tableExists('migrations')) {
            Schema::createTable('migrations')
                ->addInt('id')->primaryKey()
                ->addString('migration')
                ->create();

            $output->writeln('<info>Migrations tables created successfully</info>');
        }

        QueryBuilder::table('migrations')
            ->insert(['migration' => $table])
            ->execute();
    }
    
    protected function removeMigrationTable(string $table): void
    {
        QueryBuilder::table('migrations')->delete()
            ->where('migration', $table)
            ->execute();
    }
    
    protected function IsAlreadyMigrated(string $table): bool
    {
        if (!QueryBuilder::tableExists('migrations')) {
            return false;
        }

        return QueryBuilder::table('migrations')
            ->select('*')
            ->where('migration', $table)
            ->exists();
    }
    
    protected function getMigration(string $table)
    {
        $migration = '\App\Database\Migrations\\' . $table;
        return new $migration();
    }
}
