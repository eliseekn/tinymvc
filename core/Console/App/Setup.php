<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
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

    protected function configure(): void
    {
        $this->setDescription('Setup application');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $config = [];

        $config['APP_ENV'] = 'local' . PHP_EOL;

        $output->write('<question>Application name (default: TinyMVC)</question> ');
        $config['APP_NAME'] = $this->getInput('TinyMVC');

        $output->write('<question>Application url (default: http://127.0.0.1:8080/)</question> ');
        $app_url = trim($this->getInput('http://127.0.0.1:8080/'));
        
        if (!empty($app_url) && $app_url[-1] !== '/') {
            $app_url = $app_url . '/';
        }

        $config['APP_URL'] = $app_url . PHP_EOL;

        $output->write('<question>Application language (default: en)</question> ');
        $config['APP_LANG'] = $this->getInput('en') . PHP_EOL;

        $output->write('<question>Database driver [mysql (default), sqlite]</question> ');
        $config['DB_DRIVER'] = $this->getInput('mysql', ['mysql', 'sqlite']);

        $output->write('<question>Database host (default: 127.0.0.1)</question> ');
        $config['DB_HOST'] = $this->getInput('127.0.0.1');

        $output->write('<question>Database port (default: 3306)</question> ');
        $config['DB_PORT'] = $this->getInput('3306');

        $output->write('<question>Database name (default: tinymvc)</question> ');
        $config['DB_NAME'] = $this->getInput('tinymvc');

        $output->write('<question>Database username (default: root)</question> ');
        $config['DB_USERNAME'] = $this->getInput('root');

        $output->write('<question>Database password</question> ');
        $config['DB_PASSWORD'] = $this->getInput('') . PHP_EOL;

        $output->write('<question>Mailer transport [smtp (default), sendmail]</question> ');
        $config['MAILER_TRANSPORT'] = $this->getInput('smtp', ['smtp', 'sendmail']);

        $output->write('<question>Mailer host (default: 127.0.0.1)</question> ');
        $config['MAILER_HOST'] = $this->getInput('127.0.0.1');

        $output->write('<question>Mailer port (default: 1025)</question> ');
        $config['MAILER_PORT'] = $this->getInput('1025');

        $output->write('<question>Mailer username</question> ');
        $config['MAILER_USERNAME'] = $this->getInput('');

        $output->write('<question>Mailer password</question> ');
        $config['MAILER_PASSWORD'] = $this->getInput('') . PHP_EOL;

        $config['ENCRYPTION_KEY'] = generate_token();

        Config::saveEnv($config);

        if (!Storage::path(config('storage.lang'))->isFile(config('app.lang'))) {
            Storage::path(config('storage.lang'))->copyFile('en.php', config('app.lang') . '.php');
        }

        $output->writeln('<question>[INFO] Application has been setted up. You need to restart server to apply changes.</question>');
        return Command::SUCCESS;
    }

    private function getInput(string $default, ?array $expected = null): string
    {
        $input = fgets(STDIN);

        if (is_null($expected)) {
            if ($input === "\n" || $input = "\r\n") {
                return $default . PHP_EOL;
            }
        }

        if (!in_array(trim($input), $expected)) {
            return $default . PHP_EOL;
        }

        return $input;
    }
}
