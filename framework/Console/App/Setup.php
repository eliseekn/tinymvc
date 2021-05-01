<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Console\App;

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
        $this->setDescription('Setup application main configuration');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = [];
        $finish_setup = false;

        while (!$finish_setup) {
            $output->write('<info>Application name (eg: TinyMVC):</info> ');
            $config['APP_NAME'] = fgets(STDIN);

            if (strlen($config['APP_NAME']) <= 1) {
                $output->writeln('<error>This option is required</error>');
                continue;
            }

            $output->write('<info>Application url (eg: http://127.0.0.1:8080/):</info> ');
            $app_url = fgets(STDIN);

            if (strlen($app_url) <= 1) {
                $output->writeln('<error>This option is required</error>');
                continue;
            }

            $config['APP_URL'] = add_slash(rtrim($app_url, PHP_EOL)) . PHP_EOL;

            $output->write('<info>Application language (eg: en):</info> ');
            $config['APP_LANG'] = fgets(STDIN);

            if (strlen($config['APP_LANG']) <= 1) {
                $output->writeln('<error>This option is required</error>');
                continue;
            }

            $output->write('<info>Application currency (eg: USD):</info> ');
            $config['APP_CURRENCY'] = fgets(STDIN);

            if (strlen($config['APP_CURRENCY']) <= 1) {
                $output->writeln('<error>This option is required</error>');
                continue;
            }

            $output->write('<info>Database host (eg: localhost):</info> ');
            $config['DB_HOST'] = fgets(STDIN);

            if (strlen($config['DB_HOST']) <= 1) {
                $output->writeln('<error>This option is required</error>');
                continue;
            }

            $output->write('<info>Database name (eg: tinymvc):</info> ');
            $config['DB_NAME'] = fgets(STDIN);

            if (strlen($config['DB_NAME']) <= 1) {
                $output->writeln('<error>This option is required</error>');
                continue;
            }

            $output->write('<info>Database username:</info> ');
            $config['DB_USERNAME'] = fgets(STDIN);

            if (strlen($config['DB_USERNAME']) <= 1) {
                $output->writeln('<error>This option is required</error>');
                continue;
            }

            $output->write('<info>Database password:</info> ');
            $config['DB_PASSWORD'] = fgets(STDIN);

            if (strlen($config['DB_PASSWORD']) <= 1) {
                $output->writeln('<error>This option is required</error>');
                continue;
            }

            $finish_setup = true;
        }

        $config['ENCRYPTION_KEY'] = base64_encode(random_string(30, true));

        save_env($config);

        $output->writeln('<info>Application has been setted up</info>');

        return Command::SUCCESS;
    }
}
