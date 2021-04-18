<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Console\Database;

use Exception;
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
        $this->setHelp('This command allows you to run or delete migrations');
        $this->addArgument('migration', InputArgument::OPTIONAL|InputArgument::IS_ARRAY, 'The name of table migration (separated by space if many).');
        $this->addOption('run', null, InputOption::VALUE_NONE, 'Migrate tables');
        $this->addOption('refresh', 'r', InputOption::VALUE_NONE, 'Refresh migrations tables');
        $this->addOption('delete', 'd', InputOption::VALUE_NONE, 'Delete migrations tables');
        $this->addOption('seed', 's', InputOption::VALUE_NONE, 'Insert all seeds');
        $this->addOption('list', 'l', InputOption::VALUE_NONE, 'Display the list of migrations tables');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $migrations = $input->getArgument('migration');

        if ($input->getOption('run')) {
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

        else if ($input->getOption('refresh')) {
            if (!empty($migrations)) {
                foreach ($migrations as $table) {
                    $this->refresh($output, $table);
                }
            }

            else {
                foreach (Storage::path(config('storage.migrations'))->getFiles() as $file) {
                    $table = get_file_name($file);
                    $this->refresh($output, $table);
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

        throw new Exception('Invalid command line arguments');

        return Command::SUCCESS;
    }

    protected function migrate(OutputInterface $output, string $table)
    {
        if ($this->IsAlreadyMigrated($table)) {
            $output->writeln('<fg=yellow>Table "' . $table . '" already migrated</>');
            return;
        }

        $this->saveMigrationTable($output, $table);

        $table = '\App\Database\Migrations\\' . $table;
        $table::migrate();
        $output->writeln('<info>Table "' . $table . '" migrated successfully</info>');
    }

    protected function delete(OutputInterface $output, string $table)
    {
        if (!$this->IsAlreadyMigrated($table)) {
            $output->writeln('<fg=yellow>Migration table "' . $table . '" has not been migrated</>');
            return;
        }

        $this->removeMigrationTable($table);

        $table = '\App\Database\Migrations\\' . $table;
        $table::delete();
        $output->writeln('<info>Table "' . $table . '" deleted successfully</info>');
    }

    protected function refresh(OutputInterface $output, string $table)
    {
        $table = '\App\Database\Migrations\\' . $table;
        $table::refresh();
        $output->writeln('<info>Table "' . $table . '" refreshed successfully</info>');
    }
    
    protected function listMigrationsTables(OutputInterface $output): void
    {
        $tables = Storage::path(config('storage.migrations'))->getFiles();
        $rows = [];

        foreach ($tables as $table) {
            $rows[] = [$table];
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
}
