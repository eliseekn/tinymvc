<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Setup application
 */
class App extends Command
{
    protected static $defaultName = 'app:setup';

    protected function configure()
    {
        $this->setDescription('Setup application main configuration');
        $this->setHelp('This command allows you to setup application main configuration');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<question>Application name (ex: TinyMVC):</question> ');
        $app_name = fgets(STDIN);

        $output->writeln('<question>Application language (ex: en):</question> ');
        $app_lang = fgets(STDIN);

        $output->writeln('<question>Application url (ex: http://example.com/):</question> ');
        $app_url = fgets(STDIN);

        $output->writeln('<question>Application folder name (leave empty if not using sub-folder):</question> ');
        $app_folder = fgets(STDIN);

        $output->writeln('<question>MySQL hostname (ex: localhost):</question> ');
        $mysql_host = fgets(STDIN);

        $output->writeln('<question>MySQL database name:</question> ');
        $mysql_database = fgets(STDIN);

        $output->writeln('<question>MySQL database username:</question> ');
        $mysql_username = fgets(STDIN);

        $output->writeln('<question>MySQL database password:</question> ');
        $mysql_password = fgets(STDIN);

        $encryption_key = base64_encode(random_string(30, true));

        $config = [
            'APP_NAME' => $app_name,
            'APP_LANG' => $app_lang,
            'APP_FOLDER' => $app_folder,
            'APP_URL' => $app_url,
            'MYSQL_HOST' => $mysql_host,
            'MYSQL_DATABASE' => $mysql_database,
            'MYSQL_USERNAME' => $mysql_username,
            'MYSQL_PASSWORD' => $mysql_password,
            'ENCRYPTION_KEY' => $encryption_key
        ];

        save_env($config);

        $output->writeln('<info>Application has been setted up successfully</info>');

        return Command::SUCCESS;
    }
}
