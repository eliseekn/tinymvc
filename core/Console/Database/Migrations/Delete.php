<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Console\Database\Migrations;

use Core\Database\Connection\Connection;
use Core\Database\QueryBuilder;
use Core\Support\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Delete migrations.
 */
class Delete extends Command
{
    protected function configure(): void
    {
        $this->setName('migrations:delete');
        $this->setDescription('Delete migrations');
        $this->addArgument('migration', InputArgument::OPTIONAL | InputArgument::IS_ARRAY, 'The name of migrations (separated by space if many)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $migrations = $input->getArgument('migration');

        if (empty($migrations)) {
            $migrations = storage(config('storage.migrations'))->getFiles();
        }

        foreach ($migrations as $migration) {
            $this->delete($output, File::getName($migration));
        }

        return Command::SUCCESS;
    }

    protected function delete(OutputInterface $output, string $migration): void
    {
        if (! $this->isMigrated($migration)) {
            $output->writeln('<bg=bright-yellow;fg=black> WARN </> Migration <options=bold>'.$migration.'</> has not been migrated.');

            return;
        }

        $migrationClass = '\App\Database\Migrations\\'.$migration;

        if (method_exists($migrationClass, 'down')) {
            (new $migrationClass)->down();
        }

        QueryBuilder::table('migrations')
            ->deleteWhere('name', $migration)
            ->execute();

        $output->writeln('<bg=blue;options=bold> INFO </> Migration <options=bold>'.$migration.'</> has been deleted.');
    }

    protected function isMigrated(string $migration): bool
    {
        if (! Connection::getInstance()->tableExists('migrations')) {
            return false;
        }

        return QueryBuilder::table('migrations')
            ->select('*')
            ->where('name', $migration)
            ->exists();
    }
}
