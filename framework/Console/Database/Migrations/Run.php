<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Console\Database\Migrations;

use Framework\System\Storage;
use Framework\Database\Migration;
use Framework\Database\QueryBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Run migrations tables
 */
class Run extends Command
{
    protected static $defaultName = 'db:migrations:run';

    protected function configure()
    {
        $this->setDescription('Run migrations tables');
        $this->addArgument('table', InputArgument::OPTIONAL|InputArgument::IS_ARRAY, 'The name of migrations tables (separated by space if many)');
        $this->addOption('seed', null, InputOption::VALUE_NONE, 'Insert all seeds');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $tables = $input->getArgument('table');

        if (!QueryBuilder::tableExists('migrations')) {
            Migration::table('migrations')
                ->addInt('id')->primaryKey()
                ->addString('name')
                ->migrate();

            $output->writeln('<info>Migrations tables have been created</info>');
        }

        if (empty($tables)) {
            foreach (Storage::path(config('storage.migrations'))->files() as $file) {
                $this->migrate($output, get_file_name($file));
            }
        }

        else {
            foreach ($tables as $table) {
                $this->migrate($output, $table);
            }
        }

        if ($input->getOption('seed')) {
            $this->getApplication()->find('db:seed')->run(new ArrayInput($tables), $output);
        }

        return Command::SUCCESS;
    }

    protected function migrate(OutputInterface $output, string $table)
    {
        if ($this->isMigrated($table)) {
            $output->writeln('<fg=yellow>Table "' . $table . '" has already been migrated</>');
            return;
        }

        //create migration table
        $migration = '\App\Database\Migrations\\' . $table;
        (new $migration())->create();
        
        //add migration table from migrated tables
        QueryBuilder::table('migrations')
            ->insert(['name' => $table])
            ->execute();

        $output->writeln('<info>Table "' . $table . '" has been migrated</info>');
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
