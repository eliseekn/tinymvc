<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
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
 * Define application environnement
 */
class Environnement extends Command
{
    protected static $defaultName = 'app:env';

    protected function configure()
    {
        $this->setDescription('Define application environnement');
        $this->addArgument('name', InputArgument::REQUIRED, 'Specify application environnement (test, local or prod');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        Config::loadEnv();

        Config::saveEnv([
            'APP_ENV' => $input->getArgument('name') . PHP_EOL,
            'APP_NAME' => env('APP_NAME') . PHP_EOL,
            'APP_URL' => env('APP_URL') . PHP_EOL,
            'APP_LANG' => env('APP_LANG') . PHP_EOL . PHP_EOL,
            'DB_DRIVER' => env('DB_DRIVER') . PHP_EOL,
            'DB_NAME' => env('DB_NAME') . PHP_EOL,
            'DB_HOST' => env('DB_HOST') . PHP_EOL,
            'DB_PORT' => env('DB_PORT') . PHP_EOL,
            'DB_USERNAME' => env('DB_USERNAME') . PHP_EOL,
            'DB_PASSWORD' => env('DB_PASSWORD') . PHP_EOL . PHP_EOL,
            'MAILER_TRANSPORT' => env('MAILER_TRANSPORT') . PHP_EOL,
            'MAILER_HOST' => env('MAILER_HOST') . PHP_EOL,
            'MAILER_PORT' => env('MAILER_PORT') . PHP_EOL,
            'MAILER_USERNAME' => env('MAILER_USERNAME') . PHP_EOL,
            'MAILER_PASSWORD' => env('MAILER_PASSWORD') . PHP_EOL . PHP_EOL,
            'ENCRYPTION_KEY' => env('ENCRYPTION_KEY')
        ]);

        $output->writeln('<info>Application environnement has been defined</info>');

        return Command::SUCCESS;
    }
}
