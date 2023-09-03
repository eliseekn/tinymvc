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
use Symfony\Component\Console\Style\SymfonyStyle;

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
        $io = new SymfonyStyle($input, $output);

        $config['APP_ENV'] = 'local' . PHP_EOL;
        $config['APP_NAME'] = $this->getInput($io->ask('Application name', 'TinyMVC'));
        $config['APP_URL'] = $this->getInput($io->ask('Application url', 'http://127.0.0.1:8080/'));
        $config['APP_LANG'] = $this->getInput($io->ask('Application language', 'en'));

        $config['DB_DRIVER'] = $this->getInput($io->choice('Application driver', ['mysql', 'sqlite'], 'mysql'));
        $config['DB_HOST'] = $this->getInput($io->ask('Database host', '127.0.0.1'));
        $config['DB_PORT'] = $this->getInput($io->ask('Database port', '3306'));
        $config['DB_NAME'] = $this->getInput($io->ask('Database name', 'tinymvc'));
        $config['DB_USERNAME'] = $this->getInput($io->ask('Database username', 'root'));
        $config['DB_PASSWORD'] = $this->getInput($io->ask('Database password'));

        $config['MAILER_TRANSPORT'] = $this->getInput($io->choice('Mailer transport', ['smtp', 'sendmail'], 'smtp'));
        $config['MAILER_HOST'] = $this->getInput($io->ask('Mailer host', '127.0.0.1'));
        $config['MAILER_PORT'] = $this->getInput($io->ask('Mailer port', '1025'));
        $config['MAILER_USERNAME'] = $this->getInput($io->ask('Mailer username'));
        $config['MAILER_PASSWORD'] = $this->getInput($io->ask('Mailer password'));
        $config['ENCRYPTION_KEY'] = generate_token();

        Config::saveEnv($config);

        if (!Storage::path(config('storage.lang'))->isFile(config('app.lang'))) {
            Storage::path(config('storage.lang'))->copyFile('en.php', config('app.lang') . '.php');
        }

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
