<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Console;

use Core\Support\Storage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Clear twig templates cache
 */
class Cache extends Command
{
    protected static $defaultName = 'clear:twig';

    protected function configure(): void
    {
        $this->setDescription('Clear twig templates cache');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        Storage::path(config('storage.cache'))->deleteDir();
        $output->writeln('<info>[INFO] Twig templates cache has been cleared</info>');

        return Command::SUCCESS;
    }
}
