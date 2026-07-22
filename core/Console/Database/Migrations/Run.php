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
use Core\Database\Schema;
use Core\Support\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Run migrations.
 */
class Run extends Command
{
    protected function configure(): void
    {
        $this->setName('migrations:run');
        $this->setDescription('Run migrations');
        $this->addArgument('migration', InputArgument::OPTIONAL | InputArgument::IS_ARRAY, 'The name of migrations (separated by space if many)');
        $this->addOption('seed', null, InputOption::VALUE_NONE, 'Run seeders');
    }

    /**
     * @throws ExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->getApplication()->find('db:create')->run(new ArrayInput([]), $output);

        if (! Connection::getInstance()->tableExists('migrations')) {
            Schema::createTable('migrations')
                ->addPrimaryKey()->notNull()
                ->addVarChar('name')->notNull()
                ->addDefaultTimestamps()
                ->run();

            $output->writeln('<bg=blue;options=bold> INFO </> Migrations tables have been created.');
        }

        $migrations = ! empty($input->getArgument('migration'))
            ? $input->getArgument('migration')
            : storage(config('storage.migrations'))->getFiles();

        foreach ($migrations as $migration) {
            $this->migrate($output, File::getName($migration));
        }

        if ($input->getOption('seed')) {
            $this->getApplication()->find('db:seed')->run(new ArrayInput($migrations), $output);
        }

        return Command::SUCCESS;
    }

    protected function migrate(OutputInterface $output, string $migration): void
    {
        if (str_contains($migration, '\\')) {
            $migration = explode('\\', $migration);
            $migration = end($migration);
        }

        if ($this->isMigrated($migration)) {
            $output->writeln('<bg=bright-yellow;fg=black> WARN </> Migration <options=bold>'.$migration.'</> has already been migrated.');

            return;
        }

        $migrationClass = '\App\Database\Migrations\\'.$migration;
        (new $migrationClass)->up();

        QueryBuilder::table('migrations')
            ->insert(['name' => $migration])
            ->execute();

        $output->writeln('<bg=blue;options=bold> INFO </> Migration <options=bold>'.$migration.'</> has been migrated.');
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
