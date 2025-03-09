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

/**
 * Generate application encryption key.
 */
class EncryptionKey extends Command
{
    protected static $defaultName = 'app:key';

    protected function configure(): void
    {
        $this->setDescription('Generate application encryption key');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        Config::updateEnv(['ENCRYPTION_KEY' => generate_token()]);

        $output->writeln('<bg=blue;options=bold> INFO </> Application encryption key has been generated.');

        return Command::SUCCESS;
    }
}
