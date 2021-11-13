<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Console\Database;

use Core\Support\Storage;
use Core\Database\Connection\Connection;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Display list of databases
 */
class Show extends Command
{
    protected static $defaultName = 'db:show';

    protected function configure()
    {
        $this->setDescription('Display list of databases');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (config('app.env') === 'test') {
            $output->writeln('<fg=yellow>WARNING: You are running migrations on APP_ENV=test</>');
        }
        
        $rows = [];

        if (config('database.driver') === 'mysql') {
            $databases = Connection::getInstance()->executeQuery("SHOW DATABASES")->fetchAll();

            foreach ($databases as $db) {
                $rows[] = [$db->Database];
            }
        }

        else {
            $databases = Storage::path(config('storage.sqlite'))->getFiles();

            foreach ($databases as $db) {
                $rows[] = [basename($db)];
            }
        }

        $table = new Table($output);
        $table->setHeaders(['Schemas']);
        $table->setRows($rows);
        $table->render();

        return Command::SUCCESS;
    }
}
