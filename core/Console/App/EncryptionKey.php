<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Console\App;

use Core\Support\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Generate application encryption key
 */
class EncryptionKey extends Command
{
    protected static $defaultName = 'app:key';

    protected function configure()
    {
        $this->setDescription('Generate application encryption key');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = [
            'APP_ENV' => config('app.env') . PHP_EOL,
            'APP_NAME' => config('app.name') . PHP_EOL,
            'APP_URL' => config('app.url') . PHP_EOL,
            'APP_LANG' => config('app.lang') . PHP_EOL,
            'DB_DRIVER' => config('database.driver') . PHP_EOL,
            'DB_HOST' => config('database.mysql.host') . PHP_EOL,
            'DB_PORT' => config('database.mysql.port') . PHP_EOL,
            'DB_NAME' => config('database.name') . PHP_EOL,
            'DB_USERNAME' => config('database.mysql.username') . PHP_EOL,
            'DB_PASSWORD' => config('database.mysql.password') . PHP_EOL,
            'ENCRYPTION_KEY' => generate_token()
        ];

        Config::saveEnv($config);

        $output->writeln('<info>Application encryption key has been generated</info>');

        return Command::SUCCESS;
    }
}
