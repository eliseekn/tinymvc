<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Console\Database\Migrations;

use Core\Database\Connection\Connection;
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

    protected function configure(): void
    {
        $this->setDescription('Delete migrations tables');
        $this->addArgument('table', InputArgument::OPTIONAL|InputArgument::IS_ARRAY, 'The name of migrations tables (separated by space if many)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tables = $input->getArgument('table');

        if (empty($tables)) {
            $files = Storage::path(config('storage.migrations'))->getFiles();

            foreach ($files as $file) {
                $this->delete($output, get_file_name($file));
            }

            return Command::SUCCESS;
        }

        foreach ($tables as $table) {
            $this->delete($output, $table);
        }

        return Command::SUCCESS;
    }

    protected function delete(OutputInterface $output, string $table): void
    {
        if (!$this->isMigrated($table)) {
            $output->writeln('<comment>[WARNING] Table "' . $table . '" has not been migrated</>');
            return;
        }

        $migration = '\App\Database\Migrations\\' . $table;
        (new $migration())->drop();
        
        QueryBuilder::table('migrations')
            ->deleteWhere('name', $table)
            ->execute();

        $output->writeln('<info>[INFO] Table "' . $table . '" has been deleted</info>');
    }

    protected function isMigrated(string $table): bool
    {
        if (!Connection::getInstance()->tableExists('migrations')) {
            return false;
        }

        return QueryBuilder::table('migrations')
            ->select('*')
            ->where('name', $table)
            ->exists();
    }
}
