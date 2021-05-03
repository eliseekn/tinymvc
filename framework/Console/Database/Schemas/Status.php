<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Console\Database\Schemas;

use Framework\Database\Database;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Display list of databases 
 */
class Status extends Command
{
    protected static $defaultName = 'db:schemas:status';

    protected function configure()
    {
        $this->setDescription('Display list of databases');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $databases = Database::connection()->query("SHOW DATABASES")->fetchAll();
        $rows = [];

        foreach ($databases as $db) {
            $rows[] = [$db->Database];
        }

        $table = new Table($output);
        $table->setHeaders(['Schemas']);
        $table->setRows($rows);
        $table->render();

        return Command::SUCCESS;
    }
}
