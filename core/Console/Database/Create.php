<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Console\Database;

use Core\Database\Connection\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Create new database 
 */
class Create extends Command
{
    protected static $defaultName = 'db:create';

    protected function configure()
    {
        $this->setDescription('Create new database');
        $this->addArgument('database', InputArgument::IS_ARRAY|InputArgument::OPTIONAL, 'The name of database (separated by space if many) or leave empty to for application database');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (config('app.env') === 'test') {
            $output->writeln('<fg=yellow>WARNING: You are running migrations on APP_ENV=test</>');
        }
        
        $databases = $input->getArgument('database');

        if (is_null($databases) || empty($databases)) {
            $db = config('app.env') !== 'test' ? config('database.name') : 
               config('database.name') . config('testing.database.suffix') ;

            $databases = [$db];
        }

        foreach ($databases as $database) {
            if (Connection::getInstance()->schemaExists($database)) {
                $output->writeln('<fg=yellow>Database "' . $database . '" already exists</>');
            } else {
                Connection::getInstance()->createSchema($database);
                $output->writeln('<info>Database "' . $database . '" has been created</info>');
            }
        }

        return Command::SUCCESS;
    }
}
