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
 * Generate application encryption key
 */
class Key extends Command
{
    protected static $defaultName = 'app:key';

    protected function configure()
    {
        $this->setDescription('Generate application encryption key');
        $this->setHelp('This command allows you to generate application encryption key');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = [
            'APP_NAME' => config('app.name') . PHP_EOL,
            'APP_LANG' => config('app.lang') . PHP_EOL,
            'APP_FOLDER' => config('app.folder') . PHP_EOL,
            'APP_URL' => config('app.url') . PHP_EOL,
            'MYSQL_HOST' => config('database.host') . PHP_EOL,
            'MYSQL_DATABASE' => config('database.name') . PHP_EOL,
            'MYSQL_USERNAME' => config('database.username') . PHP_EOL,
            'MYSQL_PASSWORD' => config('database.password') . PHP_EOL,
            'ENCRYPTION_KEY' => base64_encode(random_string(30, true))
        ];

        save_env($config);

        $output->writeln('<info>Application encryption key has been generated</info>');

        return Command::SUCCESS;
    }
}
