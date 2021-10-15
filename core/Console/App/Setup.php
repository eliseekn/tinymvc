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

        $config['APP_ENV'] = 'local' . PHP_EOL;

        $output->write('<info>Application name (default: TinyMVC):</info> ');
        $config['APP_NAME'] = $this->getInput('TinyMVC');

        $output->write('<info>Application url (default: http://127.0.0.1:8080/):</info> ');
        $app_url = trim($this->getInput('http://127.0.0.1:8080/'));
        
        if (!empty($app_url) && $app_url[-1] !== '/') {
            $app_url = $app_url . '/';
        }

        $config['APP_URL'] = $app_url . PHP_EOL;

        $output->write('<info>Application language (default: en):</info> ');
        $config['APP_LANG'] = $this->getInput('en') . PHP_EOL;

        $output->write('<info>Database driver [mysql (default), sqlite]:</info> ');
        $config['DB_DRIVER'] = $this->getInput('mysql', ['mysql', 'sqlite']);

        $output->write('<info>Database host (default: 127.0.0.1):</info> ');
        $config['DB_HOST'] = $this->getInput('127.0.0.1');

        $output->write('<info>Database port (default: 3306):</info> ');
        $config['DB_PORT'] = $this->getInput('3306');

        $output->write('<info>Database name (default: tinymvc):</info> ');
        $config['DB_NAME'] = $this->getInput('tinymvc');

        $output->write('<info>Database username (default: root):</info> ');
        $config['DB_USERNAME'] = $this->getInput('root');

        $output->write('<info>Database password:</info> ');
        $config['DB_PASSWORD'] = $this->getInput('') . PHP_EOL;

        $output->write('<info>Mailer transport [smtp (default), sendmail]:</info> ');
        $config['MAILER_TRANSPORT'] = $this->getInput('smtp', ['smtp', 'sendmail']);

        $output->write('<info>Mailer host (default: 127.0.0.1):</info> ');
        $config['MAILER_HOST'] = $this->getInput('127.0.0.1');

        $output->write('<info>Mailer port (default: 1025):</info> ');
        $config['MAILER_PORT'] = $this->getInput('1025');

        $output->write('<info>Mailer username:</info> ');
        $config['MAILER_USERNAME'] = $this->getInput('');

        $output->write('<info>Mailer password:</info> ');
        $config['MAILER_PASSWORD'] = $this->getInput('') . PHP_EOL;

        $config['ENCRYPTION_KEY'] = generate_token();

        Config::saveEnv($config);

        if (!Storage::path(config('storage.lang'))->isFile(config('app.lang'))) {
            Storage::path(config('storage.lang'))->copyFile('en.php', config('app.lang') . '.php');
        }

        $output->writeln('<info>Application has been setted up. You need to restart server to apply changes.</info>');

        return Command::SUCCESS;
    }

    private function getInput(string $default, ?array $expected = null)
    {
        $input = fgets(STDIN);

        if (is_null($expected)) {
            if ($input === "\n") {
                return $default . PHP_EOL;
            }
        } else {
            if (!in_array(trim($input), $expected)) {
                return $default . PHP_EOL;
            }
        }

        return $input;
    }
}
