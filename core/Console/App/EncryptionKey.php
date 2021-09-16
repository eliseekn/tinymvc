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
            'APP_LANG' => config('app.lang') . PHP_EOL . PHP_EOL,
            'DB_DRIVER' => config('database.driver') . PHP_EOL,
            'DB_NAME' => config('database.name') . PHP_EOL,
            'DB_HOST' => config('database.' . config('database.driver') . '.host') . PHP_EOL,
            'DB_PORT' => config('database.' . config('database.driver') . '.port') . PHP_EOL,
            'DB_USERNAME' => config('database.' . config('database.driver') . '.username') . PHP_EOL,
            'DB_PASSWORD' => config('database.' . config('database.driver') . '.password') . PHP_EOL . PHP_EOL,
            'MAILER_TRANSPORT' => config('mailer.transport') . PHP_EOL,
            'MAILER_HOST' => config('mailer.' . config('mailer.transport') . '.host') . PHP_EOL,
            'MAILER_PORT' => config('mailer.' . config('mailer.transport') . '.port') . PHP_EOL,
            'MAILER_USERNAME' => config('mailer.' . config('mailer.transport') . '.username') . PHP_EOL,
            'MAILER_PASSWORD' => config('mailer.' . config('mailer.transport') . '.password') . PHP_EOL . PHP_EOL,
            'ENCRYPTION_KEY' => generate_token()
        ];

        Config::saveEnv($config);

        $output->writeln('<info>Application encryption key has been generated</info>');

        return Command::SUCCESS;
    }
}
