<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
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

    protected function configure(): void
    {
        $this->setDescription('Create new database');
        $this->addArgument('database', InputArgument::IS_ARRAY|InputArgument::OPTIONAL, 'The name of database (separated by space if many) or leave empty to for application database');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $databases = $input->getArgument('database');

        if (empty($databases)) {
            $db = config('app.env') !== 'test'
                ? config('database.name')
                : config('database.name') . config('tests.database.suffix') ;

            $databases = [$db];
        }

        foreach ($databases as $database) {
            if (Connection::getInstance()->schemaExists($database)) {
                $output->writeln('<comment>[WARNING] Database "' . $database . '" already exists</>');
            } else {
                Connection::getInstance()->createSchema($database);
                $output->writeln('<info>[INFO] Database "' . $database . '" has been created</info>');
            }
        }

        return Command::SUCCESS;
    }
}
