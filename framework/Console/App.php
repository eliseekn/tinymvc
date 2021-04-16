<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Console;

use Framework\System\Storage;
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
        $this->setDescription('Setup application');
        $this->setHelp('This command allows you to setup application');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * echo '[+] Application name (ex: TinyMVC): ';
        $app_name = fgets(STDIN);

        echo '[+] Application language (ex: en): ';
        $app_lang = fgets(STDIN);

        echo '[+] Application url (ex: http://example.com/): ';
        $app_url = fgets(STDIN);

        echo '[+] Application folder name (leave empty if using root folder): ';
        $app_folder = fgets(STDIN);

        echo '[+] MySQL hostname (ex: localhost): ';
        $mysql_host = fgets(STDIN);

        echo '[+] MySQL database name: ';
        $mysql_database = fgets(STDIN);

        echo '[+] MySQL database username: ';
        $mysql_username = fgets(STDIN);

        echo '[+] MySQL database password: ';
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

        echo PHP_EOL; 
        echo "\e[0;32;40m[+] Application has been setted up successfully\e[0m";
        exit(PHP_EOL . PHP_EOL);
         */

        return Command::SUCCESS;
    }
}
