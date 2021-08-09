<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Console\Database\Migrations;

use Core\Support\Storage;
use Core\Database\QueryBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Delete migrations tables
 */
class Delete extends Command
{
    protected static $defaultName = 'migrations:delete';

    protected function configure()
    {
        $this->setDescription('Delete migrations tables');
        $this->addArgument('table', InputArgument::OPTIONAL|InputArgument::IS_ARRAY, 'The name of migrations tables (separated by space if many)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $tables = $input->getArgument('table');

        if (empty($tables)) {
            foreach (Storage::path(config('storage.migrations'))->files() as $file) {
                $this->delete($output, get_file_name($file));
            }

            return Command::SUCCESS;
        }

        foreach ($tables as $table) {
            $this->delete($output, $table);
        }

        return Command::SUCCESS;
    }

    protected function delete(OutputInterface $output, string $table)
    {
        if (!$this->isMigrated($table)) {
            $output->writeln('<fg=yellow>Table "' . $table . '" has not been migrated</>');
            return;
        }

        $migration = '\App\Database\Migrations\\' . $table;
        (new $migration())->drop();
        
        QueryBuilder::table('migrations')->deleteWhere('name', $table)->execute();

        $output->writeln('<info>Table "' . $table . '" has been deleted</info>');
    }

    protected function isMigrated(string $table): bool
    {
        if (!QueryBuilder::tableExists('migrations')) {
            return false;
        }

        return QueryBuilder::table('migrations')
            ->select('*')
            ->where('name', $table)
            ->exists();
    }
}
