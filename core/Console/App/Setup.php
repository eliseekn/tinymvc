<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Console\App;

use Core\Support\Config;
use Core\Support\Storage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Setup application
 */
class Setup extends Command
{
    protected static $defaultName = 'app:setup';

    protected function configure()
    {
        $this->setDescription('Setup application');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = [];
        $finish_setup = false;

        $config['APP_ENV'] = 'development' . PHP_EOL;

        while (!$finish_setup) {
            $output->write('<info>Application name (default: TinyMVC):</info> ');
            $config['APP_NAME'] = fgets(STDIN);
            if (strlen($config['APP_NAME']) <= 1) $config['APP_NAME'] = 'TinyMVC' . PHP_EOL;

            $output->write('<info>Application url (default: http://127.0.0.1:8080/):</info> ');
            $app_url = fgets(STDIN);

            if (strlen($app_url) <= 1) $app_url = 'http://127.0.0.1:8080/';

            $app_url = rtrim($app_url, PHP_EOL);
            
            if (!empty($app_url) && $app_url[-1] !== '/') $app_url = $app_url . '/';

            $config['APP_URL'] = $app_url . PHP_EOL;

            $output->write('<info>Application language (default: en):</info> ');
            $config['APP_LANG'] = fgets(STDIN);
            if (strlen($config['APP_LANG']) <= 1) $config['APP_LANG'] = 'en' . PHP_EOL . PHP_EOL;

            $output->write('<info>Database driver [mysql (default), sqlite]:</info> ');
            $config['DB_DRIVER'] = fgets(STDIN);
            if (strlen($config['DB_DRIVER']) <= 1 || !in_array($config['DB_DRIVER'], ['mysql', 'sqlite'])) $config['DB_DRIVER'] = 'mysql' . PHP_EOL;

            $output->write('<info>Database host (default: 127.0.0.1):</info> ');
            $config['DB_HOST'] = fgets(STDIN);
            if (strlen($config['DB_HOST']) <= 1) $config['DB_HOST'] = '127.0.0.1' . PHP_EOL;

            $output->write('<info>Database port (default: 3306):</info> ');
            $config['DB_PORT'] = fgets(STDIN);
            if (strlen($config['DB_PORT']) <= 1) $config['DB_PORT'] = '3306' . PHP_EOL;

            $output->write('<info>Database name (default: tinymvc):</info> ');
            $config['DB_NAME'] = fgets(STDIN);
            if (strlen($config['DB_NAME']) <= 1) $config['DB_NAME'] = 'tinymvc' . PHP_EOL;

            $output->write('<info>Database username:</info> ');
            $config['DB_USERNAME'] = fgets(STDIN);

            $output->write('<info>Database password:</info> ');
            $config['DB_PASSWORD'] = fgets(STDIN) . PHP_EOL;

            $output->write('<info>Mailer transport [smtp (default), sendmail]:</info> ');
            $config['MAILER_TRANSPORT'] = fgets(STDIN);
            if (strlen($config['MAILER_TRANSPORT']) <= 1 || !in_array($config['MAILER_TRANSPORT'], ['smtp', 'sendmail'])) $config['MAILER_TRANSPORT'] = 'smtp' . PHP_EOL;

            $output->write('<info>Mailer host (default: 127.0.0.1):</info> ');
            $config['MAILER_HOST'] = fgets(STDIN);
            if (strlen($config['MAILER_HOST']) <= 1) $config['MAILER_HOST'] = '127.0.0.1' . PHP_EOL;

            $output->write('<info>Mailer port (default: 1025):</info> ');
            $config['MAILER_PORT'] = fgets(STDIN);
            if (strlen($config['MAILER_PORT']) <= 1) $config['MAILER_PORT'] = '1025' . PHP_EOL;

            $output->write('<info>Mailer username:</info> ');
            $config['MAILER_USERNAME'] = fgets(STDIN);

            $output->write('<info>Mailer password:</info> ');
            $config['MAILER_PASSWORD'] = fgets(STDIN) . PHP_EOL;

            $finish_setup = true;
        }

        $config['ENCRYPTION_KEY'] = generate_token();

        Config::saveEnv($config);

        if (!Storage::path(config('storage.lang'))->isFile(config('app.lang'))) {
            Storage::path(config('storage.lang'))->copyFile('en.php', config('app.lang') . '.php');
        }

        $output->writeln('<info>Application has been setted up. You need to restart server to make changes applies.</info>');

        return Command::SUCCESS;
    }
}
