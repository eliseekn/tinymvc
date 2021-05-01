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
        $this->setHelp('This command allows you to setup application main configuration');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = [];

        $output->writeln('<question>Application name (ex: TinyMVC):</question> ');
        $config['APP_NAME'] = fgets(STDIN);

        $output->writeln('<question>Application language (ex: en):</question> ');
        $config['APP_LANG'] = fgets(STDIN);

        $output->writeln('<question>Application url (ex: http://localhost:80/):</question> ');
        $config['APP_URL'] = fgets(STDIN);
        $config['APP_URL'] = add_slash($config['APP_URL']);

        $output->writeln('<question>Application folder name (leave empty if not using sub-folder):</question> ');
        $config['APP_FOLDER'] = fgets(STDIN);

        $output->writeln('<question>Application currency (ex: USD):</question> ');
        $config['APP_CURRENCY'] = fgets(STDIN);

        $output->writeln('<question>MySQL hostname (ex: localhost):</question> ');
        $config['MYSQL_HOST'] = fgets(STDIN);

        $output->writeln('<question>MySQL database name:</question> ');
        $config['MYSQL_DATABASE'] = fgets(STDIN);

        $output->writeln('<question>MySQL database username:</question> ');
        $config['MYSQL_USERNAME'] = fgets(STDIN);

        $output->writeln('<question>MySQL database password:</question> ');
        $config['MYSQL_PASSWORD'] = fgets(STDIN);

        $config['ENCRYPTION_KEY'] = base64_encode(random_string(30, true));

        save_env($config);

        $output->writeln('<info>Application has been setted up</info>');

        return Command::SUCCESS;
    }
}
