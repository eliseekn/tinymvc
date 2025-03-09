<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Console\Database\Migrations;

use Core\Database\Connection\Connection;
use Core\Database\QueryBuilder;
use Core\Support\Storage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Display migrations status.
 */
class Status extends Command
{
    protected static $defaultName = 'migrations:status';

    protected function configure(): void
    {
        $this->setDescription('Display migrations status');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rows = [];
        $files = storage(config('storage.migrations'))->getFiles();

        foreach ($files as $table) {
            $status = $this->isMigrated(get_file_name($table)) ? 'Yes' : '<fg=red>No</>';
            $rows[] = [get_file_name($table), $status];
        }

        $table = new Table($output);
        $table->setHeaders(['Tables', 'Migrated']);
        $table->setRows($rows);
        $table->render();

        return Command::SUCCESS;
    }

    protected function isMigrated(string $migration): bool
    {
        if (! Connection::getInstance()->tableExists('migrations')) {
            return false;
        }

        return QueryBuilder::table('migrations')
            ->select('name')
            ->where('name', $migration)
            ->exists();
    }
}
