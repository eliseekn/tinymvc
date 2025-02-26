<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Console\App;

use Core\Support\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Setup application.
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
        $io = new SymfonyStyle($input, $output);

        $config['APP_ENV'] = 'local' . PHP_EOL;
        $config['APP_NAME'] = $this->getInput($io->ask('Application name', 'TinyMVC'));
        $config['APP_URL'] = $this->getInput($io->ask('Application url', 'http://127.0.0.1:8888/'));
        $config['APP_LANG'] = $this->getInput($io->ask('Application language', 'en'));

        $config['DB_DRIVER'] = $this->getInput($io->choice('Application driver', ['mysql', 'sqlite', 'pgsql'], 'mysql'));
        $config['DB_HOST'] = $this->getInput($io->ask('Database host', '127.0.0.1'));
        $config['DB_PORT'] = $this->getInput($io->ask('Database port', '3306'));
        $config['DB_NAME'] = $this->getInput($io->ask('Database name', 'tinymvc'));
        $config['DB_USERNAME'] = $this->getInput($io->ask('Database username', 'root'));
        $config['DB_PASSWORD'] = $this->getInput($io->ask('Database password'));

        $config['MAILER_HOST'] = $this->getInput($io->ask('Mailer host', '127.0.0.1'));
        $config['MAILER_PORT'] = $this->getInput($io->ask('Mailer port', '1025'));
        $config['MAILER_USERNAME'] = $this->getInput($io->ask('Mailer username'));
        $config['MAILER_PASSWORD'] = $this->getInput($io->ask('Mailer password'));
        $config['MAILER_SENDER_NAME'] = $this->getInput($io->ask('Mailer sender name'));
        $config['MAILER_SENDER_MAIL'] = $this->getInput($io->ask('Mailer sender mail'));

        $config['ENCRYPTION_KEY'] = generate_token();

        Config::saveEnv($config);

        $output->writeln('<info>[INFO] Application has been set up. You need to restart server to apply changes.</info>');

        return Command::SUCCESS;
    }

    private function getInput(?string $input): string
    {
        if (is_null($input)) {
            return PHP_EOL;
        }

        return $input . PHP_EOL;
    }
}
