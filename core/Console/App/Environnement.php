<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Console\App;

use Core\Support\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Define application environnement.
 */
class Environnement extends Command
{
    protected static $defaultName = 'app:env';

    protected function configure(): void
    {
        $this->setDescription('Define application environnement');
        $this->addArgument('name', InputArgument::REQUIRED, 'Specify application environnement (test, local or prod)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (! in_array($input->getArgument('name'), ['test', 'prod', 'local'])) {
            $output->writeln('<error>[ERROR] Invalid application environnement "' . $input->getArgument('name') . '"</error>');

            return Command::FAILURE;
        }

        Config::loadEnv();

        Config::saveEnv([
            'APP_ENV' => $input->getArgument('name') . PHP_EOL,
            'APP_NAME' => env('APP_NAME') . PHP_EOL,
            'APP_URL' => env('APP_URL') . PHP_EOL,
            'APP_LANG' => env('APP_LANG') . PHP_EOL,
            'DB_DRIVER' => env('DB_DRIVER') . PHP_EOL,
            'DB_NAME' => env('DB_NAME') . PHP_EOL,
            'DB_HOST' => env('DB_HOST') . PHP_EOL,
            'DB_PORT' => env('DB_PORT') . PHP_EOL,
            'DB_USERNAME' => env('DB_USERNAME') . PHP_EOL,
            'DB_PASSWORD' => env('DB_PASSWORD') . PHP_EOL,
            'MAILER_HOST' => env('MAILER_HOST') . PHP_EOL,
            'MAILER_PORT' => env('MAILER_PORT') . PHP_EOL,
            'MAILER_USERNAME' => env('MAILER_USERNAME') . PHP_EOL,
            'MAILER_PASSWORD' => env('MAILER_PASSWORD') . PHP_EOL,
            'MAILER_SENDER_NAME' => env('MAILER_SENDER_NAME') . PHP_EOL,
            'MAILER_SENDER_MAIL' => env('MAILER_SENDER_MAIL') . PHP_EOL,
            'ENCRYPTION_KEY' => env('ENCRYPTION_KEY'),
        ]);

        $output->writeln('<info>[INFO] Application environnement has been defined to "' . $input->getArgument('name') . '"</info>');

        return Command::SUCCESS;
    }
}
